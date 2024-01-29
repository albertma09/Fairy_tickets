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

    public static function getEventsBySearching($item)
    {
        try {
            Log::info('Llamada al método Event.getEventsBySearching', ['valor_de_busqueda =', $item]);

            $events = DB::table('events')
                ->join('locations', 'events.location_id', '=', 'locations.id')
                ->join('sessions', 'events.id', '=', 'sessions.event_id')
                ->join('ticket_types', 'ticket_types.session_id', '=', 'sessions.id')
                ->select('events.id', 'events.name as event', 'events.description','events.image', 'ticket_types.price', 'sessions.date', 'sessions.hour', 'locations.name as location', 'locations.city as city')
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

    public static function getEventsByCategory($item)
    {
        try {
            Log::info('Llamada al método Event.getEventsByCategory', ['category =', $item]);

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

    public static function getEventsById($item)
    {
        try {

            Log::info('Llamada al método Event.getEventById', ['id_evento =', $item]);

            $events = DB::table('events')
                ->select('events.id as event_id', 'events.name', 'events.description', 'events.image', 'sessions.id as session_id', 'sessions.date', 'sessions.hour', 'ticket_types.id as ticket_type_id', 'ticket_types.session_id', 'ticket_types.price', 'ticket_types.description as ticket_types_description', 'ticket_types.ticket_amount', 'locations.name as location_name', 'locations.capacity', 'locations.province', 'locations.city', 'locations.street', 'locations.number', 'locations.cp')
                ->join('sessions', 'events.id', '=', 'sessions.event_id')
                ->join('ticket_types', 'sessions.id', '=', 'ticket_types.session_id')
                ->join('locations', 'events.location_id', '=', 'locations.id')
                ->where('events.id', '=', $item)
                ->orderBy('sessions.date', 'asc')
                ->get();

            return $events;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function getEventsByUserId($item)
    {
        try {

            Log::info('Llamada al método Event.getEventsByUserId', ['Id_user =', $item]);

            $events = DB::table('events')
                ->select('id', 'name', 'description', 'image')
                ->where('user_id', $item)
                ->get();

            return $events;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function createEvent(array $formData)
    {
        try {
            log::info("Llamada al método Event.createEvent", ['datos_formulario =', $formData]);
            // Separamos los datos de los eventos y de las sesiones
            $eventData = $formData;
            unset($eventData['sessionDatetime']);
            unset($eventData['sessionMaxCapacity']);
            unset($eventData['onlineSaleClosure']);
            unset($eventData['customSaleClosure']);
            unset($eventData['nominal_tickets']);
            $eventData['hidden'] = (bool) ($eventData['hidden'] ?? false);

            // Crea el evento y guarda la id
            $event = Event::create($eventData);
            $eventId = $event->id;

            $sessionId = Session::createSession($eventId, $formData);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
