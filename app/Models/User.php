<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // AGREGAR ESTAS RELACIONES
    public function reservas()
    {
        return $this->hasMany(\App\Models\Reserva::class);
    }

    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(\App\Models\Notificacion::class);
    }
}
