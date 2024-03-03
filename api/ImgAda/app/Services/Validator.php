<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class Validator
{
    public static function validateStoreRequest(Request $request)
    {
        Log::info("Llamada al método Validator.validateStoreRequest con: $request");
        $errors = [];

        // Comprobamos si la request lleva el parámetro 'sizes' con algun valor
        if (!$request->filled('pwd')) {
            $errors[] = 'Falta el parámetro "pwd" con las credenciales de la API';
        }

        // Comprobamos si el parámetro 'image' contiene algun archivo
        if (!$request->hasFile('image')) {
            $errors[] = 'No se ha recibido ninguna imagen';
        }

        // Comprobamos si la request lleva el parámetro 'sizes' con algun valor
        if (!$request->filled('sizes')) {
            $errors[] = 'Falta el parámetro "sizes" con los tamaños de reescalado deseados';
        }
        return $errors;
    }

    // Función que comprueba el formato del parámetro 'sizes' se la request
    public static function validateSizesInput($sizes): array
    {
        try {
            $errors = [];
            if (!is_array($sizes)) {
                $errors[] = "'Sizes' ha de ser enviado como un array numérico.";
            } else {
                foreach ($sizes as $size) {
                    if (!is_numeric($size)) {
                        $errors[] = "El tamaño '$size' en 'sizes' no es un número válido.";
                    }
                }
            }
            return $errors;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            throw new Exception($ex->getMessage(), 500);
        }
    }

    // Función que compara unas credenciales con las guardads en las variables de entorno
    public static function verifyCredentials(string $credentials): bool
    {
        try {
            Log::info("verifyCredentials: $credentials");
            return ($credentials == env('API_REQUESTS_KEY'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            throw new Exception($ex->getMessage(), 500);
        }
    }
}
