<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Exception;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public static function checkEventBelongsUser($eventId): bool
    {
        try {
            Log::info("Llamada al metodo User.checkEventBelongsUser");
            $event = Event::findOrFail($eventId);
            $userId = Auth::user()->id;

            // Primero, comprobamos si el evento pertenece al usuario
            if ($event->user_id !== $userId) {
                // Devolvemos falso si no es del usuario
                return false;
            }
            // Devolvemos True si el evento es del usuario
            return true;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
        }
    }
}
