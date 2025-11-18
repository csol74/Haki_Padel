@extends('layouts.app')

@section('title', 'Pago Fallido')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <i class="bi bi-x-circle-fill text-danger display-1 mb-4"></i>
                    <h2 class="text-danger mb-3">Pago No Completado</h2>
                    <p class="lead mb-4">Hubo un problema al procesar tu pago</p>
                    <p class="text-muted">No se realizó ningún cargo. Por favor, intenta nuevamente o contacta con nuestro soporte.</p>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('membresia.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat me-2"></i>Intentar Nuevamente
                        </a>
                        <a href="{{ route('contacto.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-envelope me-2"></i>Contactar Soporte
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-link">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
