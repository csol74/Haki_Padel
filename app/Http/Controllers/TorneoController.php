<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TorneoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('torneos');

        // Aplicar filtros
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
        }

        $torneos = $query->orderBy('fecha_inicio', 'asc')->get();

        return view('torneos.index', compact('torneos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $torneo = DB::table('torneos')->where('id', $id)->first();
        
        if (!$torneo) {
            abort(404);
        }

        // Obtener participantes del torneo
        $participantes = DB::table('participantes_torneo')
            ->join('usuarios', 'participantes_torneo.usuario_id', '=', 'usuarios.id')
            ->where('participantes_torneo.torneo_id', $id)
            ->select('usuarios.nombre', 'usuarios.email', 'participantes_torneo.fecha_inscripcion')
            ->get();

        return view('torneos.show', compact('torneo', 'participantes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
        ]);

        // Verificar si el usuario ya está inscrito
        $yaInscrito = DB::table('participantes_torneo')
            ->where('torneo_id', $request->torneo_id)
            ->where('usuario_id', auth()->id())
            ->exists();

        if ($yaInscrito) {
            return redirect()->back()->with('error', 'Ya estás inscrito en este torneo.');
        }

        // Inscribir al usuario
        DB::table('participantes_torneo')->insert([
            'torneo_id' => $request->torneo_id,
            'usuario_id' => auth()->id(),
            'fecha_inscripcion' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', '¡Te has inscrito exitosamente al torneo!');
    }
}