<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'location_id', 'name', 'description', 'hidden', 'image'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public static function getEventsBySearching(string $item)
    {
        try {
            Log::info('Llamado al método Event.getEventsBySearching');
            $events = DB::table('events')
                ->join('locations', 'events.location_id', '=', 'locations.id')
                ->join('sessions', 'events.id', '=', 'sessions.event_id')
                ->join('ticket_types', 'ticket_types.session_id', '=', 'sessions.id')
                ->select('events.id', 'events.name as event', 'events.description', 'ticket_types.price', 'sessions.date', 'sessions.hour', 'locations.name as location', 'locations.city as city')
                ->whereRaw('unaccent(lower(events.name)) ILIKE unaccent(lower(?))', ["%$item%"])
                ->orWhereRaw('unaccent(lower(locations.name)) ILIKE unaccent(lower(?))', ["%$item%"])
                ->orWhereRaw('unaccent(lower(locations.city)) ILIKE unaccent(lower(?))', ["%$item%"])
                ->orderBy('events.name')
                ->orderBy('ticket_types.price')
                ->distinct('events.name')
                ->get();

            return $events;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function getEventsByCategory(string $item)
    {
        try {
            Log::info('Llamada al método Event.getEventsByCategory');
            $events = DB::table('events')
                ->join('categories', 'categories.id', '=', 'events.category_id')
                ->join('locations', 'events.location_id', '=', 'locations.id')
                ->join('sessions', 'events.id', '=', 'sessions.event_id')
                ->join('ticket_types', 'ticket_types.session_id', '=', 'sessions.id')
                ->select('events.id', 'events.name as event', 'events.description', 'ticket_types.price', 'sessions.date', 'sessions.hour', 'locations.name as location', 'locations.city as city')
                ->where('categories.name', 'like', '%' . $item . '%')
                ->orderBy('events.name')
                ->orderBy('ticket_types.price')
                ->distinct('events.name')
                ->get();

            return $events;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function getEventsById(int $id)
    {
        try {
            Log::info('Llamada al método Event.getEventsById');

            $events = DB::table('events')
                ->select('events.id as event_id', 'events.name', 'events.description', 'events.image', 'category_id', 'location_id', 'sessions.id as session_id', 'sessions.date', 'sessions.hour', 'sessions.nominal_tickets', 'ticket_types.id as ticket_type_id', 'ticket_types.session_id', 'ticket_types.price', 'ticket_types.description as ticket_types_description', 'ticket_types.ticket_amount', 'locations.name as location_name', 'locations.capacity', 'locations.province', 'locations.city', 'locations.street', 'locations.number', 'locations.cp')
                ->join('sessions', 'events.id', '=', 'sessions.event_id')
                ->join('ticket_types', 'sessions.id', '=', 'ticket_types.session_id')
                ->join('locations', 'events.location_id', '=', 'locations.id')
                ->where('events.id', '=', $id)
                ->orderBy('sessions.date', 'asc')
                ->get();

            return $events;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public static function getEventBySessionId(int $sessionId)
    {
        try {
            $event = DB::table('events')
                ->join('sessions', 'sessions.event_id', '=', 'events.id')
                ->select('events.id', 'events.name', 'events.description', 'sessions.date', 'sessions.hour')
                ->where('sessions.id', $sessionId)
                ->get();

            return $event;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }


    public static function getEventsByUserId(int $userId)
    {
        try {
            Log::info('Llamada al método Event.getEventsByUserId');

            $events = DB::table('events')
                ->select('id', 'name', 'description', 'image')
                ->where('user_id', $userId)
                ->get();

            return $events;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function createEvent(array $formData)
    {
        try {
            Log::info("Llamada al método Event.createEvent");
            // Separamos los datos de los eventos y de las sesiones
            $eventData = $formData;
            unset($eventData['session_date']);
            unset($eventData['session_hours']);
            unset($eventData['session_minutes']);
            unset($eventData['session_capacity']);
            unset($eventData['online_sale_closure']);
            unset($eventData['custom_closure_date']);
            unset($eventData['custom_closure_hours']);
            unset($eventData['custom_closure_minutes']);
            unset($eventData['named_tickets']);
            unset($eventData['ticket_description']);
            unset($eventData['price']);
            unset($eventData['ticket_quantity']);
            $eventData['hidden'] = (bool) ($eventData['hidden'] ?? false);

            // Crea el evento y guarda la id
            $event = Event::create($eventData);
            $eventId = $event->id;
            Session::createSession($eventId, $formData);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function updateEvent(array $eventData)
    {
        try {
            Log::info('Llamada al método Event.updateEvent');

            $eventId = $eventData["event_id"];
            unset($eventData["event_id"]);
            DB::table('events')
                ->where('id', $eventId) // Filtras por algún criterio
                ->update($eventData);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
