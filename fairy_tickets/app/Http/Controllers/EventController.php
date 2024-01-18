<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Location;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function showCreateForm()
    {
        return view('events.create');
    }

    public function searchBySearchingItem(Request $request): View
    {
        $item = $request->input('search-input');
        $events = Event::getEventsBySearching($item);
        return view('search.index', ['events' => $events]);
    }

    public function searchByCategoryItem(Request $request): View
    {
        try {

            $item = $request->input('category-item');

            $events = Event::getEventsByCategory($item);

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


        return view('events.mostrar', ['id' => $id, 'evento' => $events, 'sessionPrices' => $sessionPrices]);
    }

    public function store(Request $request)
    {
dd($request);
        // Validación de la información del formulario
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'category' => 'required|in:cine,conferencia,danza,musica,teatro',
            'addressType' => 'required|in:existing,new',
            'address' => $request->input('addressType') == 'existing' ? 'required_if:addressType,existing' : 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required',
            'datetime' => 'required',
            'max_capacity' => 'required_if:addressType,new|integer',
            'hidden_event' => 'boolean',
            'named_tickets' => 'boolean',
        ]);

        if ($validatedData['addressType'] == 'new') {
            // Save the new address to the database (assuming you have an Address model)
            // Adjust this part based on your actual database structure
            $newAddress = Location::create(['address' => $validatedData['address']]);
            $validatedData['address_id'] = $newAddress->id;
        }

        // Store the event in the database
        $event = Event::create($validatedData);

        // Handle image upload if applicable
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('event_images', 'public');
            $event->image = $imagePath;
            $event->save();
        }

        return redirect()->route('events.create')->with('success', 'Event created successfully!');
    }
}
