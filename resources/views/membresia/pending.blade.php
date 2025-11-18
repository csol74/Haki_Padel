@extends('layouts.app')

@section('title', 'Pago Pendiente')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <i class="bi bi-hourglass-split text-warning display-1 mb-4"></i>
                    <h2 class="text-warning mb-3">Pago Pendiente</h2>
                    <p class="lead mb-4">Tu pago está siendo procesado</p>
                    <p class="text-muted">Estamos esperando la confirmación del pago. Te notificaremos cuando se complete el proceso.</p>

                    <div class="alert alert-info mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Este proceso puede tardar unos minutos. Recibirás una notificación cuando tu membresía sea activada.
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
