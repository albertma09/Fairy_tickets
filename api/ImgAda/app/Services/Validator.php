<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class Validator
{
   public static function validateStoreRequest(Request $request)
   {
      Log::info("Llamada al método Validator.validateStoreRequest con: $request");
      $errors = [];

      // Comprobamos si el parámetro 'image' contiene algun archivo
      if (!$request->hasFile('image')) {
         $errors[] = 'No se ha recibido ninguna imagen';
      }

      // Comprobamos si la request lleva el parámetro 'sizes' con algun valor
      if (!$request->filled('sizes')) {
         $errors[] = 'Falta el parámetro "sizes" con los tamaños de reescalado deseados';
      }

      // Validamos que el parámetro 'sizes' sea un array y contenga valores numéricos
      $sizes = $request->input('sizes');

      // Miramos si 'sizes' es un string, en vez de array, si es así lo convertimos a array
      if (is_string($sizes)) {
         $sizesArray = json_decode($sizes, true);

         if (!is_array($sizesArray)) {
            $errors[] = "'Sizes' ha de ser enviado como un array numérico.";
            
         } else {
            foreach ($sizesArray as $size) {
               if (!is_numeric($size)) {
                  $errors[] = "El tamaño '$size' en 'sizes' no es un número válido.";
               }
            }
         }
      }
      return $errors;
   }
}
