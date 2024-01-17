<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('home.index', ['events' => Event::getAllEvents()]);
    }

    public function showCreateForm()
    {
        return view('events.create');
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
        $evento = Event::getEventsById($id); // Asumiendo que estás utilizando Eloquent y que tu modelo se llama "Evento"

        return view('events.mostrar', ['evento' => $evento]);
    }

    public function store(Request $request)
    {
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
