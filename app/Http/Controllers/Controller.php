<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\Pago;


use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

     public function __construct()
    {
        // Compartir el número de pagos pendientes con todas las vistas
        view()->composer('*', function ($view) {
            $user = Auth::user();

            // Ya no es necesario validar si el usuario está logueado
            $pendingCount = Pago::where('user_id', $user->id)
                ->where('estado', 'pendiente')
                ->count();

            $view->with('pendingPayments', $pendingCount);
        });
    }
}
