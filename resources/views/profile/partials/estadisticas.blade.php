{{-- Estadísticas --}}
<div class="section-card">
    <h2 class="section-title">📊 Mis Estadísticas</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon">🎾</div>
            <div class="stat-card-value">{{ $stats['matches_played'] }}</div>
            <div class="stat-card-label">Partidos Jugados</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">🏆</div>
            <div class="stat-card-value">{{ $stats['tournaments'] }}</div>
            <div class="stat-card-label">Torneos</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon">⭐</div>
            <div class="stat-card-value">0</div>
            <div class="stat-card-label">Victorias</div>
        </div>
    </div>

    <div class="text-center py-4 text-muted">
        <p>Tus estadísticas se actualizarán a medida que juegues más partidos</p>
    </div>
</div>
