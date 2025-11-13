<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_organizador',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inscripcion_limite',
        'categoria',
        'precio_inscripcion',
        'cantidad_max_participantes',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_inscripcion_limite' => 'date',
        'precio_inscripcion' => 'decimal:2',
    ];

    // Relación con el organizador (admin)
    public function organizador()
    {
        return $this->belongsTo(User::class, 'id_organizador');
    }

    // Relación con participantes
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'participantes_torneo', 'id_torneo', 'user_id')
                    ->withPivot('estado', 'created_at')
                    ->withTimestamps();
    }

    // Scope para torneos abiertos
    public function scopeAbiertos($query)
    {
        return $query->where('estado', 'inscripciones_abiertas');
    }

    // Scope para torneos en curso
    public function scopeEnCurso($query)
    {
        return $query->where('estado', 'en_curso');
    }
}
