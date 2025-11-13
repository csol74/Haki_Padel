{{-- Reservas Pendientes de Confirmación --}}
@php
    $reservasPendientes = Auth::user()->reservas()
        ->where('estado', 'pendiente')
        ->where('created_at', '>=', now()->subMinutes(5))
        ->with('cancha')
        ->latest()
        ->get();
@endphp

@if($reservasPendientes->count() > 0)
<div class="section-card reservas-pendientes-card">
    <h2 class="section-title reservas-pendientes-title">
        ⚠️ Reservas Pendientes de Confirmación ({{ $reservasPendientes->count() }})
    </h2>

    <div class="alert alert-warning mb-3">
        <strong>
            <i class="bi bi-info-circle-fill me-2"></i>Acción Requerida:
        </strong>
        Si ya completaste el pago en MercadoPago, haz clic en "Confirmar Pago" para activar tu reserva.
        <br>
        <small class="text-danger">
            <i class="bi bi-clock-fill me-1"></i>
            Las reservas sin confirmar se eliminarán automáticamente después de 5 minutos.
        </small>
    </div>

    <div class="reservas-list">
        @foreach($reservasPendientes as $reserva)
        <div class="reserva-item reserva-pendiente-item">
            <div class="reserva-info">
                <span class="reserva-icon">⏳</span>
                <div class="reserva-details">
                    <h4>{{ $reserva->cancha->nombre ?? 'Cancha #'.$reserva->id_cancha }}</h4>
                    <p class="reserva-meta">
                        📅 {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} •
                        🕐 {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}
                    </p>
                    <small class="text-muted">
                        Creada: {{ $reserva->created_at->diffForHumans() }}
                    </small>
                    <br>
                    <small class="text-danger">
                        <i class="bi bi-alarm me-1"></i>
                        Expira en: <span class="countdown" data-created="{{ $reserva->created_at }}"></span>
                    </small>
                </div>
            </div>
            <div class="reserva-right">
                <span class="reserva-status status-pendiente">⏳ Pago Pendiente</span>
                <div class="reserva-actions">
                    <a href="{{ route('mercadopago.confirmar', ['reserva_id' => $reserva->id]) }}"
                       class="btn-action btn-confirmar">
                        ✓ Confirmar Pago
                    </a>
                    <form action="{{ route('reservas.cancelar', $reserva->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-cancelar">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
