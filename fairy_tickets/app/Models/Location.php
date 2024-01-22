<?php

namespace App\Models;

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
    
    public function users(): BelongsToMany{
        return $this->belongsToMany(User::class);
    }
    
    public static function getLocationsByUser()
    {
        $userId = auth()->user()->id;
        $locations = Location::select('locations.id', 'locations.capacity')
            ->leftJoin('events', 'locations.id', '=', 'events.location_id')
            ->where('events.user_id', '=', $userId)
            ->distinct() // Use distinct to get unique rows
            ->get();

        dd($locations);
        return $locations;
    }
}
