@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/torneos.css') }}">
<style>
.profesor-detail-header {
    background: linear-gradient(135deg, #009688 0%, #00796B 100%);
    color: white;
    padding: 40px 0;
    border-radius: 15px;
    margin-bottom: 30px;
}

.profesor-avatar-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    font-weight: bold;
    color: #009688;
    border: 5px solid rgba(255,255,255,0.3);
    margin: 0 auto;
}

.info-section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.calendario-dia {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.calendario-dia:hover {
    border-color: #009688;
    box-shadow: 0 2px 10px rgba(0,150,136,0.2);
    transform: translateY(-2px);
}

.calendario-dia.selected {
    background: #009688;
    color: white;
    border-color: #009688;
}

.calendario-dia.fin-semana {
    background: #FFF3E0;
}

.horario-slot {
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.horario-slot:hover {
    border-color: #009688;
    box-shadow: 0 2px 8px rgba(0,150,136,0.2);
}

.horario-slot.selected {
    background: #009688;
    color: white;
    border-color: #009688;
}

.horario-slot.prime {
    border-left: 4px solid #FFD700;
}

.precio-display {
    background: linear-gradient(135deg, #FFD700 0%, #FFA000 100%);
    color: #333;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    margin: 20px 0;
}

.precio-monto {
    font-size: 36px;
    font-weight: bold;
}

.duracion-btn {
    border: 2px solid #009688;
    background: white;
    color: #009688;
    border-radius: 25px;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.duracion-btn:hover, .duracion-btn.active {
    background: #009688;
    color: white;
}

.nivel-badge {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 20px;
    border: 2px solid #009688;
    background: white;
    color: #009688;
    margin: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nivel-badge:hover, .nivel-badge.active {
    background: #009688;
    color: white;
}
</style>
@endsection

@section('content')
<div class="container-fluid p-0" style="background-color:#E8F3F5; min-height:100vh;">
    <div class="container py-4">
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb-custom mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('clases.index') }}">Clases</a></li>
                <li class="breadcrumb-item active">{{ $profesor->user->name }}</li>
            </ol>
        </nav>

        <!-- Header del Profesor -->
        <div class="profesor-detail-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="profesor-avatar-large">
                            {{ strtoupper(substr($profesor->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-2">{{ $profesor->user->name }}</h1>
                        <h4 class="mb-3">
                            <span class="badge" style="background: rgba(255,255,255,0.3); font-size: 1rem;">
                                {{ $profesor->especialidad_formateada }}
                            </span>
                        </h4>
                        <div class="d-flex flex-wrap gap-3">
                            <div>
                                <i class="bi bi-star-fill me-1"></i>
                                <strong>{{ $profesor->experiencia_anios }} años</strong> de experiencia
                            </div>
                            <div>
                                <i class="bi bi-clock me-1"></i>
                                <strong>{{ $profesor->horarios->count() }}</strong> horarios disponibles
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Principal -->
            <div class="col-lg-8">
                
                <!-- Biografía -->
                <div class="info-section">
                    <h4 class="mb-3">
                        <i class="bi bi-person-badge text-primary me-2"></i>Sobre el Profesor
                    </h4>
                    <p class="text-muted">{{ $profesor->biografia }}</p>
                </div>

                <!-- Calendario -->
                <div class="info-section">
                    <h4 class="mb-3">
                        <i class="bi bi-calendar-week text-primary me-2"></i>Selecciona una Fecha
                    </h4>
                    <div class="row g-3" id="calendario">
                        @foreach($diasDisponibles as $dia)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="calendario-dia {{ $dia['es_fin_semana'] ? 'fin-semana' : '' }}" 
                                 data-fecha="{{ $dia['fecha'] }}"
                                 data-dia-semana="{{ $dia['dia_semana'] }}">
                                <div class="fw-bold">{{ ucfirst(explode(' ', $dia['fecha_formateada'])[0]) }}</div>
                                <div class="small">{{ explode(' ', $dia['fecha_formateada'], 2)[1] ?? '' }}</div>
                                @if($dia['es_fin_semana'])
                                    <small class="text-warning"><i class="bi bi-star-fill"></i> +30%</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Horarios -->
                <div class="info-section" id="seccion-horarios" style="display:none;">
                    <h4 class="mb-3">
                        <i class="bi bi-clock text-primary me-2"></i>Horarios Disponibles
                    </h4>
                    <div id="horarios-container">
                        <div class="alert alert-info">
                            Selecciona una fecha para ver los horarios disponibles
                        </div>
                    </div>
                </div>

                <!-- Duración y Nivel -->
                <div class="info-section" id="seccion-configuracion" style="display:none;">
                    <h4 class="mb-3">
                        <i class="bi bi-gear text-primary me-2"></i>Configuración de la Clase
                    </h4>
                    
                    <!-- Duración -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Duración de la Clase:</label>
                        <div>
                            <button class="duracion-btn" data-duracion="30">30 min</button>
                            <button class="duracion-btn active" data-duracion="60">1 hora</button>
                            <button class="duracion-btn" data-duracion="90">1.5 horas</button>
                            <button class="duracion-btn" data-duracion="120">2 horas</button>
                        </div>
                    </div>

                    <!-- Nivel -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tu Nivel:</label>
                        <div>
                            <span class="nivel-badge active" data-nivel="principiante">
                                <i class="bi bi-emoji-smile me-1"></i>Principiante
                            </span>
                            <span class="nivel-badge" data-nivel="intermedio">
                                <i class="bi bi-emoji-sunglasses me-1"></i>Intermedio
                            </span>
                            <span class="nivel-badge" data-nivel="avanzado">
                                <i class="bi bi-trophy me-1"></i>Avanzado
                            </span>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div>
                        <label class="form-label fw-bold">Notas Adicionales (Opcional):</label>
                        <textarea class="form-control" id="notas" rows="3" placeholder="Ej: Quiero trabajar en mi saque..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Columna Lateral -->
            <div class="col-lg-4">
                <div class="info-section position-sticky" style="top: 20px;">
                    <h4 class="mb-3">
                        <i class="bi bi-cash text-success me-2"></i>Resumen de Reserva
                    </h4>

                    <!-- Precio -->
                    <div class="precio-display">
                        <div class="small mb-1">Precio Total</div>
                        <div class="precio-monto" id="precio-total">
                            ${{ number_format($profesor->tarifa_base_30min, 0, ',', '.') }}
                        </div>
                        <small id="precio-detalle">Tarifa base por 30min</small>
                    </div>

                    <!-- Detalles de la reserva -->
                    <div id="resumen-reserva" style="display:none;">
                        <div class="mb-2">
                            <strong>Fecha:</strong>
                            <div id="resumen-fecha" class="text-muted">-</div>
                        </div>
                        <div class="mb-2">
                            <strong>Horario:</strong>
                            <div id="resumen-horario" class="text-muted">-</div>
                        </div>
                        <div class="mb-2">
                            <strong>Duración:</strong>
                            <div id="resumen-duracion" class="text-muted">60 minutos</div>
                        </div>
                        <div class="mb-3">
                            <strong>Nivel:</strong>
                            <div id="resumen-nivel" class="text-muted">Principiante</div>
                        </div>
                    </div>

                    <!-- Formulario de reserva -->
                    <form id="form-reservar" action="{{ route('clases.reservar') }}" method="POST" style="display:none;">
                        @csrf
                        <input type="hidden" name="profesor_id" value="{{ $profesor->id }}">
                        <input type="hidden" name="horario_id" id="input-horario-id">
                        <input type="hidden" name="fecha_clase" id="input-fecha">
                        <input type="hidden" name="hora_inicio" id="input-hora-inicio">
                        <input type="hidden" name="duracion_minutos" id="input-duracion" value="60">
                        <input type="hidden" name="nivel" id="input-nivel" value="principiante">
                        <input type="hidden" name="notas" id="input-notas">

                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>Confirmar y Pagar
                        </button>
                    </form>

                    <div id="mensaje-seleccion" class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Selecciona fecha y horario para continuar
                    </div>

                    <!-- Información adicional -->
                    <hr class="my-3">
                    <div class="small text-muted">
                        <div class="mb-2">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            Pago seguro con MercadoPago
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-arrow-clockwise text-info me-1"></i>
                            Cancela hasta 24h antes
                        </div>
                        <div>
                            <i class="bi bi-bell text-warning me-1"></i>
                            Confirmación inmediata
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let fechaSeleccionada = null;
let horarioSeleccionado = null;
let horarioIdSeleccionado = null;
let duracionSeleccionada = 60;
let nivelSeleccionado = 'principiante';

// Selección de fecha
document.querySelectorAll('.calendario-dia').forEach(dia => {
    dia.addEventListener('click', function() {
        // Limpiar selección anterior
        document.querySelectorAll('.calendario-dia').forEach(d => d.classList.remove('selected'));
        this.classList.add('selected');
        
        fechaSeleccionada = this.dataset.fecha;
        const diaSemana = this.dataset.diaSemana;
        
        // Cargar horarios
        cargarHorarios(fechaSeleccionada);
        
        // Mostrar sección de horarios
        document.getElementById('seccion-horarios').style.display = 'block';
        document.getElementById('seccion-configuracion').style.display = 'none';
        document.getElementById('form-reservar').style.display = 'none';
        
        // Actualizar resumen
        document.getElementById('resumen-fecha').textContent = this.querySelector('.small').textContent;
    });
});

// Selección de duración
document.querySelectorAll('.duracion-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.duracion-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        duracionSeleccionada = parseInt(this.dataset.duracion);
        document.getElementById('resumen-duracion').textContent = duracionSeleccionada + ' minutos';
        document.getElementById('input-duracion').value = duracionSeleccionada;
        calcularPrecio();
    });
});

// Selección de nivel
document.querySelectorAll('.nivel-badge').forEach(badge => {
    badge.addEventListener('click', function() {
        document.querySelectorAll('.nivel-badge').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        nivelSeleccionado = this.dataset.nivel;
        document.getElementById('resumen-nivel').textContent = this.textContent.trim();
        document.getElementById('input-nivel').value = nivelSeleccionado;
    });
});

// Cargar horarios disponibles
function cargarHorarios(fecha) {
    const container = document.getElementById('horarios-container');
    container.innerHTML = '<div class="text-center"><div class="spinner-border text-primary"></div></div>';
    
    fetch(`{{ route('clases.horarios') }}?profesor_id={{ $profesor->id }}&fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            if (data.horarios.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">No hay horarios disponibles para este día</div>';
                return;
            }
            
            let html = '<div class="row g-2">';
            data.horarios.forEach(horario => {
                html += `
                    <div class="col-6 col-md-4">
                        <div class="horario-slot ${horario.es_prime ? 'prime' : ''}" 
                             data-hora="${horario.hora}"
                             data-horario-id="${horario.horario_id}">
                            <div class="fw-bold">${horario.hora_formateada}</div>
                            ${horario.es_prime ? '<small class="text-warning"><i class="bi bi-star-fill"></i> Prime</small>' : ''}
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
            
            // Agregar eventos a los horarios
            document.querySelectorAll('.horario-slot').forEach(slot => {
                slot.addEventListener('click', function() {
                    document.querySelectorAll('.horario-slot').forEach(s => s.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    horarioSeleccionado = this.dataset.hora;
                    horarioIdSeleccionado = this.dataset.horarioId;
                    
                    document.getElementById('resumen-horario').textContent = this.querySelector('.fw-bold').textContent;
                    document.getElementById('input-hora-inicio').value = horarioSeleccionado;
                    document.getElementById('input-horario-id').value = horarioIdSeleccionado;
                    
                    // Mostrar configuración
                    document.getElementById('seccion-configuracion').style.display = 'block';
                    document.getElementById('resumen-reserva').style.display = 'block';
                    document.getElementById('form-reservar').style.display = 'block';
                    document.getElementById('mensaje-seleccion').style.display = 'none';
                    
                    calcularPrecio();
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="alert alert-danger">Error al cargar horarios</div>';
        });
}

// Calcular precio
function calcularPrecio() {
    if (!fechaSeleccionada || !horarioIdSeleccionado) return;
    
    fetch('{{ route("clases.calcular-precio") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            profesor_id: {{ $profesor->id }},
            fecha: fechaSeleccionada,
            duracion: duracionSeleccionada,
            horario_id: horarioIdSeleccionado
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('precio-total').textContent = '$' + new Intl.NumberFormat('es-CO').format(data.precio_final);
        
        let detalle = ``;
        if (data.detalles.es_fin_semana) detalle += 'Fin de semana ';
        if (data.detalles.es_horario_prime) detalle += '+ Horario Prime';
        
        document.getElementById('precio-detalle').textContent = detalle || 'Precio calculado';
    })
    .catch(error => console.error('Error:', error));
}

// Al enviar el formulario, agregar notas
document.getElementById('form-reservar').addEventListener('submit', function() {
    document.getElementById('input-notas').value = document.getElementById('notas').value;
    document.getElementById('input-fecha').value = fechaSeleccionada;
});
</script>
@endsection