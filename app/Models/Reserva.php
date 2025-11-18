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
        'precio_base',
        'descuento',
        'precio_final',
        'duracion_horas',
        'estado'
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_base' => 'decimal:2',
        'descuento' => 'decimal:2',
        'precio_final' => 'decimal:2',
        'duracion_horas' => 'decimal:2'
    ];

    // Relación con cancha
    public function cancha()
    {
        return $this->belongsTo(Cancha::class, 'id_cancha');
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
