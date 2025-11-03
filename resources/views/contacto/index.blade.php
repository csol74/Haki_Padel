@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
@endsection

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    @include('layouts.navbar')

    <!-- Hero Section -->
    <div class="contact-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">¡Contáctanos!</h1>
                    <p class="lead mb-4">Estamos aquí para ayudarte con tus reservas, consultas y hacer que tu experiencia en pádel sea increíble.</p>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="#" class="social-icon me-2">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-icon me-2">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-icon me-2">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="bi bi-twitter"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Teléfono</h5>
                                <p class="mb-2">Llámanos directamente</p>
                                <a href="tel:+573001234567" class="text-decoration-none fw-bold">+57 300 123 4567</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="bi bi-whatsapp"></i>
                                </div>
                                <h5 class="fw-bold mb-2">WhatsApp</h5>
                                <p class="mb-2">Respuesta inmediata</p>
                                <a href="https://wa.me/573001234567" class="text-decoration-none fw-bold">Enviar mensaje</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Email</h5>
                                <p class="mb-2">Consultas generales</p>
                                <a href="mailto:info@hakipadel.com" class="text-decoration-none fw-bold">info@hakipadel.com</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-method">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Ubicación</h5>
                                <p class="mb-2">Visítanos</p>
                                <span class="fw-bold">Calle 45 #27-85</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- Formulario de Contacto -->
            <div class="col-lg-8">
                <div class="contact-card card">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-4">Envíanos un mensaje</h3>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contacto.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nombre Completo</label>
                                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                           placeholder="Tu nombre completo" value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="tu@email.com" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Teléfono (Opcional)</label>
                                    <input type="tel" name="telefono" class="form-control @error('telefono') is-invalid @enderror" 
                                           placeholder="+57 300 123 4567" value="{{ old('telefono') }}">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipo de Consulta</label>
                                    <select name="tipo_consulta" class="form-select @error('tipo_consulta') is-invalid @enderror" required>
                                        <option value="">Selecciona una opción</option>
                                        <option value="reservas" {{ old('tipo_consulta') == 'reservas' ? 'selected' : '' }}>Reservas</option>
                                        <option value="clases" {{ old('tipo_consulta') == 'clases' ? 'selected' : '' }}>Clases de pádel</option>
                                        <option value="torneos" {{ old('tipo_consulta') == 'torneos' ? 'selected' : '' }}>Torneos</option>
                                        <option value="instalaciones" {{ old('tipo_consulta') == 'instalaciones' ? 'selected' : '' }}>Instalaciones</option>
                                        <option value="otro" {{ old('tipo_consulta') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('tipo_consulta')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Asunto</label>
                                    <input type="text" name="asunto" class="form-control @error('asunto') is-invalid @enderror" 
                                           placeholder="Breve descripción del tema" value="{{ old('asunto') }}" required>
                                    @error('asunto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Mensaje</label>
                                    <textarea name="mensaje" class="form-control @error('mensaje') is-invalid @enderror" rows="5" 
                                              placeholder="Cuéntanos en detalle cómo podemos ayudarte..." required>{{ old('mensaje') }}</textarea>
                                    @error('mensaje')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" 
                                               value="1" {{ old('newsletter') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="newsletter">
                                            Quiero recibir noticias y promociones de Haki Pádel
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-contact btn-lg px-5">
                                        <i class="bi bi-send me-2"></i>Enviar Mensaje
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="col-lg-4">
                <!-- Información de Contacto -->
                <div class="info-card card mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Información de Contacto</h5>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Dirección</h6>
                                <p class="text-muted mb-0">Calle 45 #27-85<br>Bucaramanga, Santander</p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Horarios</h6>
                                <p class="text-muted mb-0">
                                    Lun - Vie: 6:00 AM - 10:00 PM<br>
                                    Sáb - Dom: 7:00 AM - 9:00 PM
                                </p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Emails</h6>
                                <p class="text-muted mb-0">
                                    General: <a href="mailto:info@hakipadel.com" class="text-decoration-none">info@hakipadel.com</a><br>
                                    Reservas: <a href="mailto:reservas@hakipadel.com" class="text-decoration-none">reservas@hakipadel.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa -->
                <div class="info-card card">
                    <div class="card-body p-0">
                        <div class="map-container">
                            <!-- Aquí puedes integrar Google Maps o similar -->
                            <div class="map-placeholder">
                                <i class="bi bi-geo-alt display-4 text-muted"></i>
                                <p class="text-muted mt-2">Mapa de Ubicación</p>
                                <small class="text-muted">Bucaramanga, Santander</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="info-card card">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4 text-center">Preguntas Frecuentes</h4>
                        
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item border-0 mb-3">
                                <h5 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        ¿Con cuánta anticipación puedo reservar una cancha?
                                    </button>
                                </h5>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Puedes reservar con un mínimo de 2 horas de anticipación y hasta 30 días por adelantado. Para eventos especiales, recomendamos reservar con al menos una semana de anticipación.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item border-0 mb-3">
                                <h5 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        ¿Qué incluye el alquiler de la cancha?
                                    </button>
                                </h5>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        El alquiler incluye: uso de la cancha por el tiempo reservado, raquetas profesionales (4 unidades), pelotas oficiales (6 unidades), toallas deportivas, acceso a vestuarios con duchas y casilleros de seguridad.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item border-0 mb-3">
                                <h5 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        ¿Cuál es la política de cancelación?
                                    </button>
                                </h5>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Puedes cancelar gratuitamente hasta 1 hora antes del inicio de tu reserva. Cancelaciones con menos tiempo tendrán un cargo del 50% del valor total.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item border-0">
                                <h5 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        ¿Ofrecen clases de pádel?
                                    </button>
                                </h5>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Sí, ofrecemos clases para todos los niveles con instructores certificados. Las clases pueden ser individuales, grupales o intensivas. Contáctanos para más información sobre horarios y precios.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Animación de los métodos de contacto
    document.addEventListener('DOMContentLoaded', function() {
        const contactMethods = document.querySelectorAll('.contact-method');
        
        contactMethods.forEach((method, index) => {
            setTimeout(() => {
                method.style.opacity = '0';
                method.style.transform = 'translateY(20px)';
                method.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    method.style.opacity = '1';
                    method.style.transform = 'translateY(0)';
                }, 100);
            }, index * 200);
        });
    });
</script>
@endsection