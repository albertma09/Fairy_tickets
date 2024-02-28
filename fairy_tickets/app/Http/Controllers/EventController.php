<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Event;
use App\Models\Opinion;
use App\Libraries\Utils;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;

class EventController extends Controller
{
    public function showCreateForm()
    {
        $userLocations = Location::getLocationsByUser();
        $categories = Category::getCategories();
        return view('events.create', ['locations' => $userLocations, 'categories' => $categories]);
    }

    public function showUpdateForm($id)
    {


        $events = Event::getEventsById($id);
        $event = $events[0];

        $categories = Category::getCategories();
        $userLocations = Location::getLocationsByUser();
        return view('events.create', ['locations' => $userLocations, 'categories' => $categories, 'event' => $event]);
    }



    public function searchBySearchingItem(Request $request): View
    {
        try {
            Log::info("Llamada al método EventController.searchBySearchingItem");

            $item = $request->input('search-input');
            $events = Event::getEventsBySearching($item);
            return view('search.index', ['events' => $events]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }



    public function searchByCategoryItem(string $name): View
    {
        try {
            Log::info("Llamada al método EventController.searchByCategoryItem");

            $item = $name;

            $events = Event::getEventsByCategory($item);

            return view('search.index', ['events' => $events]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }


    public function mostrarEvento($id)
    {
        $result = Event::getEventsById($id);

        $events = [];
        $sessions = [];
        $tickets = [];
        $opinions = Opinion::getOpinionsByEvent($id);

        foreach ($result as $row) {
            // Agregar datos de eventos
            $events[$row->event_id] = [
                'id' => $row->event_id,
                'name' => $row->name,
                'description' => $row->description,
                'image' => $row->image,
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
                'nominal_tickets' => $row->nominal_tickets
            ];

            // Agregar datos de tickets
            $tickets[$row->ticket_type_id] = [
                'id' => $row->ticket_type_id,
                'session_id' => $row->session_id,
                'event_id' => $row->event_id,
                'price' => $row->price,
                'ticket_types_description' => $row->ticket_types_description,
                'ticket_amount' => $row->ticket_amount,
            ];
        }

        usort($tickets, function ($a, $b) {
            return $a['price'] - $b['price'];
        });

        $sessionPrices = [];

        foreach ($sessions as $sessionId => $session) {
            // Encuentra los tickets asociados a esta sesión
            $sessionTickets = array_filter($tickets, function ($ticket) use ($sessionId) {
                return $ticket['session_id'] == $sessionId;
            });

            $minPrice = min(array_column($sessionTickets, 'price'));

            $sessionPrices[$sessionId] = [
                'id' => $session['id'],
                'date' => $session['date'],
                'hour' => $session['hour'],
                'nominal_tickets' => $session['nominal_tickets'],
                'min_price' => $minPrice,

            ];
        }


        return view('events.mostrar', ['id' => $id, 'evento' => $events, 'sessionPrices' => $sessionPrices, 'tickets' => $tickets, 'opinions' => $opinions,]);
    }



    public function store(Request $request)
    {
        try {
            log::info('Llamada al método EventController.store');

            // Primero comprobamos que los precios lleguen bien
            Utils::sanitizePriceInput($request);

            // Validación de la información del formulario
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'category_id' => 'required|integer',
                'location_id' => 'required|integer',
                'user_id' => 'required|integer',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'session_date' => 'required|date|after:today',
                'session_hours' => 'required|integer|min:0|max:23',
                'session_minutes' => 'required|integer|min:0|max:59',
                'session_capacity' => 'required|integer',
                'online_sale_closure' => 'required|in:0,1,2,custom',
                'custom_closure_date' => 'nullable|required_if:onlineSaleClosure,custom|date|after:session_date',
                'custom_closure_hours' => 'nullable|required_if:onlineSaleClosure,custom|integer|min:0|max:23',
                'custom_closure_minutes' => 'nullable|required_if:onlineSaleClosure,custom|integer|min:0|max:59',
                'named_tickets' => 'sometimes|nullable|accepted',
                'ticket_description.*' => 'required|string|max:100',
                'ticket_quantity.*' => 'nullable|integer|min:0',
                'price.*' => 'required|numeric|min:0',
            ]);

            // Miramos si la cantidad de tickets total es válida y si hay imagen en la petición
            if (Utils::checkSessionCapTicketAmount($validatedData['session_capacity'], $validatedData['ticket_quantity']) && $request->file('image')) {
                // Se guarda en base de datos el evento, la imagen principal, la primera sesión y los tickets
                Event::createEvent($validatedData, $request->file('image'));
                return redirect()->route('promotor', ['userId' => auth()->user()->id])->with('success', 'El evento ha sido guardado de forma satisfactoria.');
            } else {
                throw new Exception('La cantidad de tickets total supera el máximo establecido en la sesión.');
            }
        } catch (ValidationException $e) {
            return redirect()->route('events.create')
                ->withErrors($e->validator->getMessageBag())
                ->withInput();
        } catch (FileNotFoundException $e) {
            Log::error($e->getMessage());
            return redirect()->route('events.create')
                ->with('errors', 'No es un archivo de imagen válido o está vacío.')
                ->withInput();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('events.create')
                ->with('errors', 'Ocurrió un error al guardar el evento.')
                ->withInput();
        }
    }

    public function edit(Request $request)
    {

       

        $validatedData = $request->validate([
            'event_id' => 'required',
            'name' => 'required|max:255',
            'category_id' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'location_id' => 'required|integer',
            'user_id' => 'required|integer',
            'description' => 'required|string',
        ]);

        


        Event::updateEvent($validatedData);

        return redirect()->route('promotor', ['userId' => auth()->user()->id])->with('success', 'El evento ha sido actualizado de forma satisfactoria.');
    }
}
