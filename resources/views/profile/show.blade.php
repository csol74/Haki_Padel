@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@endsection

@section('content')
<div class="container-fluid perfil-container">

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <div class="header-container">
                <div class="profile-avatar-large">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="profile-info">
                    <h1>{{ $user->name }}</h1>
                    <p class="profile-meta">
                        📧 {{ $user->email }}
                        {{-- Ocultamos el estado de verificación si no es necesario --}}
                    </p>
                    <div class="profile-stats">
                        <div class="stat-box">
                            <div class="stat-value">{{ $stats['matches_played'] }}</div>
                            <div class="stat-label">Partidos Jugados</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value">{{ $stats['tournaments'] }}</div>
                            <div class="stat-label">Torneos</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value">Desde {{ $stats['member_since'] }}</div>
                            <div class="stat-label">Miembro</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="content-grid">

            <!-- Sidebar Menu -->
            <aside class="sidebar-menu">
                <div class="menu-item active" data-section="reservas">
                    <span class="menu-icon">📅</span>
                    <span>Mis Reservas</span>
                </div>
                <div class="menu-item" data-section="torneos">
                    <span class="menu-icon">🏆</span>
                    <span>Mis Torneos</span>
                </div>
                <div class="menu-item" data-section="estadisticas">
                    <span class="menu-icon">📊</span>
                    <span>Estadísticas</span>
                </div>
                <div class="menu-item" data-section="informacion">
                    <span class="menu-icon">👤</span>
                    <span>Mi Información</span>
                </div>
                <div class="menu-item" data-section="pagos">
                    <span class="menu-icon">💳</span>
                    <span>Pagos</span>
                </div>
                <div class="menu-item" data-section="configuracion">
                    <span class="menu-icon">⚙️</span>
                    <span>Configuración</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-item menu-logout">
                        <span class="menu-icon">🚪</span>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </aside>

            <!-- Content Area -->
            <div class="content-area">
                {{-- Sección: Mis Reservas --}}
                <div id="section-reservas" class="profile-section active">
                    @include('profile.partials.reservas-pendientes')
                    @include('profile.partials.reservas-proximas')
                    @include('profile.partials.reservas-historial')
                </div>

                {{-- Sección: Mis Torneos --}}
                <div id="section-torneos" class="profile-section" style="display: none;">
                    @include('profile.partials.torneos')
                </div>

                {{-- Sección: Estadísticas --}}
                <div id="section-estadisticas" class="profile-section" style="display: none;">
                    @include('profile.partials.estadisticas')
                </div>

                {{-- Sección: Mi Información --}}
                <div id="section-informacion" class="profile-section" style="display: none;">
                    @include('profile.partials.informacion')
                </div>

                {{-- Sección: Pagos --}}
                <div id="section-pagos" class="profile-section" style="display: none;">
                    @include('profile.partials.pagos')
                </div>

                {{-- Sección: Configuración --}}
                <div id="section-configuracion" class="profile-section" style="display: none;">
                    @include('profile.partials.configuracion')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // ===================================
        // SISTEMA DE NAVEGACIÓN DEL PERFIL
        // ===================================
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item[data-section]');
            const sections = document.querySelectorAll('.profile-section');

            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    const sectionName = this.getAttribute('data-section');

                    // Remover clase active de todos los items del menú
                    menuItems.forEach(mi => mi.classList.remove('active'));

                    // Agregar clase active al item clickeado
                    this.classList.add('active');

                    // Ocultar todas las secciones
                    sections.forEach(section => {
                        section.style.display = 'none';
                        section.classList.remove('active');
                    });

                    // Mostrar la sección seleccionada
                    const targetSection = document.getElementById('section-' + sectionName);
                    if (targetSection) {
                        targetSection.style.display = 'block';
                        targetSection.classList.add('active');

                        // Scroll suave hacia arriba
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            });
        });

        // ===================================
        // COUNTDOWN TIMER PARA RESERVAS
        // ===================================
        function updateCountdowns() {
            document.querySelectorAll('.countdown').forEach(el => {
                const createdAt = new Date(el.dataset.created);
                const expiresAt = new Date(createdAt.getTime() + 5 * 60 * 1000);
                const now = new Date();
                const diff = expiresAt - now;

                if (diff <= 0) {
                    el.textContent = 'Expirada';
                    el.classList.add('text-danger', 'fw-bold');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    const minutes = Math.floor(diff / 60000);
                    const seconds = Math.floor((diff % 60000) / 1000);
                    el.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                }
            });
        }

        // Iniciar countdown si hay elementos
        if (document.querySelector('.countdown')) {
            setInterval(updateCountdowns, 1000);
            updateCountdowns();
        }
    </script>
@endsection
