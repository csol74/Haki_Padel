@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver al Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Ingresos Mensuales -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Ingresos Mensuales</h5>
                </div>
                <div class="card-body">
                    @if(count($ingresosMensuales) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mes/Año</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ingresosMensuales as $ingreso)
                                    <tr>
                                        <td>
                                            {{ DateTime::createFromFormat('!m', $ingreso->mes)->format('F') }} {{ $ingreso->anio }}
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-success">
                                                ${{ number_format($ingreso->total, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>TOTAL</th>
                                        <th class="text-end">
                                            <strong class="text-primary">
                                                ${{ number_format($ingresosMensuales->sum('total'), 0, ',', '.') }}
                                            </strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-graph-up display-3 text-muted"></i>
                            <p class="text-muted mt-3">No hay datos de ingresos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reservas por Cancha -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Reservas por Cancha</h5>
                </div>
                <div class="card-body">
                    @if(count($reservasPorCancha) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Cancha</th>
                                        <th class="text-end">Reservas</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalReservas = $reservasPorCancha->sum('total_reservas');
                                    @endphp
                                    @foreach($reservasPorCancha as $cancha)
                                    <tr>
                                        <td><strong>{{ $cancha->nombre }}</strong></td>
                                        <td class="text-end">{{ $cancha->total_reservas }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-primary">
                                                {{ round(($cancha->total_reservas / $totalReservas) * 100, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>TOTAL</th>
                                        <th class="text-end">{{ $totalReservas }}</th>
                                        <th class="text-end">100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-bar-chart display-3 text-muted"></i>
                            <p class="text-muted mt-3">No hay datos de reservas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
