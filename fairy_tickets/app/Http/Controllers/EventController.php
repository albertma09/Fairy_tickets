<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Event;
use App\Models\Image;
use App\Models\Opinion;
use App\Models\Session;
use App\Libraries\Utils;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

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
        try {
            $event = Event::getEventById($id);
            $location = Location::getLocationById($event->id);
            $sessions = Session::getAllSessionsAndTicketsByEvent($event->id);
            dd([$event, $location, $sessions]);
            $tickets = [];
            $opinions = Opinion::getOpinionsByEvent($id);
            foreach ($result as $row) {

                // Agregar datos de sesiones
                $sessions[$row->session_id] = [
                    'id' => $row->session_id,
                    'date' => $row->date,
                    'hour' => $row->hour,
                    'nominal_tickets' => $row->nominal_tickets
                ];

                // Agregar datos de tickets
                $tickets[] = [
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

            $images = Image::getAllImagesByEvent($id);
            if ($images && !empty($images)) {
                $images = Utils::constructImageUrls($images);
            }
            dd($event);
            return view('events.mostrar', ['id' => $id, 'evento' => $event, 'imagenes' => $images, 'sessionPrices' => $sessionPrices, 'tickets' => $tickets, 'opinions' => $opinions]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    public function changeMainImage($eventId, $imageId)
    {
        try {
            Log::info("Llamada al método EventController.changeMainImage con evento: $eventId y imagen $imageId");
            Image::resetMainImage($eventId);
            Image::setMainImage($imageId);
        } catch (\Exception $ex) {
            Log::error("Error al cambiar la imagen principal - " . $ex->getMessage());
        }
    }
}
