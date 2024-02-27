<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Opinion extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'name', 'star_rating', 'face_rating', 'title', 'comment'];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    // Función que recibe la id de un evento por parámetro y 
    // hace el select de todas las opiniones pertenecientes a ese evento
    public static function getOpinionsByEvent(int $eventId)
    {
        try {
            Log::info('Llamada al método Opinion.getOpinionsByEvent');
            $opinions = DB::table('opinions')
                ->select('opinions.name', 'opinions.star_rating', 'opinions.face_rating', 'opinions.title', 'opinions.comment', 'opinions.created_at')
                ->join('purchases', 'opinions.purchase_id', '=', 'purchases.id')
                ->join('sessions', 'purchases.session_id', '=', 'sessions.id')
                ->join('events', 'sessions.event_id', '=', 'events.id')
                ->where('events.id', '=', $eventId)
                ->orderBy('opinions.created_at', 'desc')
                ->get();
            return $opinions;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}
