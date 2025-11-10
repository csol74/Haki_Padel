<?php
// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
public function show()
{
    $user = Auth::user();

    // Puedes calcular o simular las estadísticas
    $stats = [
        'matches_played' => 12,
        'tournaments' => 3,
        'member_since' => $user->created_at->format('Y'),
        'total_reservations' => 20,
        'completed_reservations' => 15,
        'cancelled_reservations' => 5,
    ];

    return view('profile.show', compact('user', 'stats'));
}



    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update($request->only(['name', 'email']));
        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }
}
