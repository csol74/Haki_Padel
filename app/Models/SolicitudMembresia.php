<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudMembresia extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_membresia';

    protected $fillable = [
        'user_id',
        'estado',
        'payment_id',
        'monto',
        'fecha_aprobacion',
        'aprobada_por',
        'notas'
    ];

    protected $casts = [
        'fecha_aprobacion' => 'datetime',
        'monto' => 'decimal:2'
    ];

    // RELACIÓN CON USUARIO 
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //  RELACIÓN CON ADMIN QUE APROBÓ
    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobada_por');
    }

    // SCOPES ÚTILES
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobada');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazada');
    }
}
