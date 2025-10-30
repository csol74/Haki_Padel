@extends('layouts.app')

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    @include('layouts.navbar')
    <!-- Contenido principal -->
    <div class="text-center mt-5">
        <h1 class="fw-bold" style="font-family:'Poppins', sans-serif; font-size: 2.5rem;">
            Reserva tu cancha de <span style="color:#009688;">Pádel</span>
        </h1>
        <p class="text-muted">Las mejores instalaciones de Bucaramanga a tu disposición</p>

        <button class="btn btn-success px-4 py-2 fw-bold">Reservar Ahora</button>

        <!-- Buscar Disponibilidad -->
        <div class="mt-5 mx-auto p-4" style="max-width:900px; border:2px solid #000; border-radius:10px;">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="fw-bold">Fecha</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Hora</label>
                    <input type="time" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Tipo de Cancha</label>
                    <select class="form-select">
                        <option>Todas</option>
                        <option>Premium</option>
                        <option>Estándar</option>
                    </select>
                </div>
            </div>

            <div class="text-end mt-3">
                <button class="btn btn-success px-4 fw-bold">
                    <i class="bi bi-search"></i> Buscar Disponibilidad
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
