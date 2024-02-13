<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Models\Session;
use App\Libraries\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function showCreateForm($eventId)
    {
        try {
            Log::info("Llamada al metodo SessionController.showCreateForm");
            $check = User::checkEventBelongsUser($eventId);
            if (!$check) {
                abort(403); // Devolvemos un acceso no autorizado
            }
            $session = Session::getFirstSessionDataByEvent($eventId);
            $sessionData['session'] = [
                'id' => $session['session']->id,
                'event_id' => $session['session']->event_id,
                'date' => Carbon::createFromFormat('Y-m-d', $session['session']->date)->format('Y-m-d\T'),
                'hour' => $session['session']->hour,
                'session_capacity' => $session['session']->session_capacity,
                'online_sale_closure' => Carbon::createFromFormat('Y-m-d H:i:s', $session['session']->online_sale_closure)->format('Y-m-d\TH:i'),
                'nominal_tickets' => $session['session']->nominal_tickets,
            ];
            // dd($sessionData);
            $sessionData['tickettypes'] = $session['tickettypes'];
            $locationData = $session['session']->event->location;
            return view('sessions.create', ['sessionData' => $sessionData, 'location' => $locationData]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function showSessionsByPromotor($id)
    {
        try {
            Log::info("Llamada al metodo SessionController.getSessionsByPromotor");
            $sessions = Session::getAllSessionsByPromotor($id);

            return view('sessions.mostrar', ['sessions' => $sessions]);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info("Llamada al metodo SessionController.store");
            Utils::sanitizePriceInput($request);

            // Validación de la información del formulario
            $validatedData = $request->validate([
                'event_id' => 'required|numeric',
                'session_date' => 'required|date',
                'session_hours' => 'required|integer|min:0|max:23',
                'session_minutes' => 'required|integer|min:0|max:59',
                'session_capacity' => 'required|integer',
                'online_sale_closure' => 'required|in:0,1,2,custom',
                'custom_closure_date' => 'nullable|required_if:onlineSaleClosure,custom|date',
                'custom_closure_hours' => 'nullable|required_if:onlineSaleClosure,custom|integer|min:0|max:23',
                'custom_closure_minutes' => 'nullable|required_if:onlineSaleClosure,custom|integer|min:0|max:59',
                'named_tickets' => 'sometimes|nullable|accepted',
                'ticketDescription.*' => 'required|string',
                'ticketQuantity.*' => 'nullable|integer|min:0',
                'price.*' => 'required|numeric|min:0',
            ]);


            // Miramos si la cantidad de tickets total es válida
            if (Utils::checkSessionCapTicketAmount($validatedData['session_capacity'], $validatedData['ticketQuantity'])) {
                // Se guarda en base de datos el evento, la primera sesión y los tickets
                Session::createSession($validatedData['event_id'], $validatedData);
                return redirect()->route('sessions.create', [$validatedData['event_id']])->with('success', 'La sesión ha sido añadida de forma satisfactoria.');
            } else {
                throw new Exception('La cantidad de tickets total supera el máximo establecido en la sesión.');
            }


        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('sessions.create', [$request['event_id']])->with('error', $e->getMessage())->withInput();
        }
    }
}
