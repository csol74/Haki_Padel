@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver al Dashboard
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearTorneoModal">
                <i class="bi bi-plus-circle me-1"></i>Crear Nuevo Torneo
            </button>
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
            <h4 class="mb-0"><i class="bi bi-trophy me-2"></i>Gestión de Torneos ({{ count($torneos) }})</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Fecha Inicio</th>
                            <th>Participantes</th>
                            <th>Estado</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($torneos as $torneo)
                        <tr>
                            <td><strong>#{{ $torneo->id }}</strong></td>
                            <td><strong>{{ $torneo->nombre }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $torneo->categoria == 'masculino' ? 'primary' : ($torneo->categoria == 'femenino' ? 'danger' : 'success') }}">
                                    {{ ucfirst($torneo->categoria) }}
                                </span>
                            </td>
                            <td>{{ date('d/m/Y', strtotime($torneo->fecha_inicio)) }}</td>
                            <td>
                                <strong>{{ $torneo->participantes_count }}/{{ $torneo->cantidad_max_participantes }}</strong>
                            </td>
                            <td>
                                @if($torneo->estado == 'inscripciones_abiertas')
                                    <span class="badge bg-success">Abierto</span>
                                @elseif($torneo->estado == 'en_curso')
                                    <span class="badge bg-primary">En Curso</span>
                                @elseif($torneo->estado == 'finalizado')
                                    <span class="badge bg-secondary">Finalizado</span>
                                @else
                                    <span class="badge bg-info">Planificación</span>
                                @endif
                            </td>
                            <td><strong>${{ number_format($torneo->precio_inscripcion, 0, ',', '.') }}</strong></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-primary"
                                            onclick="editarTorneo({{ json_encode($torneo) }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarTorneoModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.torneos.eliminar', $torneo->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este torneo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                No hay torneos creados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

<!-- Modal Editar Torneo -->
<div class="modal fade" id="editarTorneoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Editar Torneo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEditarTorneo">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Torneo *</label>
                            <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría *</label>
                            <select class="form-select" name="categoria" id="edit_categoria" required>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="mixto">Mixto</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" id="edit_descripcion" rows="3"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Inicio *</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="edit_fecha_inicio" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Fin *</label>
                            <input type="date" class="form-control" name="fecha_fin" id="edit_fecha_fin" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Límite Inscripción *</label>
                            <input type="date" class="form-control" name="fecha_inscripcion_limite" id="edit_fecha_inscripcion_limite" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Precio Inscripción *</label>
                            <input type="number" class="form-control" name="precio_inscripcion" id="edit_precio_inscripcion" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Máx. Participantes *</label>
                            <input type="number" class="form-control" name="cantidad_max_participantes" id="edit_cantidad_max_participantes" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado *</label>
                            <select class="form-select" name="estado" id="edit_estado" required>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Actualizar Torneo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarTorneo(torneo) {
    document.getElementById('formEditarTorneo').action = `/admin/torneos/${torneo.id}`;
    document.getElementById('edit_nombre').value = torneo.nombre;
    document.getElementById('edit_categoria').value = torneo.categoria;
    document.getElementById('edit_descripcion').value = torneo.descripcion || '';
    document.getElementById('edit_fecha_inicio').value = torneo.fecha_inicio;
    document.getElementById('edit_fecha_fin').value = torneo.fecha_fin;
    document.getElementById('edit_fecha_inscripcion_limite').value = torneo.fecha_inscripcion_limite;
    document.getElementById('edit_precio_inscripcion').value = torneo.precio_inscripcion;
    document.getElementById('edit_cantidad_max_participantes').value = torneo.cantidad_max_participantes;
    document.getElementById('edit_estado').value = torneo.estado;
}
</script>
@endsection
