<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'hour'];
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
    public function ticket_types(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }
}
