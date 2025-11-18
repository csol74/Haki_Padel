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
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-people me-2"></i>Gestión de Usuarios ({{ count($usuarios) }})</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                            <th>Última Actividad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td><strong>#{{ $usuario->id }}</strong></td>
                            <td>
                                {{ $usuario->name }}
                                @if($usuario->role === 'socio')
                                    <span class="badge bg-warning text-dark ms-1">
                                        <i class="bi bi-star-fill"></i>
                                @endif
                                @if($usuario->role === 'profesor')
                                    <span class="badge bg-info ms-1">
                                        <i class="bi bi-mortarboard"></i>
                                    </span>
                                @endif
                                @if($usuario->role === 'admin')
                                    <span class="badge bg-danger ms-1">
                                        <i class="bi bi-shield-fill"></i>
                                    </span>
                                @endif
                            </td>
                            <td><small>{{ $usuario->email }}</small></td>
                            <td>
                                @switch($usuario->role)
                                    @case('admin')
                                        <span class="badge bg-danger">Administrador</span>
                                        @break
                                    @case('socio')
                                        <span class="badge bg-warning text-dark">Socio</span>
                                        @break
                                    @case('profesor')
                                        <span class="badge bg-info">Profesor</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Cliente</span>
                                @endswitch
                            </td>
                            <td>{{ date('d/m/Y', strtotime($usuario->created_at)) }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($usuario->updated_at)) }}</td>
                            <td>
                                @if($usuario->role !== 'admin')
                                    <form method="POST" action="{{ route('admin.usuarios.eliminar', $usuario->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Protegido</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                No hay usuarios registrados
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
