@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/torneos.css') }}">
<style>
.profesor-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
}

.profesor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,150,136,0.3);
}

.profesor-header {
    background: linear-gradient(135deg, #009688 0%, #00796B 100%);
    color: white;
    padding: 20px;
    position: relative;
}

.profesor-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    font-weight: bold;
    color: #009688;
    margin: 0 auto 15px;
    border: 4px solid rgba(255,255,255,0.3);
}

.especialidad-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255,255,255,0.2);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    backdrop-filter: blur(10px);
}

.stat-item {
    text-align: center;
    padding: 10px;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #009688;
}

.stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
}

.precio-tag {
    background: #FFD700;
    color: #333;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: bold;
    display: inline-block;
    margin: 10px 0;
}

.filters-section {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}
</style>
@endsection

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    <div class="container py-4">
        
        <!-- Título -->
        <div class="text-center mb-4">
            <h1 class="page-title">
                <i class="bi bi-people me-2"></i>Profesores de Pádel
            </h1>
            <p class="text-muted">Entrena con los mejores profesionales</p>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <form method="GET" action="{{ route('clases.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Especialidad</label>
                        <select class="form-select" name="especialidad">
                            <option value="">Todas</option>
                            <option value="competitivo" {{ request('especialidad') == 'competitivo' ? 'selected' : '' }}>
                                Competitivo
                            </option>
                            <option value="infantil" {{ request('especialidad') == 'infantil' ? 'selected' : '' }}>
                                Infantil
                            </option>
                            <option value="casual" {{ request('especialidad') == 'casual' ? 'selected' : '' }}>
                                Casual
                            </option>
                            <option value="acondicionamiento_fisico" {{ request('especialidad') == 'acondicionamiento_fisico' ? 'selected' : '' }}>
                                Acondicionamiento Físico
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Experiencia Mínima</label>
                        <select class="form-select" name="experiencia">
                            <option value="">Cualquiera</option>
                            <option value="3" {{ request('experiencia') == '3' ? 'selected' : '' }}>3+ años</option>
                            <option value="5" {{ request('experiencia') == '5' ? 'selected' : '' }}>5+ años</option>
                            <option value="10" {{ request('experiencia') == '10' ? 'selected' : '' }}>10+ años</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Ordenar por</label>
                        <select class="form-select" name="ordenar">
                            <option value="nombre" {{ request('ordenar') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                            <option value="precio_asc" {{ request('ordenar') == 'precio_asc' ? 'selected' : '' }}>Precio (menor a mayor)</option>
                            <option value="precio_desc" {{ request('ordenar') == 'precio_desc' ? 'selected' : '' }}>Precio (mayor a menor)</option>
                            <option value="experiencia" {{ request('ordenar') == 'experiencia' ? 'selected' : '' }}>Experiencia</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            <i class="bi bi-search me-1"></i>Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid de Profesores -->
        <div class="row g-4">
            @forelse($profesores as $profesor)
            <div class="col-lg-4 col-md-6">
                <div class="card profesor-card">
                    <div class="profesor-header">
                        <span class="especialidad-badge">
                            {{ $profesor->especialidad_formateada }}
                        </span>
                        <div class="profesor-avatar">
                            {{ strtoupper(substr($profesor->user->name, 0, 1)) }}
                        </div>
                        <h5 class="text-center mb-0">{{ $profesor->user->name }}</h5>
                    </div>

                    <div class="card-body">
                        <!-- Estadísticas -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $profesor->experiencia_anios }}</div>
                                    <div class="stat-label">Años Exp.</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $profesor->horarios->count() }}</div>
                                    <div class="stat-label">Horarios</div>
                                </div>
                            </div>
                        </div>

                        <!-- Biografía -->
                        <p class="text-muted small mb-3">
                            {{ Str::limit($profesor->biografia, 100) }}
                        </p>

                        <!-- Precio -->
                        <div class="text-center">
                            <div class="precio-tag">
                                Desde ${{ number_format($profesor->tarifa_base_30min, 0, ',', '.') }}/30min
                            </div>
                        </div>

                        <!-- Horarios disponibles -->
                        <div class="mt-3">
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-calendar-week me-1"></i>Disponible:
                            </small>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($profesor->horarios->unique('dia_semana')->take(4) as $horario)
                                    <span class="badge bg-secondary">{{ ucfirst(substr($horario->dia_semana, 0, 3)) }}</span>
                                @endforeach
                                @if($profesor->horarios->unique('dia_semana')->count() > 4)
                                    <span class="badge bg-info">+{{ $profesor->horarios->unique('dia_semana')->count() - 4 }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Botón -->
                        <div class="d-grid mt-3">
                            <a href="{{ route('clases.show', $profesor->id) }}" class="btn btn-success">
                                <i class="bi bi-calendar-check me-1"></i>Ver Horarios y Reservar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-person-x display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No se encontraron profesores</h4>
                    <p class="text-muted">Intenta modificar los filtros de búsqueda</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Información adicional -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="bi bi-info-circle me-2"></i>¿Cómo funcionan las clases?
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Elige tu profesor según especialidad y horario
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Selecciona duración: 30min, 1h, 1.5h o 2h
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Reserva y paga de forma segura
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Recibe confirmación inmediata
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="bi bi-star me-2"></i>Beneficios
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-trophy text-warning me-2"></i>
                                Profesores certificados y experimentados
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock text-info me-2"></i>
                                Horarios flexibles de 6am a 10pm
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-cash text-success me-2"></i>
                                Precios competitivos y transparentes
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-graph-up text-danger me-2"></i>
                                Mejora tu técnica y nivel de juego
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection