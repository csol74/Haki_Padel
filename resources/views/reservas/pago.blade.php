@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pago.css') }}">

<div class="container text-center my-5">

    <div class="card shadow-sm p-5 mx-auto" style="max-width: 600px; border-radius: 15px;">
        <h3 class="fw-bold text-success mb-4">Confirmar Pago de Reserva</h3>

        <p class="text-muted mb-4">
            Confirma el pago de tu reserva para completar el proceso.
            Si decides no continuar, puedes cancelarla sin costo.
        </p>

        <div class="text-start mb-4">
            <p><strong>Cancha:</strong> {{ $reserva->id_cancha }}</p>
            <p><strong>Fecha:</strong> {{ $reserva->fecha }}</p>
            <p><strong>Horario:</strong> {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</p>
            <p><strong>Jugadores:</strong> {{ $reserva->numero_jugadores }}</p>
        </div>

       <div class="d-flex justify-content-center gap-3">
        {{-- Botón de pagar --}}
        <a href="{{ route('mercadopago.preference', ['reserva' => $reserva->id]) }}"
        class="btn btn-success px-4 py-2 fw-semibold">
        Pagar con Mercado Pago
        </a>

        {{-- Botón de cancelar --}}
        <form action="{{ route('reservas.cancelar', $reserva->id) }}" method="POST"
            onsubmit="return confirm('¿Seguro que deseas cancelar la reserva?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger px-4 py-2 fw-semibold">
                Cancelar
            </button>
        </form>
    </div>

    </div>

</div>

@endsection
