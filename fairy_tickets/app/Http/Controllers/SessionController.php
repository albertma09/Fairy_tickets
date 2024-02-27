<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Session;
use App\Libraries\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

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
                'date' => Carbon::createFromFormat('Y-m-d', $session['session']->date)->format('Y-m-d'),
                'hours' => Carbon::createFromFormat('H:i:s', $session['session']->hour)->format('H'),
                'minutes' => Carbon::createFromFormat('H:i:s', $session['session']->hour)->format('i'),
                'session_capacity' => $session['session']->session_capacity,
                'custom_closure_date' => Carbon::createFromFormat('Y-m-d H:i:s', $session['session']->online_sale_closure)->format('Y-m-d'),
                'custom_closure_hours' => Carbon::createFromFormat('Y-m-d H:i:s', $session['session']->online_sale_closure)->format('H'),
                'custom_closure_minutes' => Carbon::createFromFormat('Y-m-d H:i:s', $session['session']->online_sale_closure)->format('i'),
                'nominal_tickets' => $session['session']->nominal_tickets,
            ];
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
            // dd($sessions);
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
                'session_date' => 'required|date|after:today',
                'session_hours' => 'required|nullable|numeric|min:0|max:23',
                'session_minutes' => 'required|nullable|numeric|min:0|max:59',
                'session_capacity' => 'required|integer',
                'online_sale_closure' => 'required|in:0,1,2,custom',
                'custom_closure_date' => 'nullable|required_if:onlineSaleClosure,custom|date|after:session_date',
                'custom_closure_hours' => 'nullable|required_if:onlineSaleClosure,custom|integer|min:0|max:23',
                'custom_closure_minutes' => 'nullable|required_if:onlineSaleClosure,custom|integer|min:0|max:59',
                'named_tickets' => 'sometimes|nullable|accepted',
                'ticket_description.*' => 'required|string',
                'ticket_quantity.*' => 'nullable|integer|min:0',
                'price.*' => 'required|numeric|min:0',
            ]);


            // Miramos si la cantidad de tickets total es válida
            if (Utils::checkSessionCapTicketAmount($validatedData['session_capacity'], $validatedData['ticket_quantity'])) {
                // Se guarda en base de datos el evento, la primera sesión y los tickets
                Session::createSession($validatedData['event_id'], $validatedData);
                return redirect()->route('sessions.create', [$validatedData['event_id']])->with('success', 'La sesión ha sido añadida de forma satisfactoria.');
            } else {
                throw new Exception('La cantidad de tickets total supera el máximo establecido en la sesión.');
            }
        } catch (ValidationException $e) {
            return redirect()->route('sessions.create', [$request['event_id']])
                ->withErrors($e->validator->getMessageBag())
                ->withInput();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('sessions.create', [$request['event_id']])
                ->with('error', 'Hubo un problema al procesar la solicitud. Por favor, inténtelo de nuevo.')
                ->withInput();
        }
    }

    public function generateCSV($session_id)
    {
        // Ejecutar la consulta


        $tickets = Ticket::getTicketsBySessionId($session_id);

        // Encabezados del CSV
        $csvFileName = 'tickets.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        // Iniciar el manejador del archivo CSV
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array('Buyer Ticket', 'Assistant Ticket', 'Code', 'Description'));

        // Escribir los datos en el archivo CSV
        foreach ($tickets as $ticket) {
            fputcsv($handle, array($ticket->buyer_ticket, $ticket->assistant_ticket, $ticket->code, $ticket->description));
        }

        // Cerrar el manejador del archivo
        fclose($handle);

        // Retornar la respuesta con el archivo CSV
        return Response::make('', 200, $headers);
    }

    public function closeSale(Request $request)
    {


        

        $session_id = $request['session_id'];
        
        $session = Session::findOrFail($session_id);
        
        if (Carbon::parse($session->online_sale_closure)->lte(now())) {
            
            return back()->with('error', 'La fecha de cierre de la venta ya ha pasado.');
        }

        $session->online_sale_closure = now(); 

        $session->save();

        

        return back()->with('message', 'Venta de sesión cerrada exitosamente');
    }
}
