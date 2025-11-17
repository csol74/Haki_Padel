<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\HorarioProfesor;
use App\Models\ReservaClase;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClaseController extends Controller
{
    /**
     * Mostrar lista de profesores
     */
    public function index(Request $request)
    {
        $query = Profesor::with('user', 'horarios')->activos();
        
        // Filtrar por especialidad
        if ($request->filled('especialidad')) {
            $query->where('especialidad', $request->especialidad);
        }
        
        // Filtrar por experiencia mínima
        if ($request->filled('experiencia')) {
            $query->where('experiencia_anios', '>=', $request->experiencia);
        }
        
        // Ordenar
        $ordenar = $request->get('ordenar', 'nombre');
        if ($ordenar === 'nombre') {
            $query->join('users', 'profesores.user_id', '=', 'users.id')
                  ->orderBy('users.name', 'asc')
                  ->select('profesores.*');
        } elseif ($ordenar === 'precio_asc') {
            $query->orderBy('tarifa_base_30min', 'asc');
        } elseif ($ordenar === 'precio_desc') {
            $query->orderBy('tarifa_base_30min', 'desc');
        } elseif ($ordenar === 'experiencia') {
            $query->orderBy('experiencia_anios', 'desc');
        }
        
        $profesores = $query->get();
        
        return view('clases.index', compact('profesores'));
    }

    /**
     * Mostrar detalle del profesor y calendario de disponibilidad
     */
    public function show($id)
    {
        $profesor = Profesor::with(['user', 'horarios' => function($query) {
            $query->where('disponible', true)->orderBy('dia_semana');
        }])->activos()->findOrFail($id);
        
        // Obtener días disponibles para las próximas 2 semanas
        $diasDisponibles = $this->obtenerDiasDisponibles($profesor);
        
        // Verificar si el usuario tiene reservas con este profesor
        $misReservas = [];
        if (Auth::check()) {
            $misReservas = ReservaClase::where('user_id', Auth::id())
                ->where('profesor_id', $profesor->id)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->with('horario')
                ->get();
        }
        
        return view('clases.show', compact('profesor', 'diasDisponibles', 'misReservas'));
    }

    /**
     * Obtener horarios disponibles para un día específico
     */
    public function obtenerHorariosDisponibles(Request $request)
    {
        $request->validate([
            'profesor_id' => 'required|exists:profesores,id',
            'fecha' => 'required|date|after_or_equal:today',
        ]);

        $profesor = Profesor::findOrFail($request->profesor_id);
        $fecha = Carbon::parse($request->fecha);
        $diaSemana = strtolower($fecha->locale('es')->dayName);

        // Obtener horarios del profesor para ese día
        $horarios = HorarioProfesor::where('profesor_id', $profesor->id)
            ->where('dia_semana', $diaSemana)
            ->where('disponible', true)
            ->get();

        $horariosDisponibles = [];

        foreach ($horarios as $horario) {
            $horaInicio = Carbon::parse($horario->hora_inicio);
            $horaFin = Carbon::parse($horario->hora_fin);

            // Generar bloques de 30 minutos
            while ($horaInicio->lt($horaFin)) {
                $horaInicioStr = $horaInicio->format('H:i');
                
                // Verificar si el horario está disponible (no reservado)
                $reservado = ReservaClase::where('profesor_id', $profesor->id)
                    ->where('fecha_clase', $fecha)
                    ->where('hora_inicio', $horaInicioStr)
                    ->whereIn('estado', ['pendiente', 'confirmada', 'en_curso'])
                    ->exists();

                if (!$reservado) {
                    $horariosDisponibles[] = [
                        'hora' => $horaInicioStr,
                        'hora_formateada' => $horaInicio->format('g:i A'),
                        'es_prime' => $horario->es_horario_prime && $horaInicio->hour >= 18,
                        'horario_id' => $horario->id,
                    ];
                }

                $horaInicio->addMinutes(30);
            }
        }

        return response()->json([
            'horarios' => $horariosDisponibles,
            'profesor' => [
                'nombre' => $profesor->user->name,
                'tarifa_base_30min' => $profesor->tarifa_base_30min,
            ]
        ]);
    }

    /**
     * Calcular precio de la clase
     */
    public function calcularPrecio(Request $request)
    {
        $request->validate([
            'profesor_id' => 'required|exists:profesores,id',
            'fecha' => 'required|date',
            'duracion' => 'required|in:30,60,90,120',
            'horario_id' => 'required|exists:horarios_profesor,id',
        ]);

        $profesor = Profesor::findOrFail($request->profesor_id);
        $horario = HorarioProfesor::findOrFail($request->horario_id);
        $fecha = Carbon::parse($request->fecha);
        $duracion = (int) $request->duracion;

        // Calcular precio
        $precioBase = $profesor->tarifa_base_30min * ($duracion / 30);
        $multiplicadorDia = $horario->multiplicador_precio;
        $multiplicadorPrime = $horario->es_horario_prime ? 1.3 : 1.0;

        $precioFinal = round($precioBase * $multiplicadorDia * $multiplicadorPrime, 2);

        return response()->json([
            'precio_base' => $precioBase,
            'multiplicador_dia' => $multiplicadorDia,
            'multiplicador_prime' => $multiplicadorPrime,
            'precio_final' => $precioFinal,
            'detalles' => [
                'dia' => $fecha->locale('es')->isoFormat('dddd'),
                'es_fin_semana' => $fecha->isWeekend(),
                'es_horario_prime' => $horario->es_horario_prime,
            ]
        ]);
    }

    /**
     * Iniciar proceso de reserva (redirige a MercadoPago)
     */
    public function reservar(Request $request)
    {
        $request->validate([
            'profesor_id' => 'required|exists:profesores,id',
            'horario_id' => 'required|exists:horarios_profesor,id',
            'fecha_clase' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'duracion_minutos' => 'required|in:30,60,90,120',
            'nivel' => 'required|in:principiante,intermedio,avanzado',
        ]);

        // LIMPIAR reservas pendientes expiradas (más de 10 minutos sin pagar)
        ReservaClase::where('estado', 'pendiente')
            ->where('created_at', '<', now()->subMinutes(10))
            ->delete();

        $profesor = Profesor::findOrFail($request->profesor_id);
        $horario = HorarioProfesor::findOrFail($request->horario_id);
        $fecha = Carbon::parse($request->fecha_clase);
        $horaInicio = $request->hora_inicio;
        $duracionMinutos = (int) $request->duracion_minutos;

        // Calcular hora fin
        $horaFin = Carbon::parse($horaInicio)->addMinutes($duracionMinutos)->format('H:i');

        // Verificar disponibilidad DESPUÉS de limpiar expiradas
        $reservaExistente = ReservaClase::where('profesor_id', $profesor->id)
            ->where('fecha_clase', $fecha)
            ->where('hora_inicio', $horaInicio)
            ->whereIn('estado', ['pendiente', 'confirmada', 'en_curso'])
            ->exists();

        if ($reservaExistente) {
            return redirect()->back()->with('error', 'Este horario ya está reservado.');
        }

        // Calcular precio
        $precioBase = $profesor->tarifa_base_30min * ($duracionMinutos / 30);
        $multiplicadorDia = $horario->multiplicador_precio;
        $multiplicadorPrime = $horario->es_horario_prime ? 1.3 : 1.0;
        $precio = round($precioBase * $multiplicadorDia * $multiplicadorPrime, 2);

        try {
            DB::beginTransaction();

            // Crear reserva temporal
            $reserva = ReservaClase::create([
                'user_id' => Auth::id(),
                'profesor_id' => $profesor->id,
                'horario_id' => $horario->id,
                'fecha_clase' => $fecha,
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
                'duracion_minutos' => $duracionMinutos,
                'nivel' => $request->nivel,
                'precio' => $precio,
                'estado' => 'pendiente',
                'notas' => $request->notas,
            ]);

            DB::commit();

            // Guardar en sesión
            session(['reserva_clase_pendiente_id' => $reserva->id]);

            // Redirigir a MercadoPago
            $mercadopagoController = new \App\Http\Controllers\MercadoPagoController();
            return $mercadopagoController->createPreferenceClase($reserva);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al crear la reserva: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar reserva de clase
     */
    public function cancelarReserva(Request $request, $id)
    {
        $reserva = ReservaClase::findOrFail($id);

        // Verificar que sea del usuario actual
        if ($reserva->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permiso para cancelar esta reserva.');
        }

        // Verificar que se pueda cancelar
        if (!$reserva->puedeCancelarse()) {
            return redirect()->back()->with('error', 'No se puede cancelar esta reserva.');
        }

        try {
            DB::beginTransaction();

            $reserva->cancelar($request->motivo_cancelacion);

            // Crear notificación
            Notificacion::create([
                'user_id' => Auth::id(),
                'titulo' => 'Clase Cancelada',
                'contenido' => "Has cancelado tu clase con {$reserva->profesor->user->name} para el {$reserva->fecha_clase->format('d/m/Y')}",
                'tipo' => 'clase',
                'leida' => false,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Clase cancelada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al cancelar: ' . $e->getMessage());
        }
    }

    /**
     * Obtener días disponibles para las próximas 2 semanas
     */
    private function obtenerDiasDisponibles($profesor)
    {
        $diasDisponibles = [];
        $hoy = Carbon::today();
        
        // Obtener días de la semana que el profesor trabaja
        $diasTrabajo = $profesor->horarios->pluck('dia_semana')->unique()->toArray();
        
        // Mapeo de días en español a números
        $diasMap = [
            'lunes' => 1, 'martes' => 2, 'miercoles' => 3, 'jueves' => 4,
            'viernes' => 5, 'sabado' => 6, 'domingo' => 0
        ];
        
        // Generar próximos 14 días
        for ($i = 0; $i < 14; $i++) {
            $fecha = $hoy->copy()->addDays($i);
            $diaSemana = strtolower($fecha->locale('es')->dayName);
            
            if (in_array($diaSemana, $diasTrabajo)) {
                $diasDisponibles[] = [
                    'fecha' => $fecha->format('Y-m-d'),
                    'dia_semana' => $diaSemana,
                    'fecha_formateada' => $fecha->locale('es')->isoFormat('ddd D [de] MMMM'),
                    'es_fin_semana' => $fecha->isWeekend(),
                ];
            }
        }
        
        return $diasDisponibles;
    }
}