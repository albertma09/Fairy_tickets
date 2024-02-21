<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'date', 'hour', 'session_capacity', 'online_sale_closure', 'nominal_tickets', 'code'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    // Función que pasa por parámetro la id de un usuario y
    // devuelve todas las sesiones de los eventos que este ha creado
    public static function getAllSessionsByPromotor(string $id)
    {
        try {
            Log::info('Llamada al método Session.getAllSessionsByPromotor');

            $sessions = DB::table('sessions')
                ->join('events', 'events.id', '=', 'sessions.event_id')
                ->select('events.name', 'sessions.date', 'sessions.hour', 'sessions.id')
                ->where('events.user_id', '=', $id)
                ->orderBy('sessions.date')
                ->get();
            Log::info('fin de la carga de sesiones por promotor');
            return $sessions;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que pasa por parámetro la id de un evento y
    // devuelve la primera sesión creada (la sesión por defecto)
    public static function getFirstSessionByEvent(string $eventId): Session
    {
        try {
            Log::info('Llamada al método Session.getFirstSessionByEvent');
            // Recupera los datos de la primera sesión de este evento
            $session = Session::where('event_id', $eventId)
                ->orderBy('id')
                ->first();
            Log::info('Fin del get a la primera sesión del evento');
            return $session;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public static function getFirstSessionDataByEvent(string $eventId): array
    {
        try {
            Log::info('Llamada al método Session.getFirstSessionDataByEvent');
            $firstSession = Self::getFirstSessionByEvent($eventId);
            $ticketTypes = $firstSession->ticketTypes;
            $sessionData = ['session' => $firstSession, 'tickettypes' => $ticketTypes];
            Log::info('Fin del método que recupera todos los datos de la primera sesión de un evento y sus tipos de ticket');
            return $sessionData;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }




    // Función que recibe el tipo de cierre escogido por el usuario y la fecha del evento y
    // devuelve la fecha del cierre según lo que se pida
    private static function adjustOnlineClosure(Carbon $eventDate, string $onlineClosure, string $customClosure = null): Carbon
    {
        try {
            Log::info('Llamada al método Session.adjustOnlineClosure');
            // Set default closure date
            $closureDate = $eventDate;
            // Adjust online closure based on the onlineSaleClosure value
            switch ($onlineClosure) {
                case '1':
                    $closureDate = $eventDate->clone()->subHour();
                    break;
                case '2':
                    $closureDate = $eventDate->clone()->subHours(2);
                    break;
                case 'custom':
                    if ($customClosure) {
                        $closureDate = Carbon::parse($customClosure);
                    } else {
                        throw new \Exception('Error: fecha y hora de cierre de venta no especificado.');
                    }
                    break;
                case '0':
                    // 
                    break;
                default:
                    throw new \Exception('Error: tipo de dato de venta online no válido.');
            }
            return $closureDate;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    // Función que recibe una id de sesión y crea un array de tickets vinculados a ésta
    private static function createSessionTicketsArray(int $sessionId, array $formData): array
    {
        try {
            Log::info('Llamada a Session.createSessionTicketsArray', ['datos_formulario', $formData]);
            // Crear los tipos de ticket relacionados con la sesión
            $ticketData = [];
            for ($i = 0; $i < count($formData['ticketDescription']); $i++) {
                $ticketData[] = [
                    'session_id' => $sessionId,
                    'description' => $formData['ticketDescription'][$i],
                    'price' => $formData['price'][$i],
                    // Cuando la cantidad de tickets no se indican o viene 0 toma la capacidad máxima de sesión
                    'ticket_amount' => ($formData['ticketQuantity'][$i] !== null && $formData['ticketQuantity'][$i] !== 0) ? $formData['ticketQuantity'][$i] : $formData['sessionMaxCapacity'],
                ];
                Log::debug('Datos que crea la función Session.createSessionTicketArray', $ticketData, $formData['sessionMaxCapacity']);
            }
            return $ticketData;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que hace el insert a base de datos de una sesión,
    // recibe el id del evento al que pertenece y los datos de sesión en forma de array asociativo
    public static function createSession(int $eventId, array $formData)
    {
        try {
            log::info('Llamada al método Session.createSession');
            // Parseamos la fecha y la hora que nos llegan por separado
            $sessionDate = $formData['session_date'];
            $sessionTime = $formData['session_hours'].$formData['session_minutes'];

            $carbonDatetime = Carbon::parse($sessionDate . ' ' . $sessionTime);

            // Asignamos la fecha de cierre de venta online según los datos del formulario
            $onlineClosure = Self::adjustOnlineClosure($carbonDatetime, $formData['onlineSaleClosure'], $formData['customSaleClosure']);

            // Arreglamos el tipo de datos que nos trae nominal_tickets, pasamos a booleano cuando viene vacío
            $nominalTickets = (bool) ($formData['nominal_tickets'] ?? false);

            // Preparamos los datos de la sesión en un array
            $sessionData = [
                'event_id' => $eventId,
                'code' => Str::random(15),
                'date' => $sessionDate,
                'hour' => $sessionTime,
                'session_capacity' => $formData['sessionMaxCapacity'],
                'online_sale_closure' => $onlineClosure->toDateTimeString(),
                'nominal_tickets' => $nominalTickets,
            ];

            // Creamos la sesión y recibimos el id
            $session = Session::create($sessionData);
            $sessionId = $session->id;

            // Creamos los tickets asociados a la sesión
            $tickets = Self::createSessionTicketsArray($sessionId, $formData);
            foreach ($tickets as $ticket) {
                TicketType::create($ticket);
            }

            return $sessionId;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function getTicketsBySessionId($session_id){


        try{

            $tickets = DB::table('tickets')
            ->select('p.name as buyer_ticket', 'tickets.name as assistant_ticket', 'tickets.id as code', 'tt.description')
            ->join('purchases as p', 'tickets.purchase_id', '=', 'p.id')
            ->join('ticket_types as tt', 'tickets.ticket_type_id', '=', 'tt.id')
            ->where('p.session_id', $session_id)
            ->get();

            return $tickets;

        }catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
