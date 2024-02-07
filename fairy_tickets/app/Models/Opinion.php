<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Opinion extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'name', 'star_rating', 'face_rating', 'title', 'coment'];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }


}
