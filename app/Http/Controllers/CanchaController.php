<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Reserva;
use Illuminate\Http\Request;

class CanchaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cancha::query();

        // Filtros opcionales
        if ($request->filled('tipo') && $request->tipo !== 'Todas') {
            $query->where('tipo', $request->tipo);
        }

        $fecha = $request->input('fecha');
        $hora = $request->input('hora');

        // Si no hay búsqueda (fecha u hora), no mostramos nada todavía
        if (!$fecha && !$hora) {
            return view('canchas.index', [
                'canchas' => collect([]),
                'mensaje' => 'Por favor selecciona una fecha y hora para buscar canchas disponibles.',
                'fecha' => null,
                'hora' => null,
            ]);
        }

        // Si sí hay búsqueda, traemos las canchas y verificamos disponibilidad
        $canchas = $query->get();

        if ($fecha && $hora) {
            [$horaInicio, $horaFin] = explode('-', $hora);

            $canchas->map(function ($cancha) use ($fecha, $horaInicio, $horaFin) {
                $reserva = Reserva::where('id_cancha', $cancha->id)
                    ->where('fecha', $fecha)
                    ->where(function ($q) use ($horaInicio, $horaFin) {
                        $q->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                          ->orWhereBetween('hora_fin', [$horaInicio, $horaFin]);
                    })
                    ->first();

                if ($reserva) {
                    $cancha->estado = 'ocupada';
                } elseif ($cancha->estado == 'mantenimiento') {
                    $cancha->estado = 'mantenimiento';
                } else {
                    $cancha->estado = 'disponible';
                }

                return $cancha;
            });
        }

        // Mensajes
        $mensaje = null;
        if ($canchas->isEmpty()) {
            $mensaje = 'No hay canchas registradas.';
        } elseif ($canchas->where('estado', 'disponible')->count() > 0) {
            $mensaje = '🎾 Hay canchas disponibles para reservar.';
        } else {
            $mensaje = '⚠️ No hay canchas disponibles con los filtros seleccionados.';
        }

        return view('canchas.index', compact('canchas', 'mensaje', 'fecha', 'hora'));
    }
}
