@extends('layouts.app')

@section('title', 'Pago Exitoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <i class="bi bi-check-circle-fill text-success display-1 mb-4"></i>
                    <h2 class="text-success mb-3">¡Pago Iniciado!</h2>
                    <p class="lead mb-4">Tu solicitud de membresía está siendo procesada</p>
                    <p class="text-muted">Nuestro equipo revisará tu pago y aprobará tu membresía en breve. Recibirás una notificación cuando esto ocurra.</p>

                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Nota:</strong> Debido a que aún no tenemos dominio propio, la confirmación de pago debe ser manual. Te notificaremos pronto.
                    </div>

                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-house me-2"></i>Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
