<?php

namespace App\Libraries;

use Exception;
use Carbon\Carbon;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class Utils
{
    // Función que recibe una request de formulario, toma el campo 'price' y le cambia las comas por puntos.
    // Devuelve el mismo array de inputs con el precio cambiado.
    public static function sanitizePriceInput(Request $request)
    {
        try {
            Log::info("Llamada al metodo Utils.sanitizePriceInput");
            $priceValues = $request->input('price', []);

            foreach ($priceValues as &$price) {
                $price = str_replace(',', '.', $price);
            }

            $request->merge(['price' => $priceValues]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que comprueba que el total de tickets no sea mayor que el de la capacidad de la sesión
    public static function checkSessionCapTicketAmount(int $sessionMaxCap, array $ticketAmounts): bool
    {
        try {
            Log::info('Llamada al método EventController.checkSessionCapTicketAmount', ['session_max_cap' => $sessionMaxCap, 'ticket_amounts' => $ticketAmounts]);
            $totalTickets = 0;
            foreach ($ticketAmounts as $amount) {
                $totalTickets += $amount;
                Log::debug($amount);
            }
            return ($sessionMaxCap >= $totalTickets);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que recibe una fecha, hora y minutos y devuelve un objeto datetime de Carbon 
    public static function parseDateTime(string $date, string $hours, string $minutes): Carbon
    {
        return Carbon::parse("$date $hours:$minutes");
    }

    // Función que recibe el tipo de cierre escogido por el usuario y la fecha del evento y
    // devuelve la fecha del cierre según lo que se pida
    // Ajusta el cierre de venta en línea según las especificaciones
    public static function adjustOnlineClosure(Carbon $eventDate, string $onlineClosure, Carbon $customClosure = null): Carbon
    {
        try {
            Log::info("Llamada al método Session.adjustOnlineClosure");
            // Si es personalizada, mantenemos la fecha personalizada
            if ($onlineClosure === 'custom') {
                return $customClosure ?? throw new \InvalidArgumentException('Custom closure not provided.');
            }

            // Si no es personalizada sustraemos el numero que viene a la fecha del evento, solo debería poder ser 0, 1 o 2
            $hoursToSubtract = intval($onlineClosure);
            return $eventDate->subHours($hoursToSubtract);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que reorganiza los datos para crear la sesión
    public static function createSessionData(int $eventId, array $formData, Carbon $onlineClosure): array
    {
        try {
            Log::info("Llamada al método createSessionData");
            $sessionDate = $formData['session_date'];
            $sessionTime = $formData['session_hours'] . ':' . $formData['session_minutes'];

            $nominalTickets = (bool) ($formData['nominal_tickets'] ?? false);

            return [
                'event_id' => $eventId,
                'code' => Str::random(15),
                'date' => $sessionDate,
                'hour' => $sessionTime,
                'session_capacity' => $formData['session_capacity'],
                'online_sale_closure' => $onlineClosure->toDateTimeString(),
                'nominal_tickets' => $nominalTickets,
            ];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que recibe un array de imágenes y devuelve el array con la URL
    //  construída a partir de los códigos de los tamaños de las imágenes
    public static function constructImageUrls($images)
    {
        try {
            Log::info("Llamada a método Utils.constructImageUrls, con imagenes: $images");
            $imageUrls = [];

            foreach ($images as $image) {
                Log::debug($image);
                // Construímos las URLs
                $smallImageUrl = env('IMAGE_API_URL') . "/" . $image->small;
                $mediumImageUrl = env('IMAGE_API_URL') . "/" . $image->medium;
                $bigImageUrl = env('IMAGE_API_URL') . "/" . $image->big;

                $imageUrls[] = [
                    'id' => $image->id,
                    'small' => $smallImageUrl,
                    'medium' => $mediumImageUrl,
                    'big' => $bigImageUrl,
                ];
            }

            return $imageUrls;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [];
        }
    }

    // Función que primero resetea todas las imágenes de un evento a main=false
    // y luego establece la imágen recibida por parámetro como la principal
    public function changeMainImage($eventId, $imageId)
    {
        try {
            Log::info("Llamada al método EventController.changeMainImage con evento: $eventId y imagen $imageId");
            Image::resetMainImage($eventId);
            Image::setMainImage($imageId);
        } catch (\Exception $ex) {
            Log::error("Error al cambiar la imagen principal - " . $ex->getMessage());
        }
    }
}
