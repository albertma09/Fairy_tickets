<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketType extends Model
{
    use HasFactory;
    protected $fillable = ['session_id', 'description', 'price', 'ticket_amount'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // Función que recibe una id de sesión y crea un array de tickets vinculados a ésta
    private static function createSessionTicketsArray(int $sessionId, array $formData): array
    {
        try {
            Log::info('Llamada a Session.createSessionTicketsArray', ['datos_formulario', $formData]);
            // Crear los tipos de ticket relacionados con la sesión
            $ticketData = [];
            for ($i = 0; $i < count($formData['ticket_description']); $i++) {
                $ticketData[] = [
                    'session_id' => $sessionId,
                    'description' => $formData['ticket_description'][$i],
                    'price' => $formData['price'][$i],
                    // Cuando la cantidad de tickets no se indican o viene 0 toma la capacidad máxima de sesión
                    'ticket_amount' => ($formData['ticket_quantity'][$i] !== null && $formData['ticket_quantity'][$i] !== 0) ? $formData['ticket_quantity'][$i] : $formData['session_capacity'],
                ];
            }
            return $ticketData;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
    public static function createSeveralTickets(int $sessionId, array $formData)
    {
        $tickets = Self::createSessionTicketsArray($sessionId, $formData);
        foreach ($tickets as $ticket) {
            Self::create($ticket);
        }
    }
}
