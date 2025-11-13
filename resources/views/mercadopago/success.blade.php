@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pago.css') }}">

<div class="container text-center my-5">
    <div class="card shadow-lg p-5 mx-auto" style="max-width: 600px; border-radius: 15px;">

        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>

        <h2 class="fw-bold text-success mb-4">¡Pago Completado!</h2>

        <p class="text-muted mb-3">
            Tu pago se acreditó correctamente y tu reserva ha sido confirmada.
        </p>

        @if ($reserva)
            <div class="border p-3 rounded text-start mb-4 bg-light">
                <p><strong>Cancha:</strong> {{ $reserva->cancha->nombre ?? 'Cancha' }}</p>
                <p><strong>Fecha:</strong> {{ $reserva->fecha }}</p>
                <p><strong>Horario:</strong> {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</p>
                <p class="mb-0"><strong>Estado:</strong> <span class="badge bg-success">Completada</span></p>
            </div>
        @endif

        @if ($pago)
            <div class="alert alert-success" role="alert">
                <strong>Monto pagado:</strong> ${{ number_format($pago->monto, 0, ',', '.') }} COP
            </div>
        @endif

        <a href="{{ route('profile.show') }}" class="btn btn-success btn-lg px-5 py-2 fw-semibold">
            <i class="bi bi-house-door-fill me-2"></i>Regresar a perfil
        </a>
    </div>
</div>
@endsection
