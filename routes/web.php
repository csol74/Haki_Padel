<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CanchaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rutas de Canchas
Route::get('/canchas', [CanchaController::class, 'index'])->name('canchas.index');
Route::get('/canchas/{id}', [CanchaController::class, 'show'])->name('canchas.show');
Route::post('/reservas/crear', [ReservaController::class, 'store'])->name('reservas.store');

// Rutas de Contacto
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
Route::post('/contacto', [ContactoController::class, 'store'])->name('contacto.store');

// NUEVAS RUTAS - Torneos
Route::get('/torneos', [TorneoController::class, 'index'])->name('torneos.index');
Route::get('/torneos/{id}', [TorneoController::class, 'show'])->name('torneos.show');
Route::post('/torneos/inscribir', [TorneoController::class, 'store'])->name('torneos.inscribir');

//Rutas de perfil
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

//Ruta de reserva
Route::middleware(['auth'])->group(function () {
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
});
