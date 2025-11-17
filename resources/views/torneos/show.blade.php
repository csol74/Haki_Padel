@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/torneos.css') }}">
@endsection

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    <div class="container py-4">
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb-custom mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('torneos.index') }}">Torneos</a></li>
                <li class="breadcrumb-item active">{{ $torneo->nombre }}</li>
            </ol>
        </nav>

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

        <!-- Hero Section -->
        <div class="tournament-hero mb-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold mb-3">{{ $torneo->nombre }}</h1>
                        <p class="lead mb-3">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            {{ $torneo->ubicacion ?? 'Hakipadel Club' }}
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge bg-white text-dark px-3 py-2">
                                <i class="bi bi-trophy me-1"></i>{{ ucfirst($torneo->categoria ?? 'mixto') }}
                            </span>
                            @if($torneo->estado == 'inscripciones_abiertas')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>Inscripciones Abiertas
                                </span>
                            @elseif($torneo->estado == 'en_curso')
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-play-circle me-1"></i>En Curso
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i>Cerrado
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-trophy display-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Principal -->
            <div class="col-lg-8 mb-4">
                <!-- Descripción -->
                <div class="info-card p-4 mb-4">
                    <h4 class="mb-3 text-primary">
                        <i class="bi bi-info-circle me-2"></i>Descripción del Torneo
                    </h4>
                    <p class="text-muted">
                        {{ $torneo->descripcion ?: 'Únete a este emocionante torneo de pádel donde podrás demostrar tus habilidades y competir con los mejores jugadores. Gran ambiente, premios atractivos y la oportunidad de formar parte de nuestra comunidad de pádel.' }}
                    </p>
                </div>

                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $participantesActuales }}</div>
                            <div class="stats-label">Inscritos</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ $torneo->cantidad_max_participantes ?? '∞' }}</div>
                            <div class="stats-label">Cupos</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">${{ number_format($torneo->premio ?? 500000, 0, ',', '.') }}</div>
                            <div class="stats-label">Premio</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-number">{{ \Carbon\Carbon::parse($torneo->fecha_inicio)->diffInDays(now()) }}</div>
                            <div class="stats-label">Días restantes</div>
                        </div>
                    </div>
                </div>

                <!-- Fechas Importantes -->
                <div class="info-card p-4 mb-4">
                    <h4 class="mb-3 text-primary">
                        <i class="bi bi-calendar-week me-2"></i>Fechas Importantes
                    </h4>
                    <div class="timeline-item">
                        <h6 class="fw-bold">Cierre de Inscripciones</h6>
                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($torneo->fecha_inscripcion_limite)->format('d/m/Y') }}</p>
                    </div>
                    <div class="timeline-item">
                        <h6 class="fw-bold">Inicio del Torneo</h6>
                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }}</p>
                    </div>
                    <div class="timeline-item">
                        <h6 class="fw-bold">Final del Torneo</h6>
                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($torneo->fecha_fin)->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Lista de Participantes -->
                <div class="info-card p-4">
                    <h4 class="mb-3 text-primary">
                        <i class="bi bi-people me-2"></i>
                        Participantes ({{ $participantesActuales }}/{{ $torneo->cantidad_max_participantes ?? '∞' }})
                    </h4>
                    
                    @if($torneo->participantes->count() > 0)
                        <div class="list-group">
                            @foreach($torneo->participantes->where('pivot.estado', '!=', 'retirado') as $participante)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="participant-avatar">
                                        {{ strtoupper(substr($participante->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">
                                            {{ $participante->name }}
                                            @if($participante->id == Auth::id())
                                                <span class="badge bg-primary ms-2">Tú</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            Inscrito el {{ \Carbon\Carbon::parse($participante->pivot->created_at)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    @if($participante->pivot->estado == 'confirmado')
                                        <span class="badge bg-success">Confirmado</span>
                                    @else
                                        <span class="badge bg-info">Inscrito</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No hay participantes inscritos todavía. ¡Sé el primero!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="col-lg-4">
                <!-- Acciones de Inscripción -->
                <div class="info-card p-4 mb-4 position-sticky" style="top: 20px;">
                    <h5 class="mb-3 text-primary">
                        <i class="bi bi-lightning me-2"></i>Acciones Rápidas
                    </h5>
                    
                    @auth
                        @if($estaInscrito)
                            <div class="alert alert-success mb-3">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>¡Ya estás inscrito!</strong>
                            </div>
                            
                            @if($torneo->estado == 'inscripciones_abiertas' || $torneo->estado == 'planificacion')
                                <form action="{{ route('torneos.cancelar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="torneo_id" value="{{ $torneo->id }}">
                                    <button type="submit" class="btn btn-danger w-100" 
                                            onclick="return confirm('¿Estás seguro de que deseas cancelar tu inscripción?')">
                                        <i class="bi bi-x-circle me-2"></i>Cancelar Inscripción
                                    </button>
                                </form>
                            @endif
                        @elseif($puedeInscribirse)
                            @if($lugaresDisponibles !== null)
                                <div class="alert alert-warning mb-3">
                                    <small>
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Quedan {{ $lugaresDisponibles }} lugares
                                    </small>
                                </div>
                            @endif
                            
                            <form action="{{ route('torneos.inscribir') }}" method="POST">
                                @csrf
                                <input type="hidden" name="torneo_id" value="{{ $torneo->id }}">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-trophy me-2"></i>Inscribirme Ahora
                                </button>
                            </form>

                            @if($torneo->precio_inscripcion > 0)
                                <div class="alert alert-info mt-3 mb-0">
                                    <small>
                                        <i class="bi bi-cash me-1"></i>
                                        Costo: ${{ number_format($torneo->precio_inscripcion, 0, ',', '.') }}
                                    </small>
                                </div>
                            @else
                                <div class="text-center mt-3">
                                    <span class="badge bg-success">INSCRIPCIÓN GRATUITA</span>
                                </div>
                            @endif
                        @else
                            @if($torneo->estado != 'inscripciones_abiertas')
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Las inscripciones están cerradas
                                </div>
                            @elseif($lugaresDisponibles === 0)
                                <div class="alert alert-danger">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Torneo completo
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Inicia sesión para inscribirte
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </a>
                    @endauth

                    <hr>

                    <!-- Información del Organizador -->
                    <h6 class="mb-3">Información del Torneo</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-person-badge text-primary me-2"></i>
                            <strong>Organizador:</strong> {{ $torneo->organizador->name }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-whatsapp text-success me-2"></i>
                            <strong>WhatsApp:</strong> +57 311 217 2009
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope text-info me-2"></i>
                            <strong>Email:</strong> info@hakipadel.com
                        </li>
                    </ul>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('torneos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Ver Todos los Torneos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection