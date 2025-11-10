@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    @include('layouts.navbar')

    <!-- Carrusel principal -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/cancha1.jpg') }}" class="d-block w-100" style="height: 80vh; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="fw-bold display-5 text-white text-shadow">Vive la experiencia Pádel</h2>
                    <p class="lead">Disfruta nuestras canchas de última generación</p>
                    <a href="{{ route('canchas.index') }}" class="btn btn-success btn-lg fw-bold">Reservar Ahora</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/raquetas.jpg') }}" class="d-block w-100" style="height: 80vh; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="fw-bold display-5 text-white text-shadow">Torneos y comunidad</h2>
                    <p class="lead">Únete a nuestros eventos y haz parte del club</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/amigos.jpg') }}" class="d-block w-100" style="height: 80vh; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="fw-bold display-5 text-white text-shadow">Más que deporte, un estilo de vida</h2>
                    <p class="lead">Ven con tus amigos y vive momentos únicos</p>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Bienvenida -->
    <div class="text-center my-5 px-3">
        <h1 class="fw-bold" style="font-family:'Poppins', sans-serif;">
            Bienvenido a <span style="color:#009688;">Haki Pádel Club</span>
        </h1>
        <p class="text-muted mx-auto" style="max-width:800px;">
            En Haki Pádel Club encontrarás el mejor ambiente deportivo de Bucaramanga.
            Contamos con canchas profesionales, torneos semanales y una comunidad apasionada
            por el pádel. ¡Ven a vivir una experiencia única!
        </p>
    </div>

    <!-- Beneficios -->
    <div class="container py-4">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="bi bi-lightning-charge-fill fs-1 text-success mb-3"></i>
                <h4 class="fw-bold">Canchas Profesionales</h4>
                <p class="text-muted">Superficies de alta calidad con iluminación LED para jugar de día o de noche.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-people-fill fs-1 text-success mb-3"></i>
                <h4 class="fw-bold">Ambiente Social</h4>
                <p class="text-muted">Comparte con jugadores de todos los niveles y forma parte de una comunidad activa.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-trophy-fill fs-1 text-success mb-3"></i>
                <h4 class="fw-bold">Torneos y Actividades</h4>
                <p class="text-muted">Participa en nuestros torneos internos y disfruta de eventos especiales todo el año.</p>
            </div>
        </div>
    </div>

    <!-- Sección informativa / llamada a explorar -->
    <div class="text-center py-5" style="background-color:#009688;">
        <h2 class="text-white fw-bold mb-3">Descubre todo lo que tenemos para ti</h2>
        <p class="text-white-50 mb-4">Consulta nuestras canchas, torneos y eventos disponibles</p>
        <a href="{{ route('torneos.index') }}" class="btn btn-light fw-bold px-4">Ver Torneos</a>
    </div>
</div>
@endsection
