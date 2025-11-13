{{-- Historial de Pagos --}}
<div class="section-card">
    <h2 class="section-title">💳 Historial de Pagos</h2>

    @php
        $pagos = Auth::user()->pagos()->latest()->take(10)->get();
    @endphp

    @forelse($pagos as $pago)
    <div class="reserva-item">
        <div class="reserva-info">
            <span class="reserva-icon">💰</span>
            <div class="reserva-details">
                <h4>{{ ucfirst($pago->concepto) }} - ${{ number_format($pago->monto, 0, ',', '.') }} COP</h4>
                <p class="reserva-meta">
                    {{ $pago->created_at->format('d/m/Y H:i') }} •
                    Método: {{ ucfirst($pago->metodo_pago) }}
                </p>
            </div>
        </div>
        <span class="reserva-status {{ $pago->estado === 'completado' ? 'status-confirmada' : 'status-pendiente' }}">
            {{ ucfirst($pago->estado) }}
        </span>
    </div>
    @empty
    <div class="text-center py-4 text-muted">
        <i class="bi bi-wallet2" style="font-size: 3rem;"></i>
        <p class="mt-2">No tienes pagos registrados</p>
    </div>
    @endforelse
</div>
