@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h2 class="text-success">Pago Completado</h2>
    <p>Tu reserva ha sido confirmada exitosamente.</p>

    <div class="card p-3 mt-3 mx-auto" style="max-width: 500px;">
        <h5>Detalles de la reserva</h5>
        <p><strong>Cancha:</strong> #{{ $reserva->id_cancha }}</p>
        <p><strong>Fecha:</strong> {{ $reserva->fecha }}</p>
        <p><strong>Hora:</strong> {{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($reserva->estado) }}</p>
    </div>

    <a href="{{ route('canchas.index') }}" class="btn btn-primary mt-4">Volver a canchas</a>
</div>
@endsection
