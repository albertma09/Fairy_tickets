<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Session;
use App\Models\Location;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventController extends Controller
{
    public function showCreateForm()
    {
        try {

            Log::info('Llamada al método EventController.showCreateForm');

            $userLocations = Location::getLocationsByUser();
            $categories = Category::getCategories();
            return view('events.create', ['locations' => $userLocations, 'categories' => $categories]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
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
        try {
            Log::info('Llamada al método EventController.mostrarEvento', ['id_evento' => $id]);
            $result = Event::getEventsById($id);

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
                    'ticket_types_description' => $row->ticket_types_description,
                ];
            }

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
                    'min_price' => $minPrice,
                ];
            }


            return view('events.mostrar', ['id' => $id, 'evento' => $events, 'sessionPrices' => $sessionPrices, 'tickets' => $tickets]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función
    public static function checkSessionCapTicketAmount(int $sessionMaxCap, array $ticketAmounts): bool
    {
        try {
            Log::info('Llamada al método EventController.checkSessionCapTicketAmount', ['session_max_cap' => $sessionMaxCap, 'ticket_amounts' => $ticketAmounts]);
            $totalTickets = 0;
            foreach ($ticketAmounts as $amount) {
                $totalTickets += $amount;
                Log::debug($amount);
            }
            return ($sessionMaxCap >= $totalTickets);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Validación de la información del formulario
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'category_id' => 'required|integer',
                'location_id' => 'required|integer',
                'user_id' => 'required|integer',
                //  'addressId' => 'sometimes',
                // 'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                'sessionDatetime' => 'required|date',
                'sessionMaxCapacity' => 'required|integer',
                //   'onlineSaleClosure' => 'required|in:0,1,2,custom',
                //   'customSaleClosure' => 'nullable|required_if:onlineSaleClosure,custom|date',
                'hidden_event' => 'sometimes|nullable|accepted',
                'named_tickets' => 'sometimes|nullable|accepted',
            ]);
            // Separamos los datos de los eventos y de las sesiones
            $eventData = $validatedData;
            unset($eventData['sessionDatetime']);
            unset($eventData['sessionMaxCapacity']);
            unset($eventData['onlineSaleClosure']);
            unset($eventData['customSaleClosure']);

            // Crea el evento y guarda la id
            $event = Event::create($eventData);
            $eventId = $event->id;

            // Datos de la sesión
            $sessionDatetime = $validatedData['sessionDatetime'];
            $carbonDatetime = Carbon::parse($sessionDatetime);

            $sessionData = [
                'event_id' => $eventId,
                'date' => $carbonDatetime->toDateString(),
                'hour' => $carbonDatetime->toTimeString(),
                'session_capacity' => $validatedData['sessionMaxCapacity']
            ];
            // Crea la sesión y guarda la id
            $session = Session::create($sessionData);
            $sessionId = $session->id;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('event_images', 'public');
                $event->image = $imagePath;
                $event->save();
            }

            return redirect()->route('events.create')->with('success', 'El evento ha sido guardado de forma satisfactoria.');
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            dd($e->getMessage());
        }
    }
}
