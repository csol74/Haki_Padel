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
        'estado',
        'numero_jugadores',
    ];

    // Relaciones
    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'id_cancha');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
