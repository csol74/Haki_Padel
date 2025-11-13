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
 * @property string $monto
 * @property string $metodo_pago
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereConcepto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pago whereCreatedAt($value)
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
 * @property int $id_cancha
 * @property string $fecha
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property string $estado
 * @property int|null $numero_jugadores
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cancha $cancha
 * @property-read \App\Models\User $user
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
 * @property string $name
 * @property string $email
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

