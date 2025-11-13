<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Cargar reservas con relaciones
        $user->load('reservas.cancha');

        // Calcular estadísticas
        $stats = [
            'matches_played' => $user->reservas()->where('estado', 'completada')->count(),
            'tournaments' => 0,
            'member_since' => $user->created_at->format('Y'),
            'total_reservations' => $user->reservas()->count(),
            'completed_reservations' => $user->reservas()->where('estado', 'completada')->count(),
            'cancelled_reservations' => $user->reservas()->where('estado', 'cancelada')->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente');
    }
}
