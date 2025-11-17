<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioProfesor extends Model
{
    use HasFactory;

    protected $table = 'horarios_profesor';

    protected $fillable = [
        'profesor_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'multiplicador_precio',
        'es_horario_prime',
        'disponible',
    ];

    protected $casts = [
        'multiplicador_precio' => 'decimal:2',
        'es_horario_prime' => 'boolean',
        'disponible' => 'boolean',
    ];

    /**
     * Relación con el profesor
     */
    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    /**
     * Relación con reservas de clases
     */
    public function reservasClase()
    {
        return $this->hasMany(ReservaClase::class, 'horario_id');
    }

    /**
     * Scope para horarios disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    /**
     * Scope para filtrar por día de la semana
     */
    public function scopeDiaSemana($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    /**
     * Obtener día formateado
     */
    public function getDiaFormateadoAttribute()
    {
        $dias = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo',
        ];

        return $dias[$this->dia_semana] ?? ucfirst($this->dia_semana);
    }

    /**
     * Verificar si está reservado en una fecha específica
     */
    public function estaReservadoEn($fecha)
    {
        return $this->reservasClase()
            ->where('fecha_clase', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada', 'en_curso'])
            ->exists();
    }

    /**
     * Obtener horario formateado
     */
    public function getHorarioFormateadoAttribute()
    {
        return \Carbon\Carbon::parse($this->hora_inicio)->format('H:i') . ' - ' . 
               \Carbon\Carbon::parse($this->hora_fin)->format('H:i');
    }
}