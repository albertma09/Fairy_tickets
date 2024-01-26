<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['event_id','date', 'hour', 'session_capacity', 'online_sale_closure', 'nominal_tickets'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public static function getAllSessionsByPromotor($id)
    {
        try{
            Log::info('Llamada al método Session.getAllSessionsByPromotor',['Id_promotor' => $id]);
            
            $sessions = DB::table('sessions')
            ->join('events', 'events.id', '=', 'sessions.event_id')
            ->select('events.name', 'sessions.date', 'sessions.hour')
            ->where('events.user_id','=', $id)
            ->orderBy('sessions.date')
            ->get();
            Log::info('fin de la carga de sesiones por promotor');
        return $sessions;
        }catch(Exception $e){
            Log::error($e->getMessage());
        }
        
    }
    public static function createSession(int $eventId, array $formData)
    {
        try {
            log::info('Llamada al método Session.createSession', ['Id_evento'=>$eventId,'datos_formulario'=>$formData]);
            // Parseamos el datetime que nos llega
            $sessionDatetime = $formData['sessionDatetime'];
            $carbonDatetime = Carbon::parse($sessionDatetime);

            // Set default online closure
            $onlineClosure = $carbonDatetime;

            // Adjust online closure based on the onlineSaleClosure value
            switch ($formData['onlineSaleClosure']) {
                case '1':
                    $onlineClosure = $carbonDatetime->clone()->addHour();
                    break;
                case '2':
                    $onlineClosure = $carbonDatetime->clone()->addHours(2);
                    break;
                case 'custom':
                    if ($formData['customSaleClosure']) {
                        $onlineClosure = Carbon::parse($formData['customSaleClosure']);
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

            // Arreglamos el tipo de datos que nos trae nominal_tickets, pasamos a booleano cuando viene vacío
            $nominalTickets = (bool) ($formData['nominal_tickets'] ?? false);


            // Preparamos los datos de la sesión en un array
            $sessionData = [
                'event_id' => $eventId,
                'date' => $carbonDatetime->toDateString(),
                'hour' => $carbonDatetime->toTimeString(),
                'session_capacity' => $formData['sessionMaxCapacity'],
                'online_sale_closure' => $onlineClosure->toDateTimeString(),
                'nominal_tickets' => $nominalTickets,
            ];

            // Creamos la sesión y recibimos el id
            $session = Session::create($sessionData);
            $sessionId = $session->id;

            return $sessionId;
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
