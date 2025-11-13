@extends('admin.layouts.app')

@section('title', 'Gestión de Reservas - Admin')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">

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
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Gestión de Reservas ({{ count($reservas) }})</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Cancha</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                        <tr>
                            <td><strong>#{{ $reserva->id }}</strong></td>
                            <td>
                                <strong>{{ $reserva->usuario_nombre }}</strong><br>
                                <small class="text-muted">{{ $reserva->usuario_email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $reserva->cancha_nombre }}</span><br>
                                <small>{{ ucfirst($reserva->cancha_tipo) }}</small>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($reserva->fecha)) }}</td>
                            <td>
                                {{ date('H:i', strtotime($reserva->hora_inicio)) }} -
                                {{ date('H:i', strtotime($reserva->hora_fin)) }}
                            </td>
                            <td>
                                <strong>{{ $reserva->duracion_horas }}h</strong>
                            </td>
                            <td>
                                <strong class="text-success">${{ number_format($reserva->precio_total, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($reserva->estado == 'pendiente')
                                    <span class="badge bg-warning">Pendiente</span>
                                @elseif($reserva->estado == 'confirmada')
                                    <span class="badge bg-success">Confirmada</span>
                                @elseif($reserva->estado == 'completada')
                                    <span class="badge bg-primary">Completada</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($reserva->estado) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($reserva->estado != 'cancelada')
                                <form method="POST" action="{{ route('admin.reservas.cancelar', $reserva->id) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">Cancelada</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                No hay reservas registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
