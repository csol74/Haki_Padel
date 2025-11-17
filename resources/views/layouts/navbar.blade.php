<!-- Navbar -->
<nav class="navbar navbar-expand-lg custom-navbar">
    <a class="navbar-brand text-white d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('images/logo_haki_padel.png') }}" alt="Logo Hakipadel" class="logo-navbar me-2">
        <div class="d-flex flex-column lh-1">
            <span class="fw-bold">Hakipadel</span>
            <small style="font-size: 0.8rem;">{{ Auth::user()->nombre ?? Auth::user()->name ?? 'Usuario' }}</small>
        </div>
    </a>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav text-white">
            <li class="nav-item me-4">
                <a class="nav-link {{ request()->routeIs('home') ? 'active text-success fw-bold' : 'text-white' }}"
                   href="{{ route('home') }}">Inicio</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link {{ request()->routeIs('canchas.index') ? 'active text-success fw-bold' : 'text-white' }}"
                   href="{{ route('canchas.index') }}">Canchas</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link {{ request()->routeIs('clases.*') ? 'active text-success fw-bold' : 'text-white' }}"
                   href="{{ route('clases.index') }}">Clases</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link {{ request()->routeIs('torneos.index') ? 'active text-success fw-bold' : 'text-white' }}"
                   href="{{ route('torneos.index') }}">Torneos</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link {{ request()->routeIs('contacto.index') ? 'active text-success fw-bold' : 'text-white' }}"
                   href="{{ route('contacto.index') }}">Contacto</a>
            </li>
        </ul>
    </div>

    <div class="d-flex align-items-center">

        {{-- 🔔 Notificaciones de reservas --}}
        @php
            use App\Models\Notificacion;
            $notificaciones = Notificacion::where('user_id', Auth::id())
                ->where('tipo', 'reserva')
                ->latest()
                ->take(5)
                ->get();

            $noLeidas = $notificaciones->where('leida', false)->count();
        @endphp

        <div class="dropdown me-3">
            <button class="btn btn-link text-white position-relative" id="dropdownNotificaciones"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    onclick="marcarNotificacionesLeidas()">
                <i class="bi bi-bell fs-4"></i>
                @if($noLeidas > 0)
                    <span id="notificacionesBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $noLeidas }}
                    </span>
                @endif
            </button>

            <ul class="dropdown-menu dropdown-menu-end p-2 notificaciones-dropdown"
                aria-labelledby="dropdownNotificaciones">
                <h6 class="dropdown-header">Notificaciones de Reservas</h6>
                <hr class="dropdown-divider">
                @forelse($notificaciones as $notif)
                    <li class="dropdown-item {{ $notif->leida ? 'text-muted' : 'fw-bold' }}">
                        <strong>{{ $notif->titulo }}</strong><br>
                        <small>{{ $notif->contenido }}</small><br>
                        <small class="text-secondary">{{ $notif->created_at->diffForHumans() }}</small>
                    </li>
                @empty
                    <li class="dropdown-item text-muted text-center">No tienes notificaciones</li>
                @endforelse
            </ul>
        </div>

        {{-- Usuario --}}
        <div class="dropdown">
            <button class="btn btn-success rounded-circle fw-bold text-uppercase" id="userDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="width:45px; height:45px;">
                {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name ?? 'U', 0, 1)) }}
                {{ strtoupper(substr(Auth::user()->lastname ?? '', 0, 1)) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Perfil</a></li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">Cerrar sesión</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Script para marcar notificaciones como leídas y ocultar el badge --}}
<script>
    function marcarNotificacionesLeidas() {
        const badge = document.getElementById('notificacionesBadge');
        if (badge) badge.style.display = 'none';

        fetch("{{ route('notificaciones.leer') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    }
</script>