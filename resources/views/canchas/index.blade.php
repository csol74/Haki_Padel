@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/canchas.css') }}">
@endsection

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">

        <div class="container py-4">
            <!-- Título -->
            <div class="text-center mb-4">
                <h1 class="page-title">Nuestras Canchas de Pádel</h1>
                <p class="text-muted">Selecciona la cancha perfecta para tu juego</p>
            </div>

            <!-- Filtros -->
            <div class="card filters-card mb-4">
                <div class="card-body">

                    <form id="formBusqueda" method="GET" action="{{ route('canchas.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Fecha</label>
                                <input type="date" class="form-control" name="fecha"
                                    value="{{ request('fecha', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Hora</label>
                                <select class="form-select" name="hora">
                                    <option value="">Todas las horas</option>
                                    @foreach ([
                                        '06:00-08:00',
                                        '08:00-10:00',
                                        '10:00-12:00',
                                        '12:00-14:00',
                                        '14:00-16:00',
                                        '16:00-18:00',
                                        '18:00-20:00',
                                        '20:00-22:00',
                                    ] as $rango)
                                        <option value="{{ $rango }}" {{ request('hora') == $rango ? 'selected' : '' }}>
                                            {{ $rango }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tipo</label>
                                <select class="form-select" name="tipo">
                                    <option value="Todas" {{ request('tipo') == 'Todas' ? 'selected' : '' }}>Todas</option>
                                    <option value="premium" {{ request('tipo') == 'premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="estandar" {{ request('tipo') == 'estandar' ? 'selected' : '' }}>Estándar</option>
                                    <option value="vip" {{ request('tipo') == 'vip' ? 'selected' : '' }}>VIP</option>
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
            </div>

            <!-- Mostrar mensaje solo si se realizó una búsqueda -->
            @if(request()->has('fecha') || request()->has('hora') || request()->has('tipo'))
                @if (isset($mensaje))
                    @if (Str::contains($mensaje, '🎾'))
                        <div class="alert alert-success text-center fw-bold mt-3">{{ $mensaje }}</div>
                    @elseif (Str::contains($mensaje, '⚠️'))
                        <div class="alert alert-warning text-center fw-bold mt-3">{{ $mensaje }}</div>
                    @else
                        <div class="alert alert-info text-center fw-bold mt-3">{{ $mensaje }}</div>
                    @endif
                @endif

                <!-- Grid de Canchas -->
                <div class="row g-4">
                    @forelse($canchas as $cancha)
                        <div class="col-lg-4 col-md-6">
                            <div class="card card-cancha">
                                <div class="cancha-image">
                                    <div
                                        class="status-badge status-{{ $cancha->estado == 'disponible' ? 'disponible' : ($cancha->estado == 'ocupada' ? 'ocupada' : 'mantenimiento') }}">
                                        @if ($cancha->estado == 'disponible')
                                            <i class="bi bi-check-circle me-1"></i>Disponible
                                        @elseif ($cancha->estado == 'ocupada')
                                            <i class="bi bi-x-circle me-1"></i>Ocupada
                                        @else
                                            <i class="bi bi-tools me-1"></i>Mantenimiento
                                        @endif
                                    </div>
                                    <div class="cancha-number">Cancha #{{ $cancha->numero }}</div>
                                    <div class="court-grid"></div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $cancha->nombre }}</h5>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-tag-fill text-info me-1"></i>Cancha {{ ucfirst($cancha->tipo) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="price">${{ number_format($cancha->precio_hora, 0, ',', '.') }}/hora</div>
                                        <div class="text-muted small">
                                            <i class="bi bi-building me-1"></i>{{ ucfirst($cancha->tipo) }}
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    @if ($cancha->estado === 'disponible')
                                        <form action="{{ route('reservas.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="cancha_id" value="{{ $cancha->id }}">
                                            <input type="hidden" name="fecha" value="{{ request('fecha') }}">
                                            <input type="hidden" name="horario" value="{{ request('hora') }}">
                                            <input type="hidden" name="jugadores" value="4">
                                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                                <i class="bi bi-calendar-check me-1"></i>Reservar Ahora
                                            </button>
                                        </form>
                                    @elseif($cancha->estado === 'ocupada')
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="bi bi-clock me-1"></i>Ocupada
                                        </button>
                                    @else
                                        <button class="btn btn-warning w-100" disabled>
                                            <i class="bi bi-tools me-1"></i>En Mantenimiento
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-search display-1 text-muted"></i>
                                <h4 class="text-muted mt-3">No se encontraron canchas</h4>
                                <p class="text-muted">Intenta modificar los filtros de búsqueda</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
        <!-- Información cuando no hay búsqueda -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 bg-white text-center py-5">
                            <div class="card-body">
                                <i class="bi bi-calendar-check display-1 text-primary mb-3"></i>
                                <h4 class="fw-bold text-primary mb-3">Encuentra tu Cancha Ideal</h4>
                                <p class="text-muted mb-4">
                                    Utiliza los filtros de arriba para buscar canchas disponibles según tu fecha, hora y preferencias.
                                </p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <i class="bi bi-filter-circle text-success display-6 mb-2"></i>
                                        <h6>Filtra por Fecha</h6>
                                        <small class="text-muted">Selecciona el día que prefieras jugar</small>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="bi bi-clock text-warning display-6 mb-2"></i>
                                        <h6>Elige el Horario</h6>
                                        <small class="text-muted">Desde las 6:00 AM hasta las 10:00 PM</small>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="bi bi-star text-info display-6 mb-2"></i>
                                        <h6>Selecciona Tipo</h6>
                                        <small class="text-muted">Estándar, Premium o VIP</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    </div>

@endsection
