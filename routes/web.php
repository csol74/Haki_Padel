<?php

use App\Http\Controllers\PagoController;
use App\Http\Controllers\AdminController;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

// WEBHOOK DE MERCADOPAGO (FUERA DEL MIDDLEWARE AUTH)
Route::post('/mercadopago/webhook', [MercadoPagoController::class, 'webhook'])->name('mercadopago.webhook');

// ========== RUTAS DE ADMINISTRACIÓN ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Gestión de usuarios
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::delete('/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('usuarios.eliminar');

    // Gestión de reservas
    Route::get('/reservas', [AdminController::class, 'reservas'])->name('reservas');
    Route::delete('/reservas/{id}', [AdminController::class, 'cancelarReserva'])->name('reservas.cancelar');

    // Gestión de torneos
    Route::get('/torneos', [AdminController::class, 'torneos'])->name('torneos');
    Route::post('/torneos', [AdminController::class, 'crearTorneo'])->name('torneos.crear');
    Route::put('/torneos/{id}', [AdminController::class, 'actualizarTorneo'])->name('torneos.actualizar');
    Route::delete('/torneos/{id}', [AdminController::class, 'eliminarTorneo'])->name('torneos.eliminar');

    // Reportes
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
});

// ========== RUTAS DE CLIENTES ==========
Route::middleware(['auth', 'limpiar.reservas'])->group(function () {

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Canchas
    Route::get('/canchas', [CanchaController::class, 'index'])->name('canchas.index');
    Route::get('/canchas/{id}', [CanchaController::class, 'show'])->name('canchas.show');

    // Contacto
    Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
    Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

    // Torneos
    Route::get('/torneos', [TorneoController::class, 'index'])->name('torneos.index');
    Route::get('/torneos/{id}', [TorneoController::class, 'show'])->name('torneos.show');
    Route::post('/torneos/inscribir', [TorneoController::class, 'store'])->name('torneos.inscribir');
    Route::post('/torneos/cancelar-inscripcion', [TorneoController::class, 'cancelarInscripcion'])->name('torneos.cancelar');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Reservas
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{id}/pago', [ReservaController::class, 'pago'])->name('reservas.pago');
    Route::post('/reservas/{id}/completar', [ReservaController::class, 'completarPago'])->name('reservas.completar');
    Route::delete('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::get('/reservas/exito/{id}', [PagoController::class, 'exito'])->name('reservas.exito');

    // Pagos
    Route::post('/pagos/{idReserva}', [PagoController::class, 'procesarPago'])->name('pagos.procesar');

    // Notificaciones
    Route::post('/notificaciones/leer', function () {
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);
        return response()->json(['ok' => true]);
    })->name('notificaciones.leer');

    // MercadoPago
    Route::get('/reservas/{reserva}/pagar', [MercadoPagoController::class, 'createPreference'])
        ->name('mercadopago.preference');
    Route::get('/mercadopago/confirmar', [MercadoPagoController::class, 'confirmar'])
        ->name('mercadopago.confirmar');
    Route::get('/mercadopago/success', [MercadoPagoController::class, 'success'])
        ->name('mercadopago.success');
    Route::get('/mercadopago/failure', [MercadoPagoController::class, 'failure'])
        ->name('mercadopago.failure');
});
