<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['event_id', 'small', 'medium', 'big', 'main'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    // Función que hace la petición a la API, envía la imagen y el array con los tamaños,
    // intenta recibir un array con los códigos de las imágenes
    private static function sendImageToApi(UploadedFile $imageFile, array $sizes)
    {
        $client = new Client();
        try {
            Log::info("Llamada al método Image.sendImageToApi, archivo: ", [$imageFile]);
            $response = $client->post(env('IMAGE_API_URL'), [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($imageFile->getPathname(), 'r'),
                        'filename' => $imageFile->getClientOriginalName()
                    ],
                    [
                        'name' => 'sizes',
                        'contents' => json_encode($sizes)
                    ],
                    [
                        'name' => 'pwd',
                        'contents' => env('IMAGE_API_KEY')
                    ],
                ],
            ]);
            // Posibles respuestas de la API
            if ($response->getStatusCode() === 200) {
                // Si la respuesta es satisfactoria (200)
                $responseData = json_decode($response->getBody()->getContents(), true);

                // Comprobamos que lo que nos devuelve sea un array
                // y este devuelva tantas URLs como tamaños se han pedido
                if (is_array($responseData['codes']) && count($responseData['codes']) === count($sizes)) {
                    // Devolvemos el array con las URLs
                    return $responseData['codes'];
                } else {
                    // Si ha habido respuesta, pero esta no tiene un formato válido
                    Log::error('Respuesta de la API no válida', [$responseData]);
                    return null;
                }
            } else {
                // Si el código de respuesta no es 200, logeamos el mensaje de error
                $errorMessage = json_decode($response->getBody()->getContents(), true)['error'] ?? 'Error desconocido';
                throw new Exception('Fallo al procesar la imagen: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que recibe la id de un evento, un archivo de imagen y un booleano opcional.
    // Llama a la API que almacena las imágenes y guarda en base de datos la imagen con las 3 URLs recibidas de vuelta.
    // Estas URLs apuntan a las tres versiones de la imagen enviada
    // Si el booleano es 'true' marca la imagen en cuestión como imagen principal del evento, por defecto es 'false'
    public static function createImage(int $eventId, UploadedFile $file, bool $main = false)
    {
        try {
            Log::info("Llamada al método Image.createImage", [$eventId, $file, $main, env('IMAGE_SIZES')]);
            // Parseamos a array el string que viene de la variable de entorno
            $sizesString = env('IMAGE_SIZES');
            $sizesArray = json_decode($sizesString, true);
            // Mandamos los datos a la API
            $urls = self::sendImageToApi($file, $sizesArray);

            // Si la API devuelve URLs guarda en BD, si no lanza una excepción
            if (!$urls) {
                throw new Exception("Algo ha fallado en el guardado de las imágenes");
            }
            self::create(
                [
                    'event_id' => $eventId,
                    'small' => $urls[0],
                    'medium' => $urls[1],
                    'big' => $urls[2],
                    'main' => $main
                ]
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que recupera la imagen principal de un evento, cuyo id recibe por parámetro
    public static function getMainImageByEvent(int $eventId)
    {
        try {
            Log::info("Llamada al método Image.getMainImageByEvent", [$eventId]);

            // Recupera la imagen principal
            $image = self::where('event_id', $eventId)
                ->where('main', true)
                ->first(['small', 'medium', 'big']);

            return $image;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que recupera todas las imágenes de un evento, recibe un booleano por parámetro
    // si este es falso excluye la imagen principal del evento
    public static function getAllImagesByEvent(int $eventId, bool $main = true)
    {
        try {
            Log::info("Llamada al método Image.getAllImagesByEvent. Event Id: $eventId");
            // Declaramos la consulta
            $query = self::where('event_id', $eventId);

            // Si main es false excluímos la imagen principal del evento
            if (!$main) {
                $query->where('main', false);
            }
            // Recuperamos todas las imagenes del evento
            $images = $query->get(['id', 'small', 'medium', 'big']);

            return $images;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que setea a falso el campo main de todas las imágenes del evento que recibe por parámetro
    public static function resetMainImage($eventId)
    {
        try {
            Log::info("Llamada a Image.ResetMainImage. Reseteando todas las imagenes a main:false del evento ID: $eventId");

            self::where('event_id', $eventId)
                ->update(['main' => false]);

            Log::info("Imagen principal reseteada para el evento ID: $eventId");
        } catch (\Exception $e) {
            Log::error("Error al resetear la imagen principal del evento ID: $eventId - " . $e->getMessage());
        }
    }
    // Función que setea a true el campo main de la imagen que recibe por parámetro
    public static function setMainImage($imageId)
    {
        try {
            Log::info("Llamada a método Image.setMainImage: Estableciendo a true el campo main de la imagen ID: $imageId");

            self::where('id', $imageId)
                ->update(['main' => true]);
        } catch (\Exception $e) {
            Log::error("Error al establecer la imagen principal con ID: $imageId - " . $e->getMessage());
        }
    }
}
