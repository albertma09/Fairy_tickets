<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('home.index', ['events' => Event::getAllEvents()]);
    }

    // public function index(): View
    // {
    //     return view('home.index', ['events' => Event::all()]);
    // }

    public function searchBySearchingItem(Request $request): View
    {
        $item = $request->input('search-input');
        //dd($item);  
        $events = Event::getEventsBySearching($item);
        //dd($events);      
        return view('search.index', ['events' => $events]);
    }

    public function searchByCategoryItem(Request $request): View
    {   //dd($request);
        $item = $request->input('category-item');
        //dd($item);
        $events = Event::getEventsByCategory($item);
        //dd($events);
        return view('search.index', ['events' => $events]);
    }

    public function mostrarEvento($id)
    {
        $result = Event::getEventsById($id);

        $events = [];
        $sessions = [];
        $tickets = [];

        foreach ($result as $row) {
            $events[$row->event_id] = [
                'id' => $row->event_id,
                'name' => $row->name,
                'description' => $row->description,
                'location_name' => $row->location_name,
                'capacity' => $row->capacity,
                'province' => $row->province,
                'city' => $row->city,
                'street' => $row->street,
                'number' => $row->number,
                'cp' => $row->cp,
            ];

            $sessions[$row->session_id] = [
                'id' => $row->session_id,
                'date' => $row->date,
                'hour' => $row->hour,
            ];

       
            $tickets[$row->ticket_type_id] = [
                'id' => $row->ticket_type_id,
                'session_id' => $row->session_id,
                'price' => $row->price,
                'description' => $row->description,
            ];
        }

        $sessionPrices = [];

        foreach ($sessions as $sessionId => $session) {
        
            $sessionTickets = array_filter($tickets, function ($ticket) use ($sessionId) {
                return $ticket['session_id'] == $sessionId;
            });

            $minPrice = min(array_column($sessionTickets, 'price'));

            $sessionPrices[$sessionId] = [
                'id' => $session['id'],
                'date' => $session['date'],
                'hour' => $session['hour'],
                'min_price' => $minPrice,
            ];
        }


        return view('events.mostrar', ['id' => $id, 'evento' => $events, 'sessionPrices' => $sessionPrices, 'tickets' => $tickets]);
    }
}
