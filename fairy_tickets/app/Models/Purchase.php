<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
