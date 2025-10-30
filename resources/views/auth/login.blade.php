@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="login-page d-flex align-items-center justify-content-center">
    <div class="container py-5">
        <div class="row align-items-center justify-content-center">
            
            <!-- Texto izquierdo -->
            <div class="col-lg-6 col-md-10 text-section mb-5 mb-lg-0">
                <span class="badge bg-info text-dark px-3 py-2 mb-3">Sistema de Reservas Digital</span>
                <h1 class="fw-bold title">Reserva tu cancha<br><span class="subtitle">en segundos</span></h1>
                <p class="text-muted mt-3 description">
                    Accede a nuestro sistema de gestión de reservas, torneos y entrenadores. 
                    Administra todo desde una sola plataforma.
                </p>
            </div>

            <!-- Formulario derecho -->
            <div class="col-lg-5 col-md-10">
                <div class="login-card mx-auto">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="tabs text-center mb-4">
                                <button class="tab active">Iniciar Sesión</button>
                                <a href="{{ route('register') }}" class="tab">Registrarse</a>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required
                                           placeholder="user@gmail.com" autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password" required
                                           placeholder="********" autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Recordarme</label>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Iniciar Sesión
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="forgot-link text-decoration-none" href="{{ route('password.request') }}">
                                            ¿Olvidaste tu contraseña?
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
