@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/torneos.css') }}">
@endsection

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    @include('layouts.navbar')
    
    <div class="container py-4">
        <!-- Título -->
        <div class="text-center mb-4">
            <h1 class="page-title">Torneos de Pádel</h1>
            <p class="text-muted">Únete a nuestros emocionantes torneos y compite con los mejores</p>
        </div>

        <!-- Filtros -->
        <div class="card filters-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('torneos.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Categoría</label>
                            <select class="form-select" name="categoria">
                                <option value="">Todas las categorías</option>
                                <option value="principiante" {{ request('categoria') == 'principiante' ? 'selected' : '' }}>Principiante</option>
                                <option value="intermedio" {{ request('categoria') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="avanzado" {{ request('categoria') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                                <option value="profesional" {{ request('categoria') == 'profesional' ? 'selected' : '' }}>Profesional</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Estado</label>
                            <select class="form-select" name="estado">
                                <option value="">Todos los estados</option>
                                <option value="abierto" {{ request('estado') == 'abierto' ? 'selected' : '' }}>Abierto</option>
                                <option value="en-curso" {{ request('estado') == 'en-curso' ? 'selected' : '' }}>En curso</option>
                                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                <option value="cerrado" {{ request('estado') == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Desde fecha</label>
                            <input type="date" class="form-control" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                <i class="bi bi-search me-1"></i>Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grid de Torneos -->
        <div class="row g-4">
            @forelse($torneos as $torneo)
            <div class="col-lg-4 col-md-6">
                <div class="card tournament-card">
                    <div class="tournament-header">
                        <div class="tournament-category">{{ ucfirst($torneo->categoria ?? 'General') }}</div>
                        <div class="tournament-status status-{{ str_replace(' ', '-', strtolower($torneo->estado ?? 'abierto')) }}">
                            @if($torneo->estado == 'abierto')
                                <i class="bi bi-check-circle me-1"></i>Abierto
                            @elseif($torneo->estado == 'en-curso')
                                <i class="bi bi-play-circle me-1"></i>En Curso
                            @elseif($torneo->estado == 'cerrado')
                                <i class="bi bi-x-circle me-1"></i>Cerrado
                            @else
                                <i class="bi bi-flag me-1"></i>Finalizado
                            @endif
                        </div>
                        <i class="bi bi-trophy tournament-icon"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $torneo->nombre ?? 'Torneo de Pádel' }}</h5>
                        
                        <!-- Fecha del torneo -->
                        <div class="tournament-date">
                            <div class="date-day">{{ date('d', strtotime($torneo->fecha_inicio ?? now())) }}</div>
                            <div class="date-month">{{ date('M Y', strtotime($torneo->fecha_inicio ?? now())) }}</div>
                        </div>

                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $torneo->ubicacion ?? 'Hakipadel Club' }}
                        </p>

                        <!-- Información de participantes -->
                        <div class="participants-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Participantes</small>
                                    <div class="fw-bold">{{ $torneo->participantes_actuales ?? 0 }}/{{ $torneo->max_participantes ?? 16 }}</div>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Premio</small>
                                    <div class="prize-amount">${{ number_format($torneo->premio ?? 500000, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas importantes -->
                        <div class="mb-3">
                            <small class="text-muted d-block">
                                <i class="bi bi-calendar me-1"></i>
                                Inicio: {{ date('d/m/Y', strtotime($torneo->fecha_inicio ?? now())) }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-clock me-1"></i>
                                Inscripciones hasta: {{ date('d/m/Y', strtotime($torneo->fecha_limite_inscripcion ?? now())) }}
                            </small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('torneos.show', $torneo->id) }}" class="btn btn-outline-primary flex-fill">
                                <i class="bi bi-eye me-1"></i>Ver Detalles
                            </a>
                            @if($torneo->estado == 'abierto')
                                <button class="btn btn-inscribir text-white flex-fill" data-bs-toggle="modal" data-bs-target="#inscripcionModal{{ $torneo->id }}">
                                    <i class="bi bi-trophy me-1"></i>Inscribirse
                                </button>
                            @else
                                <button class="btn btn-secondary flex-fill" disabled>
                                    @if($torneo->estado == 'cerrado')
                                        <i class="bi bi-x-circle me-1"></i>Cerrado
                                    @elseif($torneo->estado == 'en-curso')
                                        <i class="bi bi-play-circle me-1"></i>En Curso
                                    @else
                                        <i class="bi bi-flag me-1"></i>Finalizado
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Inscripción -->
            @if($torneo->estado == 'abierto')
            <div class="modal fade" id="inscripcionModal{{ $torneo->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inscribirse al Torneo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('torneos.inscribir') }}">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="torneo_id" value="{{ $torneo->id }}">
                                <h6 class="fw-bold">{{ $torneo->nombre }}</h6>
                                <p class="text-muted">{{ $torneo->descripcion ?? 'Torneo de pádel emocionante con grandes premios.' }}</p>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Información importante:</strong><br>
                                    • La inscripción es gratuita<br>
                                    • Fecha límite: {{ date('d/m/Y', strtotime($torneo->fecha_limite_inscripcion ?? now())) }}<br>
                                    • Premio: ${{ number_format($torneo->premio ?? 500000, 0, ',', '.') }}<br>
                                    • Categoría: {{ ucfirst($torneo->categoria ?? 'General') }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-trophy me-1"></i>Confirmar Inscripción
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-trophy display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No se encontraron torneos</h4>
                    <p class="text-muted">Intenta modificar los filtros de búsqueda o vuelve más tarde</p>
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
                            <i class="bi bi-info-circle me-2"></i>Información de Torneos
                        </h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Inscripción gratuita para todos los torneos</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Premios en efectivo para los ganadores</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Categorías para todos los niveles</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Arbitraje profesional incluido</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 bg-white shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="bi bi-trophy me-2"></i>Próximos Eventos
                        </h5>
                        <div class="timeline-item">
                            <h6 class="fw-bold">Torneo Mensual</h6>
                            <small class="text-muted">Cada primer sábado del mes</small>
                        </div>
                        <div class="timeline-item">
                            <h6 class="fw-bold">Copa Hakipadel</h6>
                            <small class="text-muted">Torneo anual - Diciembre 2024</small>
                        </div>
                        <div class="alert alert-success mt-3">
                            <i class="bi bi-whatsapp me-2"></i>
                            <strong>WhatsApp:</strong> +57 300 123 4567
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
// Mostrar alertas de éxito/error
@if(session('success'))
    alert('{{ session("success") }}');
@endif

@if(session('error'))
    alert('{{ session("error") }}');
@endif
</script>
@endsection