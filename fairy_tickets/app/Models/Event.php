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

    protected $fillable = ['name', 'description', 'price', 'date', 'hour'];

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
            ->select('events.id', 'events.name', 'events.description', 'events.price', 'events.date', 'events.hour', 'locations.name as location', 'locations.city as city')
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
            ->select('events.id', 'events.name', 'events.description', 'events.price', 'events.date', 'events.hour', 'locations.name as location', 'locations.city as city')
            ->where('categories.name', 'like', '%' . $item . '%')
            ->get();

        return $events;
    }

    public static function getEventsById($item)
    {
        $events = DB::table('events')
            ->join('locations', 'events.location_id', '=', 'locations.id')
            ->select('events.id', 'events.name', 'events.description', 'events.price', 'events.date', 'events.hour', 'locations.name as location', 'locations.city as city')
            ->where('events.id', 'like', $item)
            ->get();

        return $events;
    }
}
