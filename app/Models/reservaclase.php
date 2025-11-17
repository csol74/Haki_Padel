<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaClase extends Model
{
    use HasFactory;

    protected $table = 'reservas_clase';

    protected $fillable = [
        'user_id',
        'profesor_id',
        'horario_id',
        'fecha_clase',
        'hora_inicio',
        'hora_fin',
        'duracion_minutos',
        'nivel',
        'precio',
        'estado',
        'notas',
        'confirmada_at',
        'cancelada_at',
        'motivo_cancelacion',
    ];

    protected $casts = [
        'fecha_clase' => 'date',
        'precio' => 'decimal:2',
        'confirmada_at' => 'datetime',
        'cancelada_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el profesor
     */
    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    /**
     * Relación con el horario
     */
    public function horario()
    {
        return $this->belongsTo(HorarioProfesor::class, 'horario_id');
    }

    /**
     * Scope para reservas confirmadas
     */
    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    /**
     * Scope para reservas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para reservas activas (no canceladas)
     */
    public function scopeActivas($query)
    {
        return $query->whereNotIn('estado', ['cancelada', 'ausente']);
    }

    /**
     * Scope para reservas de un usuario
     */
    public function scopeUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Obtener estado formateado
     */
    public function getEstadoFormateadoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'confirmada' => 'Confirmada',
            'en_curso' => 'En Curso',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada',
            'ausente' => 'Ausente',
        ];

        return $estados[$this->estado] ?? ucfirst($this->estado);
    }

    /**
     * Obtener badge class según estado
     */
    public function getEstadoBadgeClassAttribute()
    {
        $classes = [
            'pendiente' => 'bg-warning',
            'confirmada' => 'bg-success',
            'en_curso' => 'bg-info',
            'completada' => 'bg-secondary',
            'cancelada' => 'bg-danger',
            'ausente' => 'bg-dark',
        ];

        return $classes[$this->estado] ?? 'bg-secondary';
    }

    /**
     * Obtener nivel formateado
     */
    public function getNivelFormateadoAttribute()
    {
        $niveles = [
            'principiante' => 'Principiante',
            'intermedio' => 'Intermedio',
            'avanzado' => 'Avanzado',
        ];

        return $niveles[$this->nivel] ?? ucfirst($this->nivel);
    }

    /**
     * Verificar si puede ser cancelada
     */
    public function puedeCancelarse()
    {
        return in_array($this->estado, ['pendiente', 'confirmada']) &&
               $this->fecha_clase->isFuture();
    }

    /**
     * Confirmar reserva
     */
    public function confirmar()
    {
        $this->estado = 'confirmada';
        $this->confirmada_at = now();
        $this->save();
    }

    /**
     * Cancelar reserva
     */
    public function cancelar($motivo = null)
    {
        $this->estado = 'cancelada';
        $this->cancelada_at = now();
        $this->motivo_cancelacion = $motivo;
        $this->save();
    }
}