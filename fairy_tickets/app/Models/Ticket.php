<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_id', 'ticket_type_id', 'name', 'dni', 'phone_number',];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }
}
