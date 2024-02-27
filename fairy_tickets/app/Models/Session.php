<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use App\Libraries\Utils;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function token(): HasOne
    {
        return $this->hasOne(Token::class);
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

    // Función que recibe el id de un evento y devuelve un array con los datos de la primera sesión
    // y sus tipos de ticket asociados
    public static function getFirstSessionDataByEvent(string $eventId): array
    {
        try {
            Log::info('Llamada al método Session.getFirstSessionDataByEvent');
            $firstSession = Self::getFirstSessionByEvent($eventId);
            $ticketTypes = $firstSession->ticketTypes;
            $sessionData = ['session' => $firstSession, 'tickettypes' => $ticketTypes];
            Log::debug('Fin del método que recupera todos los datos de la primera sesión de un evento y sus tipos de ticket');
            return $sessionData;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que recibe el tipo de cierre escogido por el usuario y la fecha del evento y
    // devuelve la fecha del cierre según lo que se pida
    // Ajusta el cierre de venta en línea según las especificaciones
    public static function adjustOnlineClosure(Carbon $eventDate, string $onlineClosure, Carbon $customClosure = null): Carbon
    {
        try {
            Log::info("Llamada al método Session.adjustOnlineClosure");
            // Si es personalizada, mantenemos la fecha personalizada
            if ($onlineClosure === 'custom') {
                return $customClosure ?? throw new \InvalidArgumentException('Custom closure not provided.');
            }

            // Si no es personalizada sustraemos el numero que viene a la fecha del evento, solo debería poder ser 0, 1 o 2
            $hoursToSubtract = intval($onlineClosure);
            return $eventDate->subHours($hoursToSubtract);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que reorganiza los datos para crear la sesión
    public static function createSessionData(int $eventId, array $formData, Carbon $onlineClosure): array
    {
        try {
            Log::info("Llamada al método createSessionData");
            $sessionDate = $formData['session_date'];
            $sessionTime = $formData['session_hours'] . ':' . $formData['session_minutes'];

            $nominalTickets = (bool) ($formData['nominal_tickets'] ?? false);

            return [
                'event_id' => $eventId,
                'code' => Str::random(15),
                'date' => $sessionDate,
                'hour' => $sessionTime,
                'session_capacity' => $formData['session_capacity'],
                'online_sale_closure' => $onlineClosure->toDateTimeString(),
                'nominal_tickets' => $nominalTickets,
            ];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // Función que hace el insert a base de datos de una sesión,
    // recibe el id del evento al que pertenece y los datos de sesión en forma de array asociativo
    public static function createSession(int $eventId, array $formData)
    {
        try {
            log::info('Llamada al método Session.createSession');

            // Parsea la fecha y la hora de la sesión
            $carbonDatetime = Utils::parseDateTime(
                $formData['session_date'],
                $formData['session_hours'],
                $formData['session_minutes']
            );

            // Parsea la fecha y la hora de cierre de venta personalizadas,
            // comprobamos si vienen datos en el array y si no entendemos que no hay hora de cierre personalizada
            $customSaleClosure = isset($formData['custom_closure_date']) && isset($formData['custom_closure_hours']) && isset($formData['custom_closure_minutes'])
                ? self::parseDateTime(
                    $formData['custom_closure_date'],
                    $formData['custom_closure_hours'],
                    $formData['custom_closure_minutes']
                )
                : null;

            // Ajusta el cierre de venta en línea
            $onlineClosure = self::adjustOnlineClosure(
                $carbonDatetime,
                $formData['online_sale_closure'],
                $customSaleClosure
            );

            // Crea los datos de la sesión
            $sessionData = self::createSessionData($eventId, $formData, $onlineClosure);

            // Crea la sesión y los tickets asociados
            $session = Session::create($sessionData);
            $sessionId = $session->id;
            TicketType::createSeveralTickets($sessionId, $formData);

            return $sessionId;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public static function getSessionByTicketTypeID($ticketTypeID)
    {

        try {
            Log::info("Llamada al metodo Session.getSessionByTicketTypeID");
            $sessionData = DB::table('sessions')
                ->join('ticket_types', 'sessions.id', '=', 'ticket_types.session_id')
                ->join('events', 'events.id', '=', 'sessions.event_id')
                ->select('sessions.id', 'events.name', 'sessions.date', 'sessions.hour', 'sessions.nominal_tickets')
                ->where('ticket_types.id', '=', $ticketTypeID)
                ->get();
            return $sessionData;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function getSessionByCode($code)
    {
        try {
            Log::info("Llamada al metodo Session.getSessionByCode");
            $session = DB::table('sessions')
                ->select('code', 'id')
                ->where('code', $code)
                ->first();

            return $session;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
