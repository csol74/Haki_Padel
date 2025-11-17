<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    protected $fillable = [
        'user_id',
        'especialidad',
        'biografia',
        'foto',
        'tarifa_hora',
        'tarifa_base_30min',
        'experiencia_anios',
        'activo',
    ];

    protected $casts = [
        'tarifa_hora' => 'decimal:2',
        'tarifa_base_30min' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con horarios
     */
    public function horarios()
    {
        return $this->hasMany(HorarioProfesor::class);
    }

    /**
     * Relación con reservas de clases
     */
    public function reservasClase()
    {
        return $this->hasMany(ReservaClase::class);
    }

    /**
     * Scope para profesores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por especialidad
     */
    public function scopeEspecialidad($query, $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }

    /**
     * Obtener nombre completo del profesor
     */
    public function getNombreCompletoAttribute()
    {
        return $this->user->name;
    }

    /**
     * Obtener especialidad formateada
     */
    public function getEspecialidadFormateadaAttribute()
    {
        $especialidades = [
            'competitivo' => 'Competitivo',
            'infantil' => 'Infantil',
            'casual' => 'Casual',
            'acondicionamiento_fisico' => 'Acondicionamiento Físico'
        ];

        return $especialidades[$this->especialidad] ?? ucfirst($this->especialidad);
    }

    /**
     * Calcular precio de una clase
     */
    public function calcularPrecioClase($duracionMinutos, $diaSemanaNombre, $esHorarioPrime = false)
    {
        // Precio base según duración
        $precioBase = $this->tarifa_base_30min * ($duracionMinutos / 30);

        // Multiplicador por día
        $multiplicadorDia = $this->getMultiplicadorDia($diaSemanaNombre);

        // Multiplicador por horario prime
        $multiplicadorPrime = $esHorarioPrime ? 1.3 : 1.0;

        return round($precioBase * $multiplicadorDia * $multiplicadorPrime, 2);
    }

    /**
     * Obtener multiplicador por día
     */
    private function getMultiplicadorDia($diaSemanaNombre)
    {
        $multiplicadores = [
            'lunes' => 1.0,
            'martes' => 1.0,
            'miercoles' => 1.0,
            'jueves' => 1.0,
            'viernes' => 1.0,
            'sabado' => 1.2,  // +20% sábados
            'domingo' => 1.3, // +30% domingos
        ];

        return $multiplicadores[strtolower($diaSemanaNombre)] ?? 1.0;
    }

    /**
     * Verificar si tiene disponibilidad en una fecha y hora
     */
    public function tieneDisponibilidad($fecha, $horaInicio, $duracionMinutos)
    {
        $diaSemanaNombre = strtolower(\Carbon\Carbon::parse($fecha)->locale('es')->dayName);
        
        // Buscar horario que coincida
        $horario = $this->horarios()
            ->where('dia_semana', $diaSemanaNombre)
            ->where('disponible', true)
            ->where('hora_inicio', '<=', $horaInicio)
            ->where('hora_fin', '>=', $horaInicio)
            ->first();

        if (!$horario) {
            return false;
        }

        // Verificar que no haya reserva en ese horario
        $reservaExistente = $this->reservasClase()
            ->where('fecha_clase', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->where(function($query) use ($horaInicio) {
                $query->where('hora_inicio', '<=', $horaInicio)
                      ->where('hora_fin', '>', $horaInicio);
            })
            ->exists();

        return !$reservaExistente;
    }
}