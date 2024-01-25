<?php

namespace App\Models;

use Exception;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity', 'province', 'city', 'street', 'number', 'cp'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public static function getLocationById($id)
    {
        try {
            Log::info('Llamada al método Location.getLocationById');

            $location = Location::select('locations.id', 'locations.name', 'locations.capacity', 'locations.province', 'locations.city', 'locations.street', 'locations.number', 'locations.cp')
                ->where('locations.id', '=', $id)
                ->get();
            return $location;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public static function getLocationsByUser()
    {
        try {
            Log::info('Llamada al método Location.getLocationsByUser');

            $userId = auth()->user()->id;
            $locations = Location::select('locations.id', 'locations.name', 'locations.capacity')
                ->leftJoin('events', 'locations.id', '=', 'events.location_id')
                ->where('events.user_id', '=', $userId)
                ->distinct() // Use distinct to get unique rows
                ->get();

            return $locations;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
