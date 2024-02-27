<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_id', 'ticket_type_id', 'name', 'dni', 'phone_number', 'verified'];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public static function getTicketsInformation(int $session_id, string $email)
    {

        try {
            $tickets = DB::table('tickets')
                ->select(
                    'tickets.id',
                    'tickets.name as client_name',
                    'tickets.dni',
                    'purchases.email',
                    'ticket_types.description as ticket_type_name',
                    'ticket_types.price',
                    'events.name as event_name',
                    'events.description as event_description',
                    'sessions.date',
                    'sessions.hour',
                    'locations.name as location_name',
                    'locations.city',
                    'locations.province'
                )
                ->join('purchases', 'purchases.id', '=', 'tickets.purchase_id')
                ->join('ticket_types', 'ticket_types.id', '=', 'tickets.ticket_type_id')
                ->join('sessions', 'ticket_types.session_id', '=', 'sessions.id')
                ->join('events', 'events.id', '=', 'sessions.event_id')
                ->join('locations', 'locations.id', '=', 'events.location_id')
                ->where('purchases.email', '=', $email)
                ->where('ticket_types.session_id', '=', $session_id)
                ->get();

            return $tickets;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public static function getTicketsBySessionId(int $sessionId)
    {

        try {

            $tickets = DB::table('tickets')
                ->select('p.name as buyer_ticket', 'tickets.name as assistant_ticket', 'tickets.id as code', 'tt.description')
                ->join('purchases as p', 'tickets.purchase_id', '=', 'p.id')
                ->join('ticket_types as tt', 'tickets.ticket_type_id', '=', 'tt.id')
                ->where('p.session_id', $sessionId)
                ->get();

            return $tickets;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que valida los tickets en base de datos, recibiendo por parámetro el tipo de ticket y la id de la sesión
    // Buscamos si hay algun ticket con ese id y perteneciente a esa sesión, si no devuelve null
    public static function validateTicket(int $ticketId, int $sessionId)
    {

        try {

            $ticket = DB::table('tickets')
                ->join('purchases', 'purchases.id', '=', 'tickets.purchase_id')
                ->join('sessions', 'sessions.id', '=', 'purchases.session_id')
                ->select('tickets.*', 'purchases.*', 'sessions.*')
                ->where('tickets.id', $ticketId)
                ->where('sessions.id', $sessionId)
                ->first();

            return $ticket;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public static function createRegisterPurchase(array $assistants)
    {
        try {
            Log::info("Llamada al método ticket.createRegisterPurchase");
            
            $assistantsData = $assistants;
            foreach ($assistantsData as $assitant) {
                Ticket::create($assitant);
            }
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
