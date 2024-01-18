<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    // public function index(): View
    // {
    //     $events=Event::getAllEvents();
    //     return view('home.index', ['events'=>$events]);
    // }

    public function searchBySearchingItem(Request $request): View
    {
        try {
            $item = $request->input('search-input');
            //dd($item);  
            $events = Event::getEventsBySearching($item);
            //dd($events);      
            return view('search.index', ['events' => $events]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function searchByCategoryItem(Request $request): View
    {
        try {
            //dd($request);
            $item = $request->input('category-item');
            //dd($item);
            $events = Event::getEventsByCategory($item);
            //dd($events);
            return view('search.index', ['events' => $events]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function mostrarEvento($id)
{
    $result = Event::getEventsById($id); // Asumiendo que estás utilizando Eloquent y que tu modelo se llama "Evento"

    $events = [];
$sessions = [];
$tickets = [];

foreach ($result as $row) {
    // Agregar datos de eventos
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

    // Agregar datos de sesiones
    $sessions[$row->session_id] = [
        'id' => $row->session_id,
        'date' => $row->date,
        'hour' => $row->hour,
    ];

    // Agregar datos de tickets
    $tickets[$row->ticket_type_id] = [
        'id' => $row->ticket_type_id,
        'session_id' => $row->session_id,
        'price' => $row->price,
    ];
}

$sessionPrices = [];

foreach ($sessions as $sessionId => $session) {
    // Encuentra los tickets asociados a esta sesión
    $sessionTickets = array_filter($tickets, function ($ticket) use ($sessionId) {
        return $ticket['session_id'] == $sessionId;
    });

    // Encuentra el precio más bajo de los tickets asociados a esta sesión
    $minPrice = min(array_column($sessionTickets, 'price'));

    // Agrega la fecha, hora y precio más bajo a los datos combinados
    $sessionPrices[$sessionId] = [
        'date' => $session['date'],
        'hour' => $session['hour'],
        'min_price' => $minPrice,
    ];
}

    
    return view('events.mostrar', ['id' => $id,'evento' => $events, 'sessionPrices' => $sessionPrices ]);
}

}
