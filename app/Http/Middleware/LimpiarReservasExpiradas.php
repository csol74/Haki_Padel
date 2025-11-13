<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Notificacion;
use Symfony\Component\HttpFoundation\Response;

class LimpiarReservasExpiradas
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Limpiar reservas pendientes mayores a 5 minutos
        $reservasExpiradas = Reserva::where('estado', 'pendiente')
            ->where('created_at', '<', now()->subMinutes(5))
            ->get();

        foreach ($reservasExpiradas as $reserva) {
            // Crear notificación de expiración
            Notificacion::create([
                'user_id' => $reserva->user_id,
                'titulo' => 'Reserva expirada',
                'contenido' => "Tu reserva para el {$reserva->fecha} de {$reserva->hora_inicio} a {$reserva->hora_fin} ha caducado por falta de pago.",
                'tipo' => 'reserva',
                'leida' => false,
            ]);

            // Eliminar reserva
            $reserva->delete();
        }

        return $next($request);
    }
}
