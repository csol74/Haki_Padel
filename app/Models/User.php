<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Seguridad
     */
    protected $guarded = [
        'role',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============= MÉTODOS DE ROL =============

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCliente()
    {
        return $this->role === 'cliente';
    }

    // ============= RELACIONES =============

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function torneosInscritos()
    {
        return $this->belongsToMany(Torneo::class, 'participantes_torneo', 'user_id', 'id_torneo')
                    ->withPivot('estado', 'created_at')
                    ->withTimestamps();
    }

    public function torneosOrganizados()
    {
        return $this->hasMany(Torneo::class, 'id_organizador');
    }
}
