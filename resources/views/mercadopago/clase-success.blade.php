@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pago.css') }}">

<div class="container text-center my-5">
    <div class="card shadow-lg p-5 mx-auto" style="max-width: 700px; border-radius: 15px;">

        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>

        <h2 class="fw-bold text-success mb-4">¡Clase Reservada!</h2>

        <p class="text-muted mb-3">
            Tu pago se acreditó correctamente y tu clase ha sido confirmada.
        </p>

        @if ($reserva)
            <div class="border p-4 rounded text-start mb-4 bg-light">
                <h5 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-calendar-check me-2"></i>Detalles de tu Clase
                </h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong><i class="bi bi-person-badge text-success me-2"></i>Profesor:</strong><br>
                            {{ $reserva->profesor->user->name }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong><i class="bi bi-tag text-info me-2"></i>Especialidad:</strong><br>
                            {{ $reserva->profesor->especialidad_formateada }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong><i class="bi bi-calendar3 text-danger me-2"></i>Fecha:</strong><br>
                            {{ \Carbon\Carbon::parse($reserva->fecha_clase)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong><i class="bi bi-clock text-warning me-2"></i>Horario:</strong><br>
                            {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('g:i A') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong><i class="bi bi-hourglass-split text-primary me-2"></i>Duración:</strong><br>
                            {{ $reserva->duracion_minutos }} minutos
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-0">
                            <strong><i class="bi bi-trophy text-success me-2"></i>Nivel:</strong><br>
                            {{ $reserva->nivel_formateado }}
                        </p>
                    </div>
                </div>

                @if($reserva->notas)
                <div class="mt-3 pt-3 border-top">
                    <p class="mb-0">
                        <strong><i class="bi bi-sticky text-info me-2"></i>Notas:</strong><br>
                        <small class="text-muted">{{ $reserva->notas }}</small>
                    </p>
                </div>
                @endif

                <div class="mt-3 pt-3 border-top">
                    <p class="mb-0">
                        <strong><i class="bi bi-check-circle-fill text-success me-2"></i>Estado:</strong>
                        <span class="badge bg-success ms-2">{{ $reserva->estado_formateado }}</span>
                    </p>
                </div>
            </div>
        @endif

        @if ($pago)
            <div class="alert alert-success mb-4" role="alert">
                <i class="bi bi-cash-coin me-2"></i>
                <strong>Monto pagado:</strong> ${{ number_format($pago->monto, 0, ',', '.') }} COP
            </div>
        @endif

        <div class="alert alert-info mb-4" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <div class="text-start">
                <strong>Información importante:</strong>
                <ul class="mb-0 mt-2">
                    <li>Recibirás un correo de confirmación con todos los detalles</li>
                    <li>Te recomendamos llegar 10 minutos antes de la clase</li>
                    <li>Puedes cancelar hasta 24 horas antes sin cargo</li>
                    <li>No olvides traer tu raqueta y calzado adecuado</li>
                </ul>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('clases.show', $reserva->profesor_id) }}" class="btn btn-primary btn-lg px-5 py-2 fw-semibold">
                <i class="bi bi-calendar-plus me-2"></i>Agendar Otra Clase
            </a>
            <a href="{{ route('clases.index') }}" class="btn btn-outline-secondary px-5 py-2">
                <i class="bi bi-arrow-left me-2"></i>Ver Todos los Profesores
            </a>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-primary px-5 py-2">
                <i class="bi bi-person-circle me-2"></i>Ir a Mi Perfil
            </a>
        </div>

        <div class="mt-4 p-3 bg-light rounded">
            <small class="text-muted">
                <i class="bi bi-telephone me-2"></i>
                ¿Necesitas ayuda? Contáctanos al <strong>+57 311 217 2009</strong>
            </small>
        </div>
    </div>
</div>
@endsection