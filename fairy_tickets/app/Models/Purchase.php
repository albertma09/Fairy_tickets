<?php

namespace App\Models;

use Exception;
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

    public static function createPurchase(array $owner)
    {
        try {
            Log::info("Llamada al método purchase.createPurchase");
            
            $purchaseData = $owner;
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
