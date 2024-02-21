<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Token extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'token'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

   
}
