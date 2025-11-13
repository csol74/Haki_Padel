<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\Scheduling\Schedule;

class ReservaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'horario' => 'required|string',
            'jugadores' => 'required|integer|min:2|max:4',
        ]);

        [$horaInicio, $horaFin] = explode('-', $validated['horario']);

        $yaReservada = Reserva::where('id_cancha', $validated['cancha_id'])
            ->where('fecha', $validated['fecha'])
            ->where('hora_inicio', $horaInicio)
            ->exists();

        if ($yaReservada) {
            return back()->with('error', 'Este horario ya está reservado. Intenta con otro.');
        }

        // Crear la reserva con estado pendiente
        $reserva = Reserva::create([
            'user_id' => Auth::id(),
            'id_cancha' => $validated['cancha_id'],
            'fecha' => $validated['fecha'],
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'numero_jugadores' => $validated['jugadores'],
            'estado' => 'pendiente',
        ]);

        // Crear notificación de reserva pendiente
        Notificacion::create([
            'user_id' => Auth::id(),
            'titulo' => 'Reserva pendiente de pago',
            'contenido' => "Tu reserva para el {$validated['fecha']} de {$horaInicio} a {$horaFin} está pendiente de pago. Tienes 5 minutos para completarla.",
            'tipo' => 'reserva',
            'leida' => false,
        ]);

        // Redirigir a una vista de confirmación de pago
        return redirect()->route('reservas.pago', ['id' => $reserva->id]);
    }

    // Nueva función para mostrar la pantalla de pago
    public function pago($id)
    {
        $reserva = Reserva::findOrFail($id);
        return view('reservas.pago', compact('reserva'));
    }

    // Simulación del proceso de pago
    public function completarPago($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = 'completada';
        $reserva->save();

        //Crear notificación de reserva completada
        Notificacion::create([
            'user_id' => $reserva->user_id,
            'titulo' => 'Reserva completada',
            'contenido' => "Tu pago fue exitoso. Tu reserva para el {$reserva->fecha} de {$reserva->hora_inicio} a {$reserva->hora_fin} está confirmada.",
            'tipo' => 'reserva',
            'leida' => false,
        ]);

        return redirect()->route('profile.show')
            ->with('success', '¡Pago completado! Tu reserva está confirmada.');
    }

    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);

        //cancelar reserva
        if ($reserva->user_id !== Auth::id()) {
            return redirect()->route('profile.show')->with('error', 'No tienes permiso para cancelar esta reserva.');
        }

        $reserva->delete();

        return redirect()->route('profile.show')->with('success', 'Tu reserva ha sido cancelada correctamente.');
    }

    /**
     * Limpiar reservas expiradas (mayores a 5 minutos sin pagar)
     */
    public static function limpiarReservasExpiradas()
    {
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
    }
}
