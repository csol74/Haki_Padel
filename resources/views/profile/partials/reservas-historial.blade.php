{{-- Historial de Reservas --}}
@php
    $reservasHistoricas = Auth::user()->reservas()
        ->where('estado', 'completada')
        ->where('fecha', '<', now()->format('Y-m-d'))
        ->with('cancha')
        ->orderBy('fecha', 'desc')
        ->limit(10)
        ->get();
@endphp

<div class="section-card">
    <h2 class="section-title">📋 Historial de Reservas</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon">🎾</div>
            <div class="stat-card-value">{{ $stats['total_reservations'] }}</div>
            <div class="stat-card-label">Total Reservas</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">✓</div>
            <div class="stat-card-value">{{ $stats['completed_reservations'] }}</div>
            <div class="stat-card-label">Completadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">✗</div>
            <div class="stat-card-value">{{ $stats['cancelled_reservations'] }}</div>
            <div class="stat-card-label">Canceladas</div>
        </div>
    </div>

    <div class="reservas-list">
        @forelse($reservasHistoricas as $reserva)
        <div class="reserva-item reserva-historica">
            <div class="reserva-info">
                <span class="reserva-icon">🎾</span>
                <div class="reserva-details">
                    <h4>{{ $reserva->cancha->nombre ?? 'Cancha' }}</h4>
                    <p class="reserva-meta">
                        📅 {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} •
                        🕐 {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}
                    </p>
                </div>
            </div>
            <span class="reserva-status status-confirmada">✓ Completada</span>
        </div>
        @empty
        <p class="text-center text-muted py-3">No tienes reservas anteriores</p>
        @endforelse
    </div>
</div>
