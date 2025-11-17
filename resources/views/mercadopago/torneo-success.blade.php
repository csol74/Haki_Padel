@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pago.css') }}">

<div class="container text-center my-5">
    <div class="card shadow-lg p-5 mx-auto" style="max-width: 600px; border-radius: 15px;">

        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>

        <h2 class="fw-bold text-success mb-4">¡Inscripción Confirmada!</h2>

        <p class="text-muted mb-3">
            Tu pago se acreditó correctamente y tu inscripción al torneo ha sido confirmada.
        </p>

        @if ($torneo)
            <div class="border p-3 rounded text-start mb-4 bg-light">
                <p><strong>Torneo:</strong> {{ $torneo->nombre }}</p>
                <p><strong>Categoría:</strong> {{ ucfirst($torneo->categoria ?? 'Mixto') }}</p>
                <p><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($torneo->fecha_inicio)->format('d/m/Y') }}</p>
                <p><strong>Fecha de Fin:</strong> {{ \Carbon\Carbon::parse($torneo->fecha_fin)->format('d/m/Y') }}</p>
                <p class="mb-0"><strong>Estado:</strong> <span class="badge bg-success">Confirmado</span></p>
            </div>
        @endif

        @if ($pago)
            <div class="alert alert-success" role="alert">
                <strong>Monto pagado:</strong> ${{ number_format($pago->monto, 0, ',', '.') }} COP
            </div>
        @endif

        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Importante:</strong> Recibirás más información sobre el torneo por correo electrónico.
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('torneos.show', $torneo->id) }}" class="btn btn-primary btn-lg px-5 py-2 fw-semibold">
                <i class="bi bi-trophy me-2"></i>Ver Detalles del Torneo
            </a>
            <a href="{{ route('torneos.index') }}" class="btn btn-outline-secondary px-5 py-2">
                <i class="bi bi-arrow-left me-2"></i>Ver Todos los Torneos
            </a>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-primary px-5 py-2">
                <i class="bi bi-person-circle me-2"></i>Ir a Mi Perfil
            </a>
        </div>
    </div>
</div>
@endsection