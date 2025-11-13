<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Dashboard principal del administrador
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_usuarios' => DB::table('users')->where('role', 'cliente')->count(),
            'total_reservas' => DB::table('reservas')->count(),
            'reservas_hoy' => DB::table('reservas')->whereDate('fecha', today())->count(),
            'total_torneos' => DB::table('torneos')->count(),
            'ingresos_mes' => DB::table('pagos')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('estado', 'completado')
                ->sum('monto'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ============= GESTIÓN DE USUARIOS =============

    public function usuarios()
    {
        $usuarios = DB::table('users')
            ->where('role', 'cliente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    public function eliminarUsuario($id)
    {
        try {
            DB::beginTransaction();

            // Verificar que no es un admin
            $usuario = DB::table('users')->where('id', $id)->first();

            if ($usuario->role === 'admin') {
                return redirect()->back()->with('error', 'No puedes eliminar un administrador.');
            }

            // Eliminar el usuario (las reservas se eliminarán en cascada si está configurado)
            DB::table('users')->where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Usuario eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el usuario.');
        }
    }

    // ============= GESTIÓN DE RESERVAS =============

    public function reservas()
    {
        $reservas = DB::table('reservas')
            ->join('users', 'reservas.user_id', '=', 'users.id')
            ->join('canchas', 'reservas.id_cancha', '=', 'canchas.id')
            ->select(
                'reservas.*',
                'users.name as usuario_nombre',
                'users.email as usuario_email',
                'canchas.nombre as cancha_nombre',
                'canchas.tipo as cancha_tipo',
                'canchas.precio_hora',
                DB::raw('TIMESTAMPDIFF(HOUR, reservas.hora_inicio, reservas.hora_fin) as duracion_horas'),
                DB::raw('canchas.precio_hora * TIMESTAMPDIFF(HOUR, reservas.hora_inicio, reservas.hora_fin) as precio_total')
            )
            ->orderBy('reservas.fecha', 'desc')
            ->orderBy('reservas.hora_inicio', 'desc')
            ->get();

        return view('admin.reservas', compact('reservas'));
    }

    public function cancelarReserva($id)
    {
        try {
            DB::beginTransaction();

            $reserva = DB::table('reservas')->where('id', $id)->first();

            if (!$reserva) {
                return redirect()->back()->with('error', 'Reserva no encontrada.');
            }

            // Actualizar estado de la reserva
            DB::table('reservas')
                ->where('id', $id)
                ->update([
                    'estado' => 'cancelada',
                    'updated_at' => now()
                ]);

            // Crear notificación para el usuario
            DB::table('notificaciones')->insert([
                'user_id' => $reserva->user_id,
                'titulo' => 'Reserva Cancelada por Administrador',
                'mensaje' => 'Tu reserva para el ' . date('d/m/Y', strtotime($reserva->fecha)) . ' ha sido cancelada por el administrador.',
                'tipo' => 'reserva',
                'leida' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Reserva cancelada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al cancelar reserva: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cancelar la reserva.');
        }
    }

    // ============= GESTIÓN DE TORNEOS =============

    public function torneos()
    {
        $torneos = DB::table('torneos')
            ->leftJoin('participantes_torneo', 'torneos.id', '=', 'participantes_torneo.id_torneo')
            ->select(
                'torneos.*',
                DB::raw('COUNT(CASE WHEN participantes_torneo.estado IN ("inscrito", "confirmado") THEN 1 END) as participantes_count')
            )
            ->groupBy(
                'torneos.id',
                'torneos.nombre',
                'torneos.descripcion',
                'torneos.id_organizador',
                'torneos.fecha_inicio',
                'torneos.fecha_fin',
                'torneos.fecha_inscripcion_limite',
                'torneos.categoria',
                'torneos.precio_inscripcion',
                'torneos.cantidad_max_participantes',
                'torneos.estado',
                'torneos.created_at',
                'torneos.updated_at'
            )
            ->orderBy('torneos.fecha_inicio', 'desc')
            ->get();

        return view('admin.torneos', compact('torneos'));
    }

    public function crearTorneo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fecha_inscripcion_limite' => 'required|date|before:fecha_inicio',
            'categoria' => 'required|in:masculino,femenino,mixto',
            'precio_inscripcion' => 'required|numeric|min:0',
            'cantidad_max_participantes' => 'required|integer|min:1',
            'estado' => 'required|in:planificacion,inscripciones_abiertas,en_curso,finalizado',
        ]);

        try {
            DB::table('torneos')->insert([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'id_organizador' => Auth::id(),
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'fecha_inscripcion_limite' => $request->fecha_inscripcion_limite,
                'categoria' => $request->categoria,
                'precio_inscripcion' => $request->precio_inscripcion,
                'cantidad_max_participantes' => $request->cantidad_max_participantes,
                'estado' => $request->estado,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Torneo creado exitosamente.');

        } catch (\Exception $e) {
            \Log::error('Error al crear torneo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear el torneo.');
        }
    }

    public function actualizarTorneo(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fecha_inscripcion_limite' => 'required|date|before:fecha_inicio',
            'categoria' => 'required|in:masculino,femenino,mixto',
            'precio_inscripcion' => 'required|numeric|min:0',
            'cantidad_max_participantes' => 'required|integer|min:1',
            'estado' => 'required|in:planificacion,inscripciones_abiertas,en_curso,finalizado',
        ]);

        try {
            DB::table('torneos')
                ->where('id', $id)
                ->update([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $request->fecha_fin,
                    'fecha_inscripcion_limite' => $request->fecha_inscripcion_limite,
                    'categoria' => $request->categoria,
                    'precio_inscripcion' => $request->precio_inscripcion,
                    'cantidad_max_participantes' => $request->cantidad_max_participantes,
                    'estado' => $request->estado,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Torneo actualizado exitosamente.');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar torneo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el torneo.');
        }
    }

    public function eliminarTorneo($id)
    {
        try {
            DB::beginTransaction();

            // Verificar si hay participantes
            $participantes = DB::table('participantes_torneo')
                ->where('id_torneo', $id)
                ->count();

            if ($participantes > 0) {
                return redirect()->back()->with('error', 'No puedes eliminar un torneo con participantes inscritos.');
            }

            DB::table('torneos')->where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Torneo eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar torneo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el torneo.');
        }
    }

    // ============= REPORTES Y ESTADÍSTICAS =============

    public function reportes()
    {
        // Ingresos por mes (últimos 6 meses) - desde tabla pagos
        $ingresosMensuales = DB::table('pagos')
            ->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as anio'),
                DB::raw('SUM(monto) as total')
            )
            ->where('estado', 'completado')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes', 'anio')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        // Reservas por cancha
        $reservasPorCancha = DB::table('reservas')
            ->join('canchas', 'reservas.id_cancha', '=', 'canchas.id')
            ->select(
                'canchas.nombre',
                DB::raw('COUNT(*) as total_reservas')
            )
            ->where('reservas.estado', '!=', 'cancelada')
            ->groupBy('canchas.id', 'canchas.nombre')
            ->orderBy('total_reservas', 'desc')
            ->get();

        return view('admin.reportes', compact('ingresosMensuales', 'reservasPorCancha'));
    }
}
