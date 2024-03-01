<?php

namespace App\Http\Controllers;

use App\Services\Processor;
use App\Services\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info("Llamada a endpoint store, con request", [$request->all()]);

            // Validación de los inputs, la imagen y el array de tamaños
            // Devolvemos un array de errores bajo el 'scope' del código 400, bad_request
            $errors = Validator::validateStoreRequest($request);
            if (!empty($errors)) {
                return response()->json(['errors' => $errors], 400);
            }

            // Guardamos el contenido de los parámetros enviados
            $imageFile = $request->file('image');
            $sizes = $request->input('sizes');


            Log::debug("Contenido de sizes: $sizes");
            // Procesamos la imagen y recuperamos los códigos de imagen para devolver
            $processedImages = Processor::processImage($imageFile, $sizes);
            if ($processedImages) {
                return response()->json(['codes' => $processedImages]);
            } else {
                return response()->json(['error' => 'Error inesperado: No se ha podido procesar la imagen correctamente'], 500);
            }
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $ex) {
            return response()->json(['error' => 'El tamaño del archivo de imagen excede el límite'], 413);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error inesperado'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $encryptedFilename): Response
    {
        try {
            Log::info('Llamada a endpoint show, con url encryptada: ', [$encryptedFilename]);

            // Desencriptamos el nombre del archivo
            $filename = Crypt::decryptString($encryptedFilename);
            Log::debug("Nombre del archivo: $filename");

            // Crea el path al archivo requerido
            $path = storage_path("app/public/images/$filename");
            Log::debug("Ruta del archivo: $path");

            // Miramos si el archivo existe
            if (!file_exists($path)) {
                return response()->json(['error' => 'Archivo no encontrado'], 404);
            }

            // Recuperamos la imagen
            $file = Storage::get("public/images/$filename");

            // Guardamos el tipo de contenido del archivo que pasaremos
            $type = Storage::mimeType("public/images/$filename");

            return response($file)->header('Content-type', $type);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            Log::error($ex->getMessage());
            return response()->json(['error' => 'No se ha podido desencriptar la url'], 400);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error inesperado'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            Log::info('Llamada a endpoint delete, con request', [$request]);

            $urls = $request->input('urls');

            if (empty($urls)) {
                return response()->json(['error' => 'No se proporcionaron URLs para eliminar archivos'], 400);
            }

            $deletedFiles = Processor::deleteImages($urls);

            return response()->json(['archivos_eliminados' => $deletedFiles]);
        } catch (\Exception $ex) {
            $statusCode = $ex->getCode() ?: 500;
            return response()->json(['error' => $ex->getMessage()], $statusCode);
        }
    }
}
