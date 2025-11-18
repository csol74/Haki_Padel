<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'concepto',
        'id_referencia',
        'monto',
        'descuento_aplicado',
        'metodo_pago',
        'estado'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'descuento_aplicado' => 'decimal:2'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // MÉTODO PARA OBTENER EL MONTO ORIGINAL (SIN DESCUENTO)
    public function getMontoOriginalAttribute()
    {
        return $this->monto + $this->descuento_aplicado;
    }
}
