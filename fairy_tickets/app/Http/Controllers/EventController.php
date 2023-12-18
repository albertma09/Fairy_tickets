<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(): View
    {
        return view('home.index', ['events' => Event::all()]);
    }

    public function searchByRandomItem(Request $request): View
    {
        $item = $request->input('search-input');
        $events = DB::table('events')
                    ->join('locations', 'events.location_id','=','locations.id')
                    ->select('events.name','events.description','events.price','events.date','events.hour','locations.name as location','locations.city')
                    ->where('events.name','like','%' . $item . '%')
                    ->orWhere('locations.name','like','%' . $item . '%')
                    ->orWhere('locations.city','like','%' . $item . '%')
                    ->get();
                    
        return view('home.index',['events' => $events]);
    }

}
