<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
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


    public static function getTokenBySessionId(int $sessionId)
    {


        $existingToken = DB::table('tokens')
            ->where('session_id', '=', $sessionId)
            ->first();

        if (!$existingToken) {
            $token = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 5)), 0, 15);

            Token::create([
                'session_id' => $sessionId,
                'token' => $token,
            ]);

            return $token;
        } else {
            return $existingToken->token;
        }
    }

    public static function verifyToken(string $token, int $sessionId){

        $validate = DB::table('tokens')
            ->select('token')
            ->where('token', '=', $token)
            ->where('session_id', '=', $sessionId)
            ->first();

            return $validate;
    }
}
