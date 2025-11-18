<nav class="navbar navbar-expand-lg navbar-dark admin-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-trophy me-2"></i>Hakipadel
            <span class="admin-badge">ADMIN</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}" href="{{ route('admin.usuarios') }}">
                        <i class="bi bi-people me-1"></i>Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reservas') ? 'active' : '' }}" href="{{ route('admin.reservas') }}">
                        <i class="bi bi-calendar-check me-1"></i>Reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.torneos') ? 'active' : '' }}" href="{{ route('admin.torneos') }}">
                        <i class="bi bi-trophy me-1"></i>Torneos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.membresias.*') ? 'active' : '' }} position-relative"
                       href="{{ route('admin.membresias.index') }}">
                        <i class="bi bi-star me-1"></i>Membresías
                        @php
                            $solicitudesPendientes = \App\Models\SolicitudMembresia::where('estado', 'pendiente')->count();
                        @endphp
                        @if($solicitudesPendientes > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $solicitudesPendientes }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reportes') ? 'active' : '' }}" href="{{ route('admin.reportes') }}">
                        <i class="bi bi-graph-up me-1"></i>Reportes
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                {{-- Notificaciones de Admin --}}
                @php
                    use App\Models\Notificacion;
                    $notificacionesAdmin = Notificacion::where('user_id', Auth::id())
                        ->where('tipo', 'membresia')
                        ->latest()
                        ->take(5)
                        ->get();
                    $noLeidasAdmin = $notificacionesAdmin->where('leida', false)->count();
                @endphp

                <li class="nav-item dropdown me-2">
                    <a class="nav-link position-relative" href="#" id="notificacionesDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false"
                       onclick="marcarNotificacionesAdmin()">
                        <i class="bi bi-bell fs-5"></i>
                        @if($noLeidasAdmin > 0)
                            <span id="badgeNotificacionesAdmin" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $noLeidasAdmin }}
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificacionesDropdown" style="min-width: 300px;">
                        <li><h6 class="dropdown-header">Notificaciones</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        @forelse($notificacionesAdmin as $notif)
                            <li>
                                <a class="dropdown-item {{ $notif->leida ? '' : 'fw-bold bg-light' }}"
                                   href="{{ route('admin.membresias.index') }}">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <strong>{{ $notif->titulo }}</strong><br>
                                    <small>{{ $notif->contenido }}</small><br>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </a>
                            </li>
                        @empty
                            <li class="dropdown-item text-muted text-center">No hay notificaciones</li>
                        @endforelse
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Ver Sitio
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
function marcarNotificacionesAdmin() {
    const badge = document.getElementById('badgeNotificacionesAdmin');
    if (badge) badge.style.display = 'none';

    fetch("{{ route('notificaciones.leer') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
}
</script>
