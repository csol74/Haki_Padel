@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/canchas.css') }}">
@endsection
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    @include('layouts.navbar')

    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-custom">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('canchas.index') }}" class="text-decoration-none">Canchas</a></li>
                <li class="breadcrumb-item active">{{ $cancha->nombre }}</li>
            </ol>
        </nav>

        <!-- Hero de la Cancha -->
        <div class="court-hero">
            <div class="court-grid-bg"></div>
            <div class="status-badge">
                @if($cancha->estado == 'disponible')
                    <i class="bi bi-check-circle me-1"></i>Disponible
                @elseif($cancha->estado == 'ocupada')
                    <i class="bi bi-clock me-1"></i>Ocupada
                @else
                    <i class="bi bi-tools me-1"></i>Mantenimiento
                @endif
            </div>
            <div class="court-info-overlay">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <h1 class="fw-bold mb-2">{{ $cancha->nombre }} #{{ $cancha->numero }}</h1>
                        <p class="mb-1 opacity-75">
                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $cancha->ubicacion }} - {{ $cancha->descripcion }}
                        </p>
                        <div class="d-flex gap-3">
                            <span><i class="bi bi-people-fill me-1"></i>Hasta {{ $cancha->capacidad }} jugadores</span>
                            <span><i class="bi bi-clock me-1"></i>Disponible {{ $cancha->horario_inicio ?? '6:00 AM' }} - {{ $cancha->horario_fin ?? '10:00 PM' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="price-highlight text-white">${{ number_format($cancha->precio_hora, 0, ',', '.') }}</div>
                        <div class="opacity-75">por hora</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna de Información -->
            <div class="col-lg-8">
                <!-- Características -->
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Características de la Cancha</h4>
                        <div class="row g-4">
                            @if($cancha->servicios)
                                @php
                                    $servicios = explode(',', $cancha->servicios);
                                    $iconos = [
                                        'Iluminación LED' => 'brightness-high text-warning',
                                        'Wi-Fi' => 'wifi text-primary',
                                        'Hidratación' => 'droplet text-info',
                                        'Parking' => 'car-front text-warning',
                                        'Vestuarios' => 'house text-success',
                                        'Duchas' => 'water text-info',
                                        'Aire Acondicionado' => 'wind text-primary',
                                        'Cafetería' => 'cup-hot text-warning',
                                    ];
                                @endphp
                                @foreach($servicios as $servicio)
                                    @php $servicio = trim($servicio); @endphp
                                    <div class="col-md-4 col-6">
                                        <div class="text-center">
                                            <div class="feature-icon">
                                                <i class="bi bi-{{ $iconos[$servicio] ?? 'check-circle text-success' }}"></i>
                                            </div>
                                            <h6 class="fw-bold">{{ $servicio }}</h6>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Horarios Disponibles -->
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Horarios Disponibles para Hoy</h4>
                        <div class="row g-2" id="horariosContainer">
                            @php
                                $horarios = [
                                    '06:00-08:00' => 'Disponible',
                                    '08:00-10:00' => 'Disponible',
                                    '10:00-12:00' => 'Ocupado',
                                    '12:00-14:00' => 'Ocupado',
                                    '14:00-16:00' => 'Disponible',
                                    '16:00-18:00' => 'Disponible',
                                    '18:00-20:00' => 'Disponible',
                                    '20:00-22:00' => 'Disponible'
                                ];
                            @endphp

                            @foreach($horarios as $horario => $estado)
                            <div class="col-md-4 col-6">
                                <div class="time-slot {{ strtolower($estado) == 'disponible' ? 'available' : 'occupied' }}"
                                     onclick="{{ strtolower($estado) == 'disponible' ? 'selectTime(this)' : '' }}"
                                     data-horario="{{ $horario }}">
                                    <div class="fw-bold">{{ str_replace('-', ' - ', $horario) }}</div>
                                    <small class="text-{{ strtolower($estado) == 'disponible' ? 'success' : 'danger' }}">{{ $estado }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna de Reserva -->
            <div class="col-lg-4">
                <div class="price-card card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="price-highlight">${{ number_format($cancha->precio_hora, 0, ',', '.') }}</div>
                            <div class="text-muted">por hora</div>
                        </div>

                        <form method="POST" action="{{ route('reservas.store') }}">
                            @csrf
                            <input type="hidden" name="cancha_id" value="{{ $cancha->id }}">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha</label>
                                <input type="date" class="form-control" name="fecha" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Horario Seleccionado</label>
                                <input type="text" class="form-control" id="selectedTime" name="horario" placeholder="Selecciona un horario" readonly required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Número de Jugadores</label>
                                <select class="form-select" name="jugadores" required>
                                    <option value="2">2 jugadores</option>
                                    <option value="3">3 jugadores</option>
                                    <option value="4" selected>4 jugadores</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Servicios Adicionales</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="coach" name="servicios[]" value="entrenador">
                                    <label class="form-check-label" for="coach">
                                        Entrenador personal (+$30.000)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="recording" name="servicios[]" value="grabacion">
                                    <label class="form-check-label" for="recording">
                                        Grabación del partido (+$15.000)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="refreshments" name="servicios[]" value="refrigerios">
                                    <label class="form-check-label" for="refreshments">
                                        Refrigerios (+$20.000)
                                    </label>
                                </div>
                            </div>

                            <!-- Resumen de Precios -->
                            <div class="border-top pt-3 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Alquiler de cancha (2 horas)</span>
                                    <span>${{ number_format($cancha->precio_hora * 2, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Servicios adicionales</span>
                                    <span id="additionalCosts">$0</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold fs-5 text-primary">
                                    <span>Total</span>
                                    <span id="totalCost">${{ number_format($cancha->precio_hora * 2, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if($cancha->estado == 'disponible')
                                <button type="submit" class="btn btn-reservar text-white w-100 mb-3">
                                    <i class="bi bi-calendar-check me-2"></i>Confirmar Reserva
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary w-100 mb-3" disabled>
                                    <i class="bi bi-x-circle me-2"></i>No Disponible
                                </button>
                            @endif

                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Reserva segura • Cancelación gratuita hasta 1 hora antes
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="card border-0 mt-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">¿Necesitas ayuda?</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span>+57 300 123 4567</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>reservas@hakipadel.com</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-chat-dots text-primary me-2"></i>
                            <span>Chat en línea</span>
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
    let selectedTimeSlot = null;
    const baseCost = {{ $cancha->precio_hora * 2 }};

    function selectTime(element) {
        // Remover selección anterior
        document.querySelectorAll('.time-slot.selected').forEach(slot => {
            slot.classList.remove('selected');
        });

        // Agregar selección actual
        element.classList.add('selected');
        selectedTimeSlot = element.getAttribute('data-horario');

        // Actualizar campo de horario
        document.getElementById('selectedTime').value = selectedTimeSlot;
    }

    // Cálculo de costos adicionales
    function updateCosts() {
        let additionalCost = 0;

        if (document.getElementById('coach').checked) additionalCost += 30000;
        if (document.getElementById('recording').checked) additionalCost += 15000;
        if (document.getElementById('refreshments').checked) additionalCost += 20000;

        document.getElementById('additionalCosts').textContent = '$' + additionalCost.toLocaleString('es-CO');
        document.getElementById('totalCost').textContent = '$' + (baseCost + additionalCost).toLocaleString('es-CO');
    }

    // Agregar listeners para checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateCosts);
    });

    // Validar formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!selectedTimeSlot) {
            e.preventDefault();
            alert('Por favor selecciona un horario');
            return false;
        }
    });
</script>
@endsection
