<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_cancha',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'numero_jugadores',
        'estado',
    ];

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con cancha
     */
    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'id_cancha');
    }
}
