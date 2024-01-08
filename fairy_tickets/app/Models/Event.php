<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','price','date','hour'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public static function getEventsBySearching($item)
    {
        $events = DB::table('events')
                    ->join('locations', 'events.location_id','=','locations.id')
                    ->select('events.name','events.description','events.price','events.date','events.hour','locations.name as location','locations.city')
                    ->where('events.name','like','%' . $item . '%')
                    ->orWhere('locations.name','like','%' . $item . '%')
                    ->orWhere('locations.city','like','%' . $item . '%')
                    ->get();
                    
        return $events;
    }

    public static function getEventsByCategory($item)
    {
        $events = DB::table('events')
                    ->join('locations', 'events.location_id','=','locations.id')
                    ->join('categories','events.category_id','=','categories.id')
                    ->select('events.name','events.description','events.price','events.date','events.hour','locations.name as location','locations.city')
                    ->where('categories.name','like','%' . $item . '%')
                    ->get();
                    
        return $events;
    }
}
