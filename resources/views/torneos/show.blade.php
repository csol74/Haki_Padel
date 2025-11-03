@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/torneos.css') }}">
@endsection

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    @include('layouts.navbar')
    
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-custom">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('torneos.index') }}" class="text-decoration-none">Torneos</a></li>
                <li class="breadcrumb-item active">{{ $torneo->nombre ?? 'Torneo' }}</li>
            </ol>
        </nav>

        <!-- Hero del Torneo -->
        <div class="tournament-hero">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-trophy display-4 me-3"></i>
                            <div>
                                <h1 class="fw-bold mb-1">{{ $torneo->nombre ?? 'Torneo de Pádel' }}</h1>
                                <div class="d-flex gap-3">
                                    <span class="badge bg-light text-dark">{{ ucfirst($torneo->categoria ?? 'General') }}</span>
                                    <span class="badge bg-{{ $torneo->estado == 'abierto' ? 'success' : ($torneo->estado == 'en-curso' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($torneo->estado ?? 'Abierto') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p class="lead mb-3">{{ $torneo->descripcion ?? 'Únete a este emocionante torneo de pádel y compite por grandes premios.' }}</p>
                        <div class="d-flex gap-4">
                            <div>
                                <i class="bi bi-calendar3 me-2"></i>
                                <span>{{ date('d/m/Y', strtotime($torneo->fecha_inicio ?? now())) }}</span>
                            </div>
                            <div>
                                <i class="bi bi-geo-alt me-2"></i>
                                <span>{{ $torneo->ubicacion ?? 'Hakipadel Club' }}</span>
                            </div>
                            <div>
                                <i class="bi bi-people me-2"></i>
                                <span>{{ $torneo->participantes_actuales ?? 0 }}/{{ $torneo->max_participantes ?? 16 }} participantes</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="prize-amount text-white display-6">${{ number_format($torneo->premio ?? 500000, 0, ',', '.') }}</div>
                        <div class="opacity-75 fs-5">Premio total</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna de Información -->
            <div class="col-lg-8">
                <!-- Detalles del Torneo -->
                <div class="info-card card mb-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Detalles del Torneo</h4>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="stats-card">
                                    <div class="stats-number">{{ date('d', strtotime($torneo->fecha_inicio ?? now())) }}</div>
                                    <div class="stats-label">{{ date('M Y', strtotime($torneo->fecha_inicio ?? now())) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stats-card">
                                    <div class="stats-number">{{ $torneo->max_participantes ?? 16 }}</div>
                                    <div class="stats-label">Max Participantes</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stats-card">
                                    <div class="stats-number">${{ number_format(($torneo->premio ?? 500000) * 0.5, 0, ',', '.') }}</div>
                                    <div class="stats-label">1er Lugar</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stats-card">
                                    <div class="stats-number">${{ number_format(($torneo->premio ?? 500000) * 0.3, 0, ',', '.') }}</div>
                                    <div class="stats-label">2do Lugar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Importante -->
                <div class="info-card card mb-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Información Importante</h4>
                        <div class="timeline-item">
                            <h6 class="fw-bold">Fecha de Inicio</h6>
                            <p class="text-muted">{{ date('d/m/Y H:i', strtotime($torneo->fecha_inicio ?? now())) }}</p>
                        </div>
                        <div class="timeline-item">
                            <h6 class="fw-bold">Fecha Límite de Inscripción</h6>
                            <p class="text-muted">{{ date('d/m/Y H:i', strtotime($torneo->fecha_limite_inscripcion ?? now())) }}</p>
                        </div>
                        <div class="timeline-item">
                            <h6 class="fw-bold">Formato del Torneo</h6>
                            <p class="text-muted">{{ $torneo->formato ?? 'Eliminación directa con repechaje' }}</p>
                        </div>
                        <div class="timeline-item">
                            <h6 class="fw-bold">Requisitos</h6>
                            <ul class="text-muted">
                                <li>Nivel {{ $torneo->categoria ?? 'General' }}</li>
                                <li>Equipo completo (2 jugadores)</li>
                                <li>Certificado médico (recomendado)</li>
                                <li>Cumplir con el reglamento del club</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Participantes -->
                <div class="info-card card mb-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Participantes Inscritos ({{ count($participantes) }})</h4>
                        @if(count($participantes) > 0)
                            <div class="row g-3">
                                @foreach($participantes as $participante)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="participant-avatar">
                                            {{ strtoupper(substr($participante->nombre ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $participante->nombre ?? 'Usuario' }}</h6>
                                            <small class="text-muted">
                                                Inscrito: {{ date('d/m/Y', strtotime($participante->fecha_inscripcion ?? now())) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-people display-4 text-muted"></i>
                                <p class="text-muted mt-2">Aún no hay participantes inscritos</p>
                                <p class="text-muted">¡Sé el primero en unirte!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna de Inscripción -->
            <div class="col-lg-4">
                <div class="info-card card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h5 class="fw-bold">Inscripción al Torneo</h5>
                            <div class="prize-amount">${{ number_format($torneo->premio ?? 500000, 0, ',', '.') }}</div>
                            <div class="text-muted">Premio total</div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Participantes:</span>
                                <span class="fw-bold">{{ $torneo->participantes_actuales ?? 0 }}/{{ $torneo->max_participantes ?? 16 }}</span>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" style="width: {{ (($torneo->participantes_actuales ?? 0) / ($torneo->max_participantes ?? 16)) * 100 }}%"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Categoría:</span>
                                <span class="fw-bold">{{ ucfirst($torneo->categoria ?? 'General') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Estado:</span>
                                <span class="badge bg-{{ $torneo->estado == 'abierto' ? 'success' : ($torneo->estado == 'en-curso' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($torneo->estado ?? 'Abierto') }}
                                </span>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($torneo->estado == 'abierto')
                            <form method="POST" action="{{ route('torneos.inscribir') }}">
                                @csrf
                                <input type="hidden" name="torneo_id" value="{{ $torneo->id }}">
                                
                                <button type="submit" class="btn btn-inscribir text-white w-100 mb-3">
                                    <i class="bi bi-trophy me-2"></i>Inscribirse al Torneo
                                </button>
                            </form>
                        @elseif($torneo->estado == 'en-curso')
                            <button type="button" class="btn btn-warning w-100 mb-3" disabled>
                                <i class="bi bi-play-circle me-2"></i>Torneo en Curso
                            </button>
                        @elseif($torneo->estado == 'cerrado')
                            <button type="button" class="btn btn-danger w-100 mb-3" disabled>
                                <i class="bi bi-x-circle me-2"></i>Inscripciones Cerradas
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary w-100 mb-3" disabled>
                                <i class="bi bi-flag me-2"></i>Torneo Finalizado
                            </button>
                        @endif

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Inscripción gratuita • Fecha límite: {{ date('d/m/Y', strtotime($torneo->fecha_limite_inscripcion ?? now())) }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="info-card card mt-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">¿Necesitas ayuda?</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span>+57 300 123 4567</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>torneos@hakipadel.com</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-whatsapp text-success me-2"></i>
                            <span>WhatsApp disponible 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-ocultar alertas después de 5 segundos
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('show')) {
            alert.classList.remove('show');
        }
    });
}, 5000);
</script>
@endsection