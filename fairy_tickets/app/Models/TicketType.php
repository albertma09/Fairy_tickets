<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'price', 'ticket_amount'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
