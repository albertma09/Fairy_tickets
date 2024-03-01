<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class Processor
{
   // Función para devolver una cadena de caracteres aleatorios, 
   // cuya longitud recibe por parámetro
   public static function generateRandomChars(int $length): string
   {
      $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      $random_string = '';
      $max_index = strlen($characters) - 1;

      // Genera los caracteres aleatorios
      for ($i = 0; $i < $length; $i++) {
         $random_string .= $characters[rand(0, $max_index)];
      }

      return $random_string;
   }

   private static function storeImage($image, $filename)
   { {
         // Definimos la ruta donde se guardaran las imágenes
         $directory = storage_path("app/public/images");

         // Comprobamos si existe el directorio, si no lo creamos y le damos los permisos necesarios
         if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
         }

         // Guarda la imagen en el storage de la app
         imagejpeg($image, "$directory/$filename");
      }
   }

   private static function resizeImage($originImg, $width)
   {
      try {
         Log::info("Llamada a método resizeImage");

         // Abrimos la imagen recibida
         $image = imagecreatefromstring(file_get_contents($originImg->path()));

         if (!$image) {
            abort(404, 'Image not found or invalid');
         }

         // Dimensiones originales
         $originalWidth = imagesx($image);
         $originalHeight = imagesy($image);

         // Calcula la altura manteniendo el ratio
         $newHeight = intval($originalHeight * ($width / $originalWidth));

         // Creamos una nueva imagen con las dimensiones
         $resizedImage = imagecreatetruecolor($width, $newHeight);

         // Copiamos el contenido de la imagen original a la copia
         imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $newHeight, $originalWidth, $originalHeight);

         // Devolvemos la imagen reescalada
         return $resizedImage;
      } catch (\Exception $ex) {
         Log::error($ex->getMessage());
         throw $ex;
      }
   }

   private static function generateUniqueFilename($extension = 'jpg'): string
   {
      // Generamos un nombre unico basado en el timestamp y una cadena de 3 caracteres aleatorios
      $filename = self::generateRandomChars(2) . time() . "." . $extension;
      return "$filename";
   }

   private static function scaleAndStore($image, array $sizes)
   {
      $codes = [];

      foreach ($sizes as $size) {
         $resizedImage = self::resizeImage($image, $size);

         $filename = self::generateUniqueFilename();

         self::storeImage($resizedImage, $filename);

         $encryptedFilename = Crypt::encryptString($filename);

         // Generamos y añadimos el código de la imagen
         $codes[] = $encryptedFilename;
      }

      return $codes;
   }

   public static function processImage($imageFile, $sizes)
   {
      try {
         // Si sizes es un string, lo convertimos a array
         if (is_string($sizes)) {
            $sizesArray = json_decode($sizes, true);
         }

         // Primero ordenamos los tamaños de menor a mayor
         sort($sizesArray);

         // Reescala y crea los códigos
         $imageCodes = self::scaleAndStore($imageFile, $sizesArray);

         Log::info("Image processed successfully: " . $imageFile);

         return $imageCodes;
      } catch (\Exception $ex) {
         Log::error("Error processing image: " . $ex->getMessage());
         return null;
      }
   }

   public static function deleteImages(array $urls): array
   {
      try {
         $deletedFiles = [];
         foreach ($urls as $url) {
            try {
               $filename = basename(parse_url($url, PHP_URL_PATH));
               $decryptedFilename = Crypt::decryptString($filename);
               $path = storage_path("app/public/images/$decryptedFilename");

               if (file_exists($path)) {
                  unlink($path);
                  $deletedFiles[] = $filename;
               } else {
                  Log::warning("Archivo no encontrado para la URL: $url");
                  throw new Exception("Archivo no encontrado para la URL: $url", 404);
               }
            } catch (\Exception $ex) {
               Log::error("Error al eliminar archivo para la URL: $url - " . $ex->getMessage());
               throw new Exception("Error al eliminar archivo para la URL: $url - " . $ex->getMessage(), 500);
            }
         }
         return $deletedFiles;
      } catch (\Exception $ex) {
         Log::error($ex->getMessage());
         throw new Exception($ex->getMessage(), 500);
      }
   }
}
