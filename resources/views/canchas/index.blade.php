@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/canchas.css') }}">
@endsection

@section('content')
    <div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
        @include('layouts.navbar')

        <div class="container py-4">
            <!-- Título -->
            <div class="text-center mb-4">
                <h1 class="page-title">Nuestras Canchas de Pádel</h1>
                <p class="text-muted">Selecciona la cancha perfecta para tu juego</p>
            </div>

            <!-- Filtros -->
            <div class="card filters-card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('canchas.index') }}">
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
                                    <option value="06:00-08:00" {{ request('hora') == '06:00-08:00' ? 'selected' : '' }}>
                                        06:00 - 08:00</option>
                                    <option value="08:00-10:00" {{ request('hora') == '08:00-10:00' ? 'selected' : '' }}>
                                        08:00 - 10:00</option>
                                    <option value="10:00-12:00" {{ request('hora') == '10:00-12:00' ? 'selected' : '' }}>
                                        10:00 - 12:00</option>
                                    <option value="12:00-14:00" {{ request('hora') == '12:00-14:00' ? 'selected' : '' }}>
                                        12:00 - 14:00</option>
                                    <option value="14:00-16:00" {{ request('hora') == '14:00-16:00' ? 'selected' : '' }}>
                                        14:00 - 16:00</option>
                                    <option value="16:00-18:00" {{ request('hora') == '16:00-18:00' ? 'selected' : '' }}>
                                        16:00 - 18:00</option>
                                    <option value="18:00-20:00" {{ request('hora') == '18:00-20:00' ? 'selected' : '' }}>
                                        18:00 - 20:00</option>
                                    <option value="20:00-22:00" {{ request('hora') == '20:00-22:00' ? 'selected' : '' }}>
                                        20:00 - 22:00</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tipo</label>
                                <select class="form-select" name="tipo">
                                    <option value="">Todas</option>
                                    <option value="premium" {{ request('tipo') == 'premium' ? 'selected' : '' }}>Premium
                                    </option>
                                    <option value="estandar" {{ request('tipo') == 'estandar' ? 'selected' : '' }}>Estándar
                                    </option>
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

            <!-- Grid de Canchas -->
            <div class="row g-4">
                @forelse($canchas as $cancha)
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-cancha">
                            <div class="cancha-image">
                                <div
                                    class="status-badge status-{{ $cancha->estado == 'activa' ? 'disponible' : 'mantenimiento' }}">
                                    @if ($cancha->estado == 'activa')
                                        <i class="bi bi-check-circle me-1"></i>Disponible
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



                                @if ($cancha->estado == 'disponible')
                                    <a href="{{ route('canchas.show', $cancha->id) }}"
                                        class="btn btn-reservar text-white w-100">
                                        <i class="bi bi-calendar-check me-1"></i>Reservar Ahora
                                    </a>
                                @elseif($cancha->estado == 'ocupada')
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="bi bi-clock me-1"></i>Ocupada hasta
                                        {{ $cancha->ocupada_hasta ?? '18:00' }}
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

            <!-- Información adicional -->
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>Información de Reservas
                            </h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Reservas con 2 horas
                                    de anticipación</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Cancelación gratuita
                                    hasta 1 hora antes</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Equipamiento incluido
                                    (raquetas y pelotas)</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Vestuarios y duchas
                                    disponibles</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-clock me-2"></i>Horarios de Atención
                            </h5>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1 fw-bold">Lunes - Viernes</p>
                                    <p class="text-muted">6:00 AM - 10:00 PM</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1 fw-bold">Sábados - Domingos</p>
                                    <p class="text-muted">7:00 AM - 9:00 PM</p>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-telephone me-2"></i>
                                <strong>Contacto:</strong> +57 300 123 4567
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
        // Cualquier JavaScript adicional si es necesario
    </script>
@endsection
