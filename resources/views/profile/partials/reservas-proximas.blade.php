{{-- Mis Próximas Reservas (Completadas) --}}
@php
    $reservasCompletadas = Auth::user()->reservas()
        ->where('estado', 'completada')
        ->where('fecha', '>=', now()->format('Y-m-d'))
        ->with('cancha')
        ->orderBy('fecha', 'asc')
        ->orderBy('hora_inicio', 'asc')
        ->get();
@endphp

<div class="section-card">
    <h2 class="section-title">📅 Mis Próximas Reservas ({{ $reservasCompletadas->count() }})</h2>

    <div class="reservas-list">
        @forelse($reservasCompletadas as $reserva)
        <div class="reserva-item">
            <div class="reserva-info">
                <span class="reserva-icon">🎾</span>
                <div class="reserva-details">
                    <h4>{{ $reserva->cancha->nombre ?? 'Cancha' }}</h4>
                    <p class="reserva-meta">
                        📅 {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} •
                        🕐 {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}
                    </p>
                    <small class="text-muted">
                        👥 {{ $reserva->numero_jugadores }} jugadores
                    </small>
                </div>
            </div>
            <div class="reserva-right">
                <span class="reserva-status status-confirmada">✓ Confirmada</span>
                <div class="reserva-actions">
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
        @empty
        <div class="text-center py-4 text-muted">
            <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
            <p class="mt-2">No tienes reservas próximas</p>
            <a href="{{ route('canchas.index') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle me-2"></i>Hacer una Reserva
            </a>
        </div>
        @endforelse
    </div>
</div>
