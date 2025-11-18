@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver al Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">
                <i class="bi bi-star-fill me-2"></i>Solicitudes de Membresía
                <span class="badge bg-dark">{{ $solicitudes->where('estado', 'pendiente')->count() }} Pendientes</span>
            </h4>
        </div>
        <div class="card-body">

            {{-- Tabs para filtrar por estado --}}
            <ul class="nav nav-tabs mb-4" id="solicitudesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button">
                        <i class="bi bi-hourglass-split me-1"></i>Pendientes
                        <span class="badge bg-warning">{{ $solicitudes->where('estado', 'pendiente')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="aprobadas-tab" data-bs-toggle="tab" data-bs-target="#aprobadas" type="button">
                        <i class="bi bi-check-circle me-1"></i>Aprobadas
                        <span class="badge bg-success">{{ $solicitudes->where('estado', 'aprobada')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rechazadas-tab" data-bs-toggle="tab" data-bs-target="#rechazadas" type="button">
                        <i class="bi bi-x-circle me-1"></i>Rechazadas
                        <span class="badge bg-danger">{{ $solicitudes->where('estado', 'rechazada')->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="solicitudesTabContent">

                {{-- PENDIENTES --}}
                <div class="tab-pane fade show active" id="pendientes" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Monto</th>
                                    <th>Payment ID</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes->where('estado', 'pendiente') as $solicitud)
                                <tr>
                                    <td><strong>#{{ $solicitud->id }}</strong></td>
                                    <td>{{ $solicitud->usuario->name }}</td>
                                    <td><small>{{ $solicitud->usuario->email }}</small></td>
                                    <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge bg-info">${{ number_format($solicitud->monto, 0, ',', '.') }}</span></td>
                                    <td><small class="text-muted">{{ $solicitud->payment_id ?? 'N/A' }}</small></td>
                                    <td>
                                        <form action="{{ route('admin.membresias.aprobar', $solicitud->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('¿Aprobar esta membresía?')">
                                                <i class="bi bi-check-lg"></i> Aprobar
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalRechazar{{ $solicitud->id }}">
                                            <i class="bi bi-x-lg"></i> Rechazar
                                        </button>

                                        {{-- Modal para rechazar --}}
                                        <div class="modal fade" id="modalRechazar{{ $solicitud->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rechazar Solicitud</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.membresias.rechazar', $solicitud->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p>¿Estás seguro de rechazar la solicitud de <strong>{{ $solicitud->usuario->name }}</strong>?</p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Motivo (opcional)</label>
                                                                <textarea name="notas" class="form-control" rows="3" placeholder="Explica el motivo del rechazo..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Rechazar Solicitud</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        No hay solicitudes pendientes
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- APROBADAS --}}
                <div class="tab-pane fade" id="aprobadas" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Fecha Aprobación</th>
                                    <th>Aprobado Por</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes->where('estado', 'aprobada') as $solicitud)
                                <tr>
                                    <td><strong>#{{ $solicitud->id }}</strong></td>
                                    <td>
                                        {{ $solicitud->usuario->name }}
                                        <span class="badge bg-success ms-1">SOCIO</span>
                                    </td>
                                    <td><small>{{ $solicitud->usuario->email }}</small></td>
                                    <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $solicitud->fecha_aprobacion ? $solicitud->fecha_aprobacion->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $solicitud->aprobadoPor->name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-success">${{ number_format($solicitud->monto, 0, ',', '.') }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        No hay solicitudes aprobadas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- RECHAZADAS --}}
                <div class="tab-pane fade" id="rechazadas" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Fecha Solicitud</th>
                                    <th>Monto</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($solicitudes->where('estado', 'rechazada') as $solicitud)
                                <tr>
                                    <td><strong>#{{ $solicitud->id }}</strong></td>
                                    <td>{{ $solicitud->usuario->name }}</td>
                                    <td><small>{{ $solicitud->usuario->email }}</small></td>
                                    <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>
                                    <td><span class="badge bg-danger">${{ number_format($solicitud->monto, 0, ',', '.') }}</span></td>
                                    <td><small>{{ $solicitud->notas ?? 'Sin notas' }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        No hay solicitudes rechazadas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
