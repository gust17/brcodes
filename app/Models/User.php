<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'role',
        'pontuacao',
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

    /**
     * Define se o usuário é um competidor.
     *
     * @return bool
     */
    public function isCompetidor()
    {
        return $this->role === 'competidor';
    }

    /**
     * Define se o usuário é um patrocinador.
     *
     * @return bool
     */
    public function isPatrocinador()
    {
        return $this->role === 'patrocinador';
    }

    /**
     * Define se o usuário é um administrador.
     *
     * @return bool
     */
    public function isAdministrador()
    {
        return $this->role === 'administrador';
    }
}
