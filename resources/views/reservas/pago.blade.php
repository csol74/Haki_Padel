@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pago.css') }}">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center py-3">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2"></i>Confirmar Pago de Reserva</h3>
                </div>

                <div class="card-body p-4">
                    <!-- Información de la reserva -->
                    <div class="reserva-info mb-4">
                        <h5 class="fw-bold text-primary mb-3">Detalles de tu Reserva</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-geo-alt me-2"></i>Cancha:</strong> {{ $reserva->cancha->nombre ?? 'Cancha #' . $reserva->id_cancha }}</p>
                                <p><strong><i class="bi bi-calendar me-2"></i>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</p>
                                <p><strong><i class="bi bi-clock me-2"></i>Horario:</strong> {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-people me-2"></i>Jugadores:</strong> {{ $reserva->numero_jugadores }}</p>
                                <p><strong><i class="bi bi-clock-history me-2"></i>Duración:</strong> {{ $reserva->duracion_horas }} horas</p>
                                <p>
                                    <strong><i class="bi bi-person-badge me-2"></i>Estado:</strong>
                                    <span class="badge bg-warning text-dark">Pendiente de pago</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de pago -->
                    <div class="payment-summary bg-light p-4 rounded mb-4">
                        <h5 class="fw-bold text-primary mb-3">Resumen de Pago</h5>

                        <div class="row">
                            <div class="col-8">
                                <p class="mb-2">Precio base ({{ $reserva->duracion_horas }} horas):</p>
                                @if($reserva->descuento > 0)
                                    <p class="mb-2 text-success">Descuento Socio (20%):</p>
                                    <p class="mb-0 fw-bold">Total a pagar:</p>
                                @else
                                    <p class="mb-0 fw-bold">Total a pagar:</p>
                                @endif
                            </div>
                            <div class="col-4 text-end">
                                <p class="mb-2">${{ number_format($reserva->precio_base, 0, ',', '.') }}</p>
                                @if($reserva->descuento > 0)
                                    <p class="mb-2 text-success">-${{ number_format($reserva->descuento, 0, ',', '.') }}</p>
                                    <p class="mb-0 fw-bold text-success fs-5">${{ number_format($reserva->precio_final, 0, ',', '.') }}</p>
                                @else
                                    <p class="mb-0 fw-bold text-success fs-5">${{ number_format($reserva->precio_final, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Badge de socio si aplica descuento -->
                        @if($reserva->descuento > 0)
                            <div class="mt-3">
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i>Descuento Socio Aplicado (20%)
                                </span>
                                <p class="small text-muted mt-1 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Como socio, disfrutas de un 20% de descuento en todas las reservas.
                                </p>
                            </div>
                        @else
                            <div class="mt-3">
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Conviértete en socio y disfruta de un 20% de descuento en todas tus reservas.
                                    <a href="{{ route('membresia.index') }}" class="text-primary">Más información</a>
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Información del usuario -->
                    <div class="user-info bg-white p-3 rounded border mb-4">
                        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-person me-2"></i>Información del Usuario</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>Tipo de usuario:</strong>
                                    @if(Auth::user()->role === 'socio')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-star-fill me-1"></i>Socio
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-person me-1"></i>Cliente
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        {{-- Botón de pagar con MercadoPago --}}
                        <a href="{{ route('mercadopago.preference', ['reserva' => $reserva->id]) }}"
                           class="btn btn-success btn-lg px-4 py-2 fw-semibold">
                            <i class="bi bi-credit-card me-2"></i>Pagar con Mercado Pago
                        </a>

                        {{-- Botón de cancelar --}}
                        <form action="{{ route('reservas.cancelar', $reserva->id) }}" method="POST"
                              onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-lg px-4 py-2 fw-semibold">
                                <i class="bi bi-x-circle me-2"></i>Cancelar Reserva
                            </button>
                        </form>
                    </div>

                    <!-- Información adicional -->
                    <div class="mt-4 text-center">
                        <p class="text-muted small mb-2">
                            <i class="bi bi-clock me-1"></i>
                            Tienes 5 minutos para completar el pago antes de que la reserva expire.
                        </p>
                        <p class="text-muted small">
                            <i class="bi bi-shield-check me-1"></i>
                            Pago seguro procesado por Mercado Pago
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.reserva-info {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}
.payment-summary {
    border-left: 4px solid #28a745;
}
.user-info {
    border-left: 4px solid #007bff;
}
.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    transition: all 0.3s ease;
}
.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
}
</style>
@endsection
