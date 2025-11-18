<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property int $numero
 * @property string $tipo
 * @property string $precio_hora
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reserva> $reservas
 * @property-read int|null $reservas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha wherePrecioHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cancha whereUpdatedAt($value)
 */
	class Cancha extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $profesor_id
 * @property string $dia_semana
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property numeric $multiplicador_precio
 * @property bool $es_horario_prime
 * @property bool $disponible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $dia_formateado
 * @property-read mixed $horario_formateado
 * @property-read \App\Models\Profesor $profesor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservaClase> $reservasClase
 * @property-read int|null $reservas_clase_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor diaSemana($dia)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor disponibles()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereDiaSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereDisponible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereEsHorarioPrime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereMultiplicadorPrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereProfesorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HorarioProfesor whereUpdatedAt($value)
 */
	class HorarioProfesor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $contenido
 * @property string $tipo
 * @property int $leida
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereContenido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereLeida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notificacion whereUserId($value)
 */
	class Notificacion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $concepto
 * @property string|null $id_referencia
 * @property numeric $monto
 * @property numeric $descuento_aplicado
 * @property string $metodo_pago
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $monto_original
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereConcepto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereDescuentoAplicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereIdReferencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereMetodoPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereUserId($value)
 */
	class Pago extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $especialidad
 * @property string|null $biografia
 * @property string|null $foto
 * @property numeric $tarifa_hora
 * @property numeric $tarifa_base_30min
 * @property int $experiencia_anios
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $especialidad_formateada
 * @property-read mixed $nombre_completo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HorarioProfesor> $horarios
 * @property-read int|null $horarios_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReservaClase> $reservasClase
 * @property-read int|null $reservas_clase_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor activos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor especialidad($especialidad)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereBiografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereExperienciaAnios($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereTarifaBase30min($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereTarifaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profesor whereUserId($value)
 */
	class Profesor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $id_cancha
 * @property \Illuminate\Support\Carbon $fecha
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property string $estado
 * @property int|null $numero_jugadores
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cancha $cancha
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereIdCancha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereNumeroJugadores($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reserva whereUserId($value)
 */
	class Reserva extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $profesor_id
 * @property int $horario_id
 * @property \Illuminate\Support\Carbon $fecha_clase
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property int $duracion_minutos
 * @property string $nivel
 * @property numeric $precio
 * @property string $estado
 * @property string|null $notas
 * @property \Illuminate\Support\Carbon|null $confirmada_at
 * @property \Illuminate\Support\Carbon|null $cancelada_at
 * @property string|null $motivo_cancelacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $estado_badge_class
 * @property-read mixed $estado_formateado
 * @property-read mixed $nivel_formateado
 * @property-read \App\Models\HorarioProfesor $horario
 * @property-read \App\Models\Profesor $profesor
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase activas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase confirmadas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase pendientes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase usuario($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereCanceladaAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereConfirmadaAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereDuracionMinutos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereFechaClase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereHorarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereMotivoCancelacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereNotas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereProfesorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservaClase whereUserId($value)
 */
	class ReservaClase extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $estado
 * @property string|null $payment_id
 * @property numeric $monto
 * @property \Illuminate\Support\Carbon|null $fecha_aprobacion
 * @property int|null $aprobada_por
 * @property string|null $notas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $aprobadoPor
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia aprobadas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia pendientes()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia rechazadas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereAprobadaPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereFechaAprobacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereNotas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SolicitudMembresia whereUserId($value)
 */
	class SolicitudMembresia extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $id_organizador
 * @property \Illuminate\Support\Carbon $fecha_inicio
 * @property \Illuminate\Support\Carbon $fecha_fin
 * @property \Illuminate\Support\Carbon $fecha_inscripcion_limite
 * @property string $categoria
 * @property numeric|null $precio_inscripcion
 * @property int|null $cantidad_max_participantes
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $organizador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $participantes
 * @property-read int|null $participantes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo abiertos()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo enCurso()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereCantidadMaxParticipantes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereFechaInscripcionLimite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereIdOrganizador($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo wherePrecioInscripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Torneo whereUpdatedAt($value)
 */
	class Torneo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pago> $pagos
 * @property-read int|null $pagos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Reserva> $reservas
 * @property-read int|null $reservas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SolicitudMembresia> $solicitudesMembresia
 * @property-read int|null $solicitudes_membresia_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Torneo> $torneosInscritos
 * @property-read int|null $torneos_inscritos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Torneo> $torneosOrganizados
 * @property-read int|null $torneos_organizados_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

