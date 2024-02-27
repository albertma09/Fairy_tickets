<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'name', 'dni', 'phone_number', 'email'];
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function opinion(): HasOne
    {
        return $this->hasOne(Opinion::class);
    }


    public static function getRememberTickets()
    {
        try {

            $event = DB::table('purchases')
                ->join('sessions', 'sessions.id', '=', 'purchases.session_id')
                ->join('events', 'events.id', '=', 'sessions.event_id')
                ->select('events.id', 'purchases.email', 'events.name as event_name', 'sessions.id as session_id')
                ->where('sessions.date', '=', DB::raw('1 + CURRENT_DATE'))
                ->distinct()
                ->get();

            return $event;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    
    public static function sendOpinion()
    {
        try {
            $event = DB::table('purchases')
                ->join('sessions', 'sessions.id', '=', 'purchases.session_id')
                ->join('events', 'events.id', '=', 'sessions.event_id')
                ->select('purchases.id', 'purchases.name', 'purchases.email', 'events.id as event_id', 'events.name as event_name')
                ->where('sessions.date', '=', DB::raw('CURRENT_DATE - 1'))
                ->distinct()
                ->get();

            return $event;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }

    
    public static function createPurchase(array $ownerData)
    {
        try {
            Log::info("Llamada al método purchase.createPurchase");
            
            $purchaseData = $ownerData;
            $purchase = Purchase::create($purchaseData);
            $purchaseId = $purchase->id;
            $sessionIdOwner = $purchase->session_id;
            $emailOwner = $purchase->email;

            $dataOwnerInserted = [
                "purchase_id" => $purchaseId,
                "session_id" => $sessionIdOwner,
                "email" => $emailOwner
            ];
            return($dataOwnerInserted);
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
