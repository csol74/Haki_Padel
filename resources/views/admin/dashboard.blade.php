@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1"><i class="bi bi-shield-check text-primary me-2"></i>Panel de Administración</h2>
                            <p class="text-muted mb-0">Bienvenido, {{ Auth::user()->name }}</p>
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-1"></i>Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 text-primary"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_usuarios'] }}</h3>
                    <p class="text-muted mb-0">Usuarios Registrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check display-4 text-success"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_reservas'] }}</h3>
                    <p class="text-muted mb-0">Total Reservas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-trophy display-4 text-warning"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_torneos'] }}</h3>
                    <p class="text-muted mb-0">Torneos Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack display-4 text-info"></i>
                    <h3 class="mt-3 mb-0">${{ number_format($stats['ingresos_mes'], 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Ingresos del Mes</p>
                </div>
            </div>
        </div>
    </div>
    <!-- En tu dashboard actual (admin.dashboard) -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-star-fill display-4 text-warning"></i>
                <h3 class="mt-3 mb-0">{{ $stats['socios_activos'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Socios Activos</p>
                @php
                    $pendientes = \App\Models\SolicitudMembresia::where('estado', 'pendiente')->count();
                @endphp
                @if($pendientes > 0)
                    <a href="{{ route('admin.membresias.index') }}" class="btn btn-sm btn-warning mt-2">
                        {{ $pendientes }} pendientes
                    </a>
                @endif
            </div>
        </div>
    <!-- Gráficos y otras secciones pueden ir aquí -->
</div>
@endsection
