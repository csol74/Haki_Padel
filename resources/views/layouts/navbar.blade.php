<!-- Navbar -->
<nav class="navbar navbar-expand-lg" style="background-color:#435A5F; padding:15px 50px;">
    <a class="navbar-brand text-white d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('images/logo_haki_padel.png') }}" alt="Logo de Hakipadel" width="40" height="40" class="me-2">
        <div class="d-flex flex-column lh-1">
            <span class="fw-bold">Hakipadel</span>
            <small style="font-size: 0.8rem;">{{ Auth::user()->nombre ?? Auth::user()->name ?? 'Usuario' }}</small>
        </div>
    </a>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav text-white">
            <li class="nav-item me-4">
                <a class="nav-link active text-success fw-bold" href="{{ route('home') }}">Inicio</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link text-white" href="{{ route('canchas.index') }}">Canchas</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link text-white" href="{{ route('torneos.index') }}">Torneos</a>
            </li>
            <li class="nav-item me-4">
                <a class="nav-link text-white" href="{{ route('contacto.index') }}">Contacto</a>
            </li>
        </ul>
    </div>

    <div class="d-flex align-items-center">
        <button class="btn btn-link text-white me-3">
            <i class="bi bi-bell fs-4"></i>
        </button>
        <div class="dropdown">
            <button class="btn btn-success rounded-circle fw-bold text-uppercase" id="userDropdown"
                data-bs-toggle="dropdown" aria-expanded="false"
                style="width:45px; height:45px;">
                {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? '', 0, 1)) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#">Perfil</a></li>
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