@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@endsection

@section('content')
<div class="container-fluid perfil-container">
    @include('layouts.navbar')  

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
                        @if($user->email_verified_at)
                            <span class="email-status verified">✓ Verificado</span>
                        @else
                            <span class="email-status not-verified">⏳ No verificado</span>
                        @endif
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

                <!-- Mis Reservas Activas -->
                <div class="section-card">
                    <h2 class="section-title">📅 Mis Próximas Reservas</h2>

                    <div class="reservas-list">
                        <div class="reserva-item">
                            <div class="reserva-info">
                                <span class="reserva-icon">🎾</span>
                                <div class="reserva-details">
                                    <h4>Cancha 1 - Premium</h4>
                                    <p class="reserva-meta">📅 Hoy, 17 de Octubre • 🕐 8:00 PM - 9:30 PM</p>
                                </div>
                            </div>
                            <div class="reserva-right">
                                <span class="reserva-status status-confirmada">✓ Confirmada</span>
                                <div class="reserva-actions">
                                    <button class="btn-action btn-ver">Ver QR</button>
                                    <button class="btn-action btn-cancelar">Cancelar</button>
                                </div>
                            </div>
                        </div>

                        <div class="reserva-item">
                            <div class="reserva-info">
                                <span class="reserva-icon">🎾</span>
                                <div class="reserva-details">
                                    <h4>Cancha 4 - Estándar</h4>
                                    <p class="reserva-meta">📅 Sábado, 19 de Octubre • 🕐 10:00 AM - 11:30 AM</p>
                                </div>
                            </div>
                            <div class="reserva-right">
                                <span class="reserva-status status-confirmada">✓ Confirmada</span>
                                <div class="reserva-actions">
                                    <button class="btn-action btn-ver">Ver Detalles</button>
                                    <button class="btn-action btn-cancelar">Cancelar</button>
                                </div>
                            </div>
                        </div>

                        <div class="reserva-item">
                            <div class="reserva-info">
                                <span class="reserva-icon">🎾</span>
                                <div class="reserva-details">
                                    <h4>Cancha 2 - Estándar</h4>
                                    <p class="reserva-meta">📅 Lunes, 21 de Octubre • 🕐 6:00 PM - 7:30 PM</p>
                                </div>
                            </div>
                            <div class="reserva-right">
                                <span class="reserva-status status-pendiente">⏳ Pago Pendiente</span>
                                <div class="reserva-actions">
                                    <button class="btn-action btn-pagar">Pagar Ahora</button>
                                    <button class="btn-action btn-cancelar">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de Reservas -->
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
                        <div class="reserva-item reserva-historica">
                            <div class="reserva-info">
                                <span class="reserva-icon">🎾</span>
                                <div class="reserva-details">
                                    <h4>Cancha 3 - Premium</h4>
                                    <p class="reserva-meta">📅 12 de Octubre • 🕐 7:00 PM - 8:30 PM</p>
                                </div>
                            </div>
                            <span class="reserva-status status-confirmada">✓ Completada</span>
                        </div>

                        <div class="reserva-item reserva-historica">
                            <div class="reserva-info">
                                <span class="reserva-icon">🎾</span>
                                <div class="reserva-details">
                                    <h4>Cancha 1 - Premium</h4>
                                    <p class="reserva-meta">📅 8 de Octubre • 🕐 9:00 AM - 10:30 AM</p>
                                </div>
                            </div>
                            <span class="reserva-status status-confirmada">✓ Completada</span>
                        </div>
                    </div>
                </div>

@section('scripts')
    <script src="{{ asset('js/perfil.js') }}"></script>
@endsection
