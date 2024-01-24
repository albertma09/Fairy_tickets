<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'hour', 'session_capacity'];

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
            Log::info('Llamada al método Session.getAllSessionsByPromotor');
            
            $sessions = DB::table('sessions')
            ->join('events', 'events.id', '=', 'sessions.event_id')
            ->select('events.name', 'sessions.date', 'sessions.hour')
            ->where('events.user_id','=', $id)
            ->orderBy('sessions.date')
            ->get();
            Log::info('fin de la carga de sesiones por promotor');
        return $sessions;
        }catch(Exception $e){
            Log::debug($e->getMessage());
        }
        
    }
}
