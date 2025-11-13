@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1"><i class="bi bi-shield-check text-primary me-2"></i>Panel de Administración</h2>
                            <p class="text-muted mb-0">Bienvenido, {{ Auth::user()->name }}</p>
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-1"></i>Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 text-primary"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_usuarios'] }}</h3>
                    <p class="text-muted mb-0">Usuarios Registrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check display-4 text-success"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_reservas'] }}</h3>
                    <p class="text-muted mb-0">Total Reservas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-trophy display-4 text-warning"></i>
                    <h3 class="mt-3 mb-0">{{ $stats['total_torneos'] }}</h3>
                    <p class="text-muted mb-0">Torneos Activos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack display-4 text-info"></i>
                    <h3 class="mt-3 mb-0">${{ number_format($stats['ingresos_mes'], 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Ingresos del Mes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Gestión -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <ul class="nav nav-tabs card-header-tabs" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
                        <i class="bi bi-people me-1"></i>Usuarios
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reservas-tab" data-bs-toggle="tab" data-bs-target="#reservas" type="button" role="tab">
                        <i class="bi bi-calendar-check me-1"></i>Reservas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="torneos-tab" data-bs-toggle="tab" data-bs-target="#torneos" type="button" role="tab">
                        <i class="bi bi-trophy me-1"></i>Torneos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reportes-tab" data-bs-toggle="tab" data-bs-target="#reportes" type="button" role="tab">
                        <i class="bi bi-graph-up me-1"></i>Reportes
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="adminTabsContent">

                <!-- TAB USUARIOS -->
                <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
                    <h4 class="mb-3"><i class="bi bi-people me-2"></i>Gestión de Usuarios</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Fecha Registro</th>
                                    <th>Reservas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <a href="{{ route('admin.usuarios') }}" class="btn btn-primary">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>Ver Gestión Completa de Usuarios
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB RESERVAS -->
                <div class="tab-pane fade" id="reservas" role="tabpanel">
                    <h4 class="mb-3"><i class="bi bi-calendar-check me-2"></i>Gestión de Reservas</h4>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Hoy hay <strong>{{ $stats['reservas_hoy'] }}</strong> reservas programadas
                    </div>
                    <div class="text-center">
                        <a href="{{ route('admin.reservas') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Ver Todas las Reservas
                        </a>
                    </div>
                </div>

                <!-- TAB TORNEOS -->
                <div class="tab-pane fade" id="torneos" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0"><i class="bi bi-trophy me-2"></i>Gestión de Torneos</h4>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearTorneoModal">
                            <i class="bi bi-plus-circle me-1"></i>Crear Nuevo Torneo
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.torneos') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Ver Gestión Completa de Torneos
                        </a>
                    </div>
                </div>

                <!-- TAB REPORTES -->
                <div class="tab-pane fade" id="reportes" role="tabpanel">
                    <h4 class="mb-3"><i class="bi bi-graph-up me-2"></i>Reportes y Estadísticas</h4>
                    <div class="text-center">
                        <a href="{{ route('admin.reportes') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Ver Reportes Detallados
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Torneo -->
<div class="modal fade" id="crearTorneoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-trophy me-2"></i>Crear Nuevo Torneo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.torneos.crear') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Torneo *</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría *</label>
                            <select class="form-select" name="categoria" required>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="mixto">Mixto</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Inicio *</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Fin *</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Límite Inscripción *</label>
                            <input type="date" class="form-control" name="fecha_inscripcion_limite" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Precio Inscripción *</label>
                            <input type="number" class="form-control" name="precio_inscripcion" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Máx. Participantes *</label>
                            <input type="number" class="form-control" name="cantidad_max_participantes" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" name="estado" required>
                                <option value="planificacion">Planificación</option>
                                <option value="inscripciones_abiertas">Inscripciones Abiertas</option>
                                <option value="en_curso">En Curso</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Crear Torneo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
