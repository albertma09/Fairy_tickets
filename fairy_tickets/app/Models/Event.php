<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'date', 'hour', 'hidden', 'nominal_tickets'];

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

    public static function getAllEvents()
    {
        $events = DB::table('events')
            ->join('locations', 'events.location_id', '=', 'locations.id')
            ->select('events.id', 'events.name', 'events.description', 'events.price', 'events.date', 'events.hour', 'locations.name as location', 'locations.city as city')
            ->get();

        return $events;
    }

    public static function getEventsBySearching($item)
    {
        $events = DB::table('events')
            ->join('locations', 'events.location_id', '=', 'locations.id')
            ->select('events.id', 'events.name as event', 'events.description', 'events.price', 'events.date', 'events.hour', 'locations.name as location', 'locations.city as city')
            ->where('events.name', 'like', '%' . $item . '%')
            ->orWhere('locations.name', 'like', '%' . $item . '%')
            ->orWhere('locations.city', 'like', '%' . $item . '%')
            ->get();

        return $events;
    }

    public static function getEventsByCategory($item)
    {
        $events = DB::table('events')
            ->join('locations', 'events.location_id', '=', 'locations.id')
            ->join('categories', 'events.category_id', '=', 'categories.id')
            ->select('events.id', 'events.name as event', 'events.description', 'events.price', 'events.date', 'events.hour', 'locations.name as location', 'locations.city as city')
            ->where('categories.name', 'like', '%' . $item . '%')
            ->orderBy('events.date')
            ->get();

        return $events;
    }

    public static function getEventsById($item)
    {
        $events = DB::table('events')
            ->select('events.id as event_id', 'events.name', 'events.description', 'sessions.id as session_id', 'sessions.date', 'sessions.hour', 'ticket_types.id as ticket_type_id', 'ticket_types.session_id', 'ticket_types.price','ticket_types.description as ticket_types_description', 'locations.name as location_name', 'locations.capacity', 'locations.province', 'locations.city', 'locations.street', 'locations.number', 'locations.cp')
            ->join('sessions', 'events.id', '=', 'sessions.event_id')
            ->join('ticket_types', 'sessions.id', '=', 'ticket_types.session_id')
            ->join('locations', 'events.location_id', '=', 'locations.id')
            ->where('events.id', '=', $item)
            ->get();

        return $events;
    }

    public static function getEventsByUserId($item)
    {

        $events = DB::table('events')
            ->select('id', 'name', 'description')
            ->where('user_id', $item)
            ->get();

        return $events;
    }
}
