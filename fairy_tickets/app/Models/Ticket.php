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
    protected $fillable = ['purchase_id', 'ticket_type_id', 'name', 'dni', 'phone_number',];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public static function getTicketsInformation($session_id, $email)
    {

        try{
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
        }catch (Exception $e) {
            Log::debug($e->getMessage());
        }
        


    }

    public static function getEventInformation(){
        try{
            $event = DB::table('events')
            ->join('sessions', 'sessions.event_id', '=', 'events.id')
            ->select('events.id','events.name', 'events.description', 'sessions.date', 'sessions.hour')
            ->where('sessions.id', 1)
            ->get();

            return $event;

        }catch (Exception $e){
            Log::debug($e->getMessage());
        }
    }

    public static function getRememberTickets(){

        try{

            
            $event = DB::table('purchases')
            ->join('sessions', 'sessions.id', '=', 'purchases.session_id')
            ->join('events', 'events.id', '=', 'sessions.event_id')
            ->select('events.id', 'purchases.email', 'events.name as event_name', 'sessions.id as session_id')
            ->where('sessions.date', '=', DB::raw('1 + CURRENT_DATE'))
            ->get();



            return $event;
        }catch(Exception $e){
            Log::debug($e->getMessage());
        }
    }

    public static function sendOpinion(){

        try{

            
            $event = DB::table('purchases')
            ->join('sessions', 'sessions.id', '=', 'purchases.session_id')
            ->join('events', 'events.id', '=', 'sessions.event_id')
            ->select('purchases.name', 'purchases.email', 'events.id','events.name as event_name')
            ->where('sessions.date', '=', DB::raw('CURRENT_DATE - 1'))
            ->get();



            return $event;
        }catch(Exception $e){
            Log::debug($e->getMessage());
        }
    }
}
