@extends('layouts.app')

@section('title', 'Membresía Hakipadel')

@section('content')
<div class="membresia-container">
    <div class="container py-5">

        {{-- Alertas --}}
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

        {{-- Header --}}
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary mb-3">
                <i class="bi bi-star-fill text-warning"></i> Membresía Hakipadel
            </h1>
            <p class="lead text-muted">Únete a nuestro club y disfruta de beneficios exclusivos</p>
        </div>

        @if($esSocio)
            {{-- Usuario ya es socio --}}
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-lg socio-card">
                        <div class="card-body text-center p-5">
                            <i class="bi bi-trophy-fill text-warning display-1 mb-4"></i>
                            <h2 class="text-success mb-3">¡Eres Socio de Hakipadel!</h2>
                            <p class="lead mb-4">Disfruta de todos los beneficios exclusivos de tu membresía</p>

                            <div class="beneficios-activos">
                                <div class="row text-start">
                                    <div class="col-md-6 mb-3">
                                        <div class="beneficio-item">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <strong>20% de descuento</strong> en todas las clases
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="beneficio-item">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <strong>15% de descuento</strong> en reservas de canchas
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="beneficio-item">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            Prioridad en reservas
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="beneficio-item">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            Acceso a torneos exclusivos
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg mt-4">
                                <i class="bi bi-house me-2"></i>Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($solicitudPendiente)
            {{-- Solicitud pendiente --}}
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-lg pendiente-card">
                        <div class="card-body text-center p-5">
                            <i class="bi bi-hourglass-split text-warning display-1 mb-4"></i>
                            <h2 class="text-warning mb-3">Solicitud Pendiente</h2>
                            <p class="lead mb-4">Tu solicitud de membresía está siendo revisada por nuestro equipo</p>
                            <p class="text-muted">Recibirás una notificación cuando sea aprobada</p>

                            <div class="alert alert-info mt-4">
                                <i class="bi bi-info-circle me-2"></i>
                                Fecha de solicitud: {{ $solicitudPendiente->created_at->format('d/m/Y H:i') }}
                            </div>

                            <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">
                                <i class="bi bi-house me-2"></i>Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- Información y compra de membresía --}}
            <div class="row">
                {{-- Beneficios --}}
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0"><i class="bi bi-gift me-2"></i>Beneficios de la Membresía</h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="beneficio-box">
                                        <div class="beneficio-icon">
                                            <i class="bi bi-percent text-success"></i>
                                        </div>
                                        <h5 class="fw-bold">20% de Descuento en Clases</h5>
                                        <p class="text-muted mb-0">Aprende y mejora tu técnica con descuento en todas nuestras clases de pádel</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="beneficio-box">
                                        <div class="beneficio-icon">
                                            <i class="bi bi-calendar-check text-info"></i>
                                        </div>
                                        <h5 class="fw-bold">15% de Descuento en Canchas</h5>
                                        <p class="text-muted mb-0">Reserva canchas con precio preferencial todos los días</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="beneficio-box">
                                        <div class="beneficio-icon">
                                            <i class="bi bi-star text-warning"></i>
                                        </div>
                                        <h5 class="fw-bold">Prioridad en Reservas</h5>
                                        <p class="text-muted mb-0">Acceso prioritario a las mejores canchas en horarios premium</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="beneficio-box">
                                        <div class="beneficio-icon">
                                            <i class="bi bi-trophy text-danger"></i>
                                        </div>
                                        <h5 class="fw-bold">Torneos Exclusivos</h5>
                                        <p class="text-muted mb-0">Participa en torneos exclusivos para socios con premios especiales</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="beneficio-box">
                                        <div class="beneficio-icon">
                                            <i class="bi bi-people text-primary"></i>
                                        </div>
                                        <h5 class="fw-bold">Comunidad Exclusiva</h5>
                                        <p class="text-muted mb-0">Forma parte de nuestra comunidad de jugadores apasionados</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="beneficio-box">
                                        <div class="beneficio-icon">
                                            <i class="bi bi-lightning text-success"></i>
                                        </div>
                                        <h5 class="fw-bold">Beneficios Especiales</h5>
                                        <p class="text-muted mb-0">Acceso a eventos especiales y promociones exclusivas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card de compra --}}
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-lg precio-card sticky-top" style="top: 100px;">
                        <div class="card-body text-center p-4">
                            <div class="badge bg-warning text-dark mb-3 px-3 py-2">
                                <i class="bi bi-star-fill me-1"></i>OFERTA ESPECIAL
                            </div>

                            <h2 class="display-4 fw-bold text-primary mb-2">$100.000</h2>
                            <p class="text-muted mb-4">Pago único anual</p>

                            <div class="ahorro-badge mb-4">
                                <i class="bi bi-piggy-bank me-2"></i>
                                ¡Ahorra hasta $500.000 al año!
                            </div>

                            <form action="{{ route('membresia.pago') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Adquirir Membresía
                                </button>
                            </form>

                            <p class="text-muted small mb-0">
                                <i class="bi bi-shield-check me-1"></i>
                                Pago seguro con MercadoPago
                            </p>

                            <hr class="my-4">

                            <div class="text-start">
                                <h6 class="fw-bold mb-3">¿Qué incluye?</h6>
                                <ul class="list-unstyled small">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Descuentos permanentes
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Validez de 1 año
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Renovación automática opcional
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Soporte prioritario
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FAQ --}}
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h3 class="mb-0"><i class="bi bi-question-circle me-2"></i>Preguntas Frecuentes</h3>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                            ¿Cuánto dura la membresía?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            La membresía tiene una duración de 1 año desde la fecha de activación y se puede renovar al finalizar.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                            ¿Cuándo se activan los descuentos?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Los descuentos se activan inmediatamente después de que el administrador apruebe tu solicitud de membresía.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                            ¿Puedo cancelar mi membresía?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Sí, puedes cancelar tu membresía en cualquier momento contactando con nuestro servicio de atención al cliente.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
