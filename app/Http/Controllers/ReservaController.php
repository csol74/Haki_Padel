<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function store(Request $request)
    {
        // Validar datos del formulario
        $validated = $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'horario' => 'required|string',
            'jugadores' => 'required|integer|min:2|max:4',
        ]);

        // Separar el rango horario "08:00-10:00"
        [$horaInicio, $horaFin] = explode('-', $validated['horario']);

        // Verificar que no exista una reserva para la misma cancha en ese horario
        $yaReservada = Reserva::where('id_cancha', $validated['cancha_id'])
            ->where('fecha', $validated['fecha'])
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                  ->orWhereBetween('hora_fin', [$horaInicio, $horaFin]);
            })
            ->exists();

        if ($yaReservada) {
            return back()->with('error', 'Este horario ya está reservado. Intenta con otro.');
        }

        // Crear la reserva
        Reserva::create([
            'user_id' => Auth::id(),
            'id_cancha' => $validated['cancha_id'],
            'fecha' => $validated['fecha'],
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'numero_jugadores' => $validated['jugadores'],
            'estado' => 'pendiente',
        ]);

        return redirect()->route('canchas.index')->with('success', '¡Reserva creada exitosamente!');
    }
}
