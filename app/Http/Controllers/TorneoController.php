<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TorneoController extends Controller
{
    /**
     * Mostrar lista de torneos
     */
    public function index(Request $request)
    {
        $query = Torneo::with('organizador');
        
        // Mapear los valores del filtro de estado a los valores de la BD
        $estadoMap = [
            'abierto' => 'inscripciones_abiertas',
            'en-curso' => 'en_curso',
            'finalizado' => 'finalizado',
            'cerrado' => 'planificacion'
        ];
        
        // Aplicar filtros
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        
        if ($request->filled('estado')) {
            $estadoDB = $estadoMap[$request->estado] ?? $request->estado;
            $query->where('estado', $estadoDB);
        }
        
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
        }
        
        $torneos = $query->orderBy('fecha_inicio', 'asc')->get();
        
        // Mapear los estados de la BD a los estados para la vista
        $torneos->transform(function($torneo) {
            // Agregar campos adicionales para la vista
            $torneo->estado_vista = $this->mapEstadoParaVista($torneo->estado);
            $torneo->participantes_actuales = $torneo->participantes()->count();
            $torneo->max_participantes = $torneo->cantidad_max_participantes;
            $torneo->premio = 500000; // Puedes agregar este campo a tu BD si lo necesitas
            $torneo->fecha_limite_inscripcion = $torneo->fecha_inscripcion_limite;
            
            // Mapear estado para la vista
            $torneo->estado = $torneo->estado_vista;
            
            return $torneo;
        });
        
        // Verificar inscripciones del usuario actual
        $torneosInscritos = [];
        if (Auth::check()) {
            $torneosInscritos = Auth::user()->torneosInscritos->pluck('id')->toArray();
        }
        
        return view('torneos.index', compact('torneos', 'torneosInscritos'));
    }

    /**
     * Mostrar detalle del torneo
     */
    public function show($id)
    {
        $torneo = Torneo::with(['participantes', 'organizador'])->findOrFail($id);
        
        // Verificar si el usuario actual está inscrito
        $estaInscrito = false;
        if (Auth::check()) {
            $estaInscrito = $torneo->participantes->contains(Auth::id());
        }
        
        // Contar participantes actuales (excluyendo retirados)
        $participantesActuales = $torneo->participantes()
            ->wherePivot('estado', '!=', 'retirado')
            ->count();
        
        // Calcular lugares disponibles
        $lugaresDisponibles = null;
        if ($torneo->cantidad_max_participantes) {
            $lugaresDisponibles = $torneo->cantidad_max_participantes - $participantesActuales;
        }
        
        // Verificar si se puede inscribir
        $puedeInscribirse = Auth::check() && 
            !$estaInscrito && 
            $torneo->estado == 'inscripciones_abiertas' &&
            now()->lessThanOrEqualTo($torneo->fecha_inscripcion_limite) &&
            ($lugaresDisponibles === null || $lugaresDisponibles > 0);
        
        // Agregar campos adicionales para compatibilidad con la vista
        $torneo->premio = 500000; // Puedes agregar este campo a tu BD
        
        return view('torneos.show', compact(
            'torneo', 
            'estaInscrito', 
            'participantesActuales',
            'lugaresDisponibles',
            'puedeInscribirse'
        ));
    }

    /**
     * Iniciar proceso de inscripción al torneo (redirige a MercadoPago)
     */
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
        ]);
        
        $torneo = Torneo::findOrFail($request->torneo_id);
        $user = Auth::user();
        
        // Verificar si ya está inscrito
        if ($torneo->participantes->contains($user->id)) {
            return redirect()->back()->with('error', 'Ya estás inscrito en este torneo.');
        }
        
        // Verificar estado del torneo
        if ($torneo->estado != 'inscripciones_abiertas') {
            return redirect()->back()->with('error', 'Las inscripciones no están abiertas para este torneo.');
        }
        
        // Verificar disponibilidad
        if ($torneo->cantidad_max_participantes) {
            $inscritos = $torneo->participantes()
                ->wherePivot('estado', '!=', 'retirado')
                ->count();
            if ($inscritos >= $torneo->cantidad_max_participantes) {
                return redirect()->back()->with('error', 'El torneo está completo.');
            }
        }
        
        // Verificar fecha límite
        if (now()->greaterThan($torneo->fecha_inscripcion_limite)) {
            return redirect()->back()->with('error', 'El período de inscripción ha finalizado.');
        }
        
        // Guardar en sesión el torneo pendiente de pago
        session(['torneo_pendiente_id' => $torneo->id]);
        
        // Redirigir a MercadoPago
        $mercadopagoController = new \App\Http\Controllers\MercadoPagoController();
        return $mercadopagoController->createPreferenceTorneo($torneo);
    }
    
    /**
     * Cancelar inscripción al torneo
     */
    public function cancelarInscripcion(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
        ]);
        
        $torneo = Torneo::findOrFail($request->torneo_id);
        $user = Auth::user();
        
        // Verificar si está inscrito
        if (!$torneo->participantes->contains($user->id)) {
            return redirect()->back()->with('error', 'No estás inscrito en este torneo.');
        }
        
        // Verificar si el torneo ya comenzó
        if ($torneo->estado == 'en_curso' || $torneo->estado == 'finalizado') {
            return redirect()->back()->with('error', 'No puedes cancelar tu inscripción una vez que el torneo ha comenzado.');
        }
        
        try {
            DB::beginTransaction();
            
            // Actualizar estado a retirado
            $torneo->participantes()->updateExistingPivot($user->id, [
                'estado' => 'retirado',
                'updated_at' => now()
            ]);
            
            // Crear notificación
            Notificacion::create([
                'user_id' => $user->id,
                'titulo' => 'Inscripción cancelada',
                'contenido' => "Has cancelado tu inscripción al torneo: {$torneo->nombre}",
                'tipo' => 'torneo',
                'leida' => false
            ]);
            
            DB::commit();
            
            return redirect()->route('torneos.index')
                ->with('success', 'Tu inscripción ha sido cancelada.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Ocurrió un error al cancelar tu inscripción.');
        }
    }
    
    /**
     * Mapear estado de BD a estado para vista
     */
    private function mapEstadoParaVista($estadoDB)
    {
        $mapa = [
            'inscripciones_abiertas' => 'abierto',
            'en_curso' => 'en-curso',
            'finalizado' => 'finalizado',
            'planificacion' => 'cerrado'
        ];
        
        return $mapa[$estadoDB] ?? $estadoDB;
    }
}