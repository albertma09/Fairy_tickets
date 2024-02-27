<?php

namespace App\Libraries;

use Carbon\Carbon;
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

}
