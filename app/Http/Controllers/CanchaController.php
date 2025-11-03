<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanchaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('canchas');

        // Aplicar filtros
        if ($request->filled('fecha')) {
            // Aquí puedes agregar lógica para filtrar por fecha
        }

        if ($request->filled('hora')) {
            // Aquí puedes agregar lógica para filtrar por hora
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $canchas = $query->get();

        return view('canchas.index', compact('canchas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cancha = DB::table('canchas')->where('id', $id)->first();
        
        if (!$cancha) {
            abort(404);
        }

        return view('canchas.show', compact('cancha'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}