<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Reserva;
use App\Models\Torneo;
use App\Models\ReservaClase;
use App\Models\Pago;
use App\Models\Notificacion;
use App\Models\User;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\DB;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
    }

    /**
     * Crear la preferencia y redirigir a MercadoPago (RESERVAS) - CON DESCUENTO PARA SOCIOS
     */
    public function createPreference($reserva)
    {
        try {
            if (!$reserva instanceof Reserva) {
                $reserva = Reserva::with('cancha', 'usuario')->findOrFail($reserva);
            }

            if (!$reserva->cancha) {
                return back()->with('error', 'La reserva no tiene una cancha asociada.');
            }

            // ⭐⭐ USAR EL PRECIO FINAL QUE YA INCLUYE DESCUENTO DE SOCIO ⭐⭐
            $precioFinal = $reserva->precio_final;
            $precio = (int) round((float) $precioFinal);

            $canchaNombre = $reserva->cancha->nombre ?? 'Cancha';

            $client = new PreferenceClient();

            $itemTitle = "Reserva - {$canchaNombre}";

            // ⭐⭐ INCLUIR INFORMACIÓN DE DESCUENTO EN LA DESCRIPCIÓN ⭐⭐
            if ($reserva->descuento > 0) {
                $itemTitle .= " (Descuento Socio Aplicado)";
            }

            $preferenceData = [
                "items" => [
                    [
                        "title" => $itemTitle,
                        "quantity" => 1,
                        "unit_price" => $precio,
                        "currency_id" => "COP",
                    ]
                ],
                "back_urls" => [
                    "success" => route('mercadopago.confirmar', ['reserva_id' => $reserva->id]),
                    "failure" => route('mercadopago.failure'),
                    "pending" => route('mercadopago.confirmar', ['reserva_id' => $reserva->id]),
                ],
                "external_reference" => "reserva-{$reserva->id}",
            ];

            Log::info('Creando preferencia con descuento', [
                'reserva_id' => $reserva->id,
                'precio_base' => $reserva->precio_base,
                'descuento' => $reserva->descuento,
                'precio_final' => $reserva->precio_final,
                'es_socio' => $reserva->usuario->role === 'socio'
            ]);

            /** @var \MercadoPago\Resources\Preference $preference */
            $preference = $client->create($preferenceData);

            // Guardar pago con el precio final (ya con descuento aplicado)
            Pago::create([
                'user_id' => $reserva->user_id,
                'concepto' => 'reserva',
                'id_referencia' => $preference->id,
                'monto' => $precioFinal, // ⭐⭐ GUARDAR EL PRECIO FINAL CON DESCUENTO ⭐⭐
                'descuento_aplicado' => $reserva->descuento, // ⭐⭐ GUARDAR EL DESCUENTO APLICADO ⭐⭐
                'metodo_pago' => 'mercadopago',
                'estado' => 'pendiente',
            ]);

            $initPoint = $preference->init_point ?? $preference->sandbox_init_point;

            if (!$initPoint) {
                return back()->with('error', 'No se pudo generar el enlace de pago.');
            }

            // Guardar en sesión para después
            session(['reserva_pendiente_id' => $reserva->id]);

            return redirect()->away($initPoint);

        } catch (MPApiException $e) {
            $responseContent = $e->getApiResponse()->getContent();
            Log::error('MPApiException', ['response' => $responseContent]);
            return back()->with('error', 'Error al procesar el pago.');

        } catch (\Exception $e) {
            Log::error('Exception', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    /**
     * Crear la preferencia y redirigir a MercadoPago (TORNEOS)
     */
    public function createPreferenceTorneo($torneo)
    {
        try {
            if (!$torneo instanceof Torneo) {
                $torneo = Torneo::findOrFail($torneo);
            }

            $torneo->load('organizador');

            // Obtener el precio de inscripción del torneo
            $precioOriginal = $torneo->precio_inscripcion ?? 0;
            $precio = (int) round((float) $precioOriginal);
            $torneoNombre = $torneo->nombre ?? 'Torneo de Pádel';

            $client = new PreferenceClient();

            $preferenceData = [
                "items" => [
                    [
                        "title" => "Inscripción - {$torneoNombre}",
                        "quantity" => 1,
                        "unit_price" => $precio,
                        "currency_id" => "COP",
                    ]
                ],
                "back_urls" => [
                    "success" => route('mercadopago.torneo.confirmar', ['torneo_id' => $torneo->id]),
                    "failure" => route('mercadopago.torneo.failure'),
                    "pending" => route('mercadopago.torneo.confirmar', ['torneo_id' => $torneo->id]),
                ],
                "external_reference" => "torneo-{$torneo->id}",
            ];

            Log::info('Creando preferencia de torneo', $preferenceData);

            /** @var \MercadoPago\Resources\Preference $preference */
            $preference = $client->create($preferenceData);

            // Guardar pago - CORREGIDO: auth()->id()
            Pago::create([
                'user_id' => auth()->id(),
                'concepto' => 'torneo',
                'id_referencia' => $preference->id,
                'monto' => $precio,
                'metodo_pago' => 'mercadopago',
                'estado' => 'pendiente',
            ]);

            $initPoint = $preference->init_point ?? $preference->sandbox_init_point;

            if (!$initPoint) {
                return back()->with('error', 'No se pudo generar el enlace de pago.');
            }

            // Guardar en sesión para después
            session(['torneo_pendiente_id' => $torneo->id]);

            return redirect()->away($initPoint);

        } catch (MPApiException $e) {
            $responseContent = $e->getApiResponse()->getContent();
            Log::error('MPApiException en torneo', ['response' => $responseContent]);
            return back()->with('error', 'Error al procesar el pago.');

        } catch (\Exception $e) {
            Log::error('Exception en torneo', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Crear la preferencia y redirigir a MercadoPago (CLASES)
     */
    public function createPreferenceClase($reservaClase)
    {
        try {
            if (!$reservaClase instanceof ReservaClase) {
                $reservaClase = ReservaClase::findOrFail($reservaClase);
            }

            $reservaClase->load('profesor.user', 'horario');

            $precio = (int) round((float) $reservaClase->precio);
            $profesorNombre = $reservaClase->profesor->user->name ?? 'Profesor';
            $fecha = $reservaClase->fecha_clase->format('d/m/Y');

            $client = new PreferenceClient();

            $preferenceData = [
                "items" => [
                    [
                        "title" => "Clase de Pádel - {$profesorNombre} ({$fecha})",
                        "quantity" => 1,
                        "unit_price" => $precio,
                        "currency_id" => "COP",
                    ]
                ],
                "back_urls" => [
                    "success" => route('mercadopago.clase.confirmar', ['reserva_id' => $reservaClase->id]),
                    "failure" => route('mercadopago.clase.failure'),
                    "pending" => route('mercadopago.clase.confirmar', ['reserva_id' => $reservaClase->id]),
                ],
                "external_reference" => "clase-{$reservaClase->id}",
            ];

            Log::info('Creando preferencia de clase', $preferenceData);

            /** @var \MercadoPago\Resources\Preference $preference */
            $preference = $client->create($preferenceData);

            // Guardar pago - CORREGIDO: auth()->id()
            Pago::create([
                'user_id' => auth()->id(),
                'concepto' => 'clase',
                'id_referencia' => $preference->id,
                'monto' => $precio,
                'metodo_pago' => 'mercadopago',
                'estado' => 'pendiente',
            ]);

            $initPoint = $preference->init_point ?? $preference->sandbox_init_point;

            if (!$initPoint) {
                return back()->with('error', 'No se pudo generar el enlace de pago.');
            }

            // Guardar en sesión para después
            session(['reserva_clase_pendiente_id' => $reservaClase->id]);

            return redirect()->away($initPoint);

        } catch (MPApiException $e) {
            $responseContent = $e->getApiResponse()->getContent();
            Log::error('MPApiException en clase', ['response' => $responseContent]);
            return back()->with('error', 'Error al procesar el pago.');

        } catch (\Exception $e) {
            Log::error('Exception en clase', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Redirección luego del pago exitoso (RESERVAS)
     */
    public function success(Request $request)
    {
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');
        $preferenceId = $request->get('preference_id');
        $externalRef = $request->get('external_reference');
        $collectionId = $request->get('collection_id');
        $collectionStatus = $request->get('collection_status');

        Log::info('Usuario regresó de MercadoPago', [
            'payment_id' => $paymentId,
            'collection_id' => $collectionId,
            'status' => $status,
            'collection_status' => $collectionStatus,
            'all_params' => $request->all(),
        ]);

        // Buscar el pago en nuestra BD
        $pago = Pago::where('id_referencia', $preferenceId)->first();

        if (!$pago) {
            Log::error('No se encontró el pago', ['preference_id' => $preferenceId]);
            return redirect()->route('home')->with('error', 'No se encontró el registro del pago.');
        }

        // Buscar la reserva
        $reservaIdFromRef = $externalRef ? str_replace('reserva-', '', $externalRef) : null;
        $reserva = $reservaIdFromRef ? Reserva::find($reservaIdFromRef) : null;

        if (!$reserva) {
            Log::error('No se encontró la reserva', ['external_reference' => $externalRef]);
            return redirect()->route('home')->with('error', 'No se encontró la reserva.');
        }

        // VERIFICAR el pago consultando a MercadoPago
        $pagoVerificado = false;

        try {
            if ($paymentId || $collectionId) {
                $realPaymentId = $paymentId ?? $collectionId;

                // Consultar la API de MercadoPago
                $client = new PaymentClient();
                /** @var \MercadoPago\Resources\Payment $payment */
                $payment = $client->get($realPaymentId);

                Log::info('Verificación de pago', [
                    'payment_id' => $realPaymentId,
                    'status' => $payment->status,
                ]);

                // Solo aprobar si MercadoPago confirma
                if ($payment->status === 'approved') {
                    $pagoVerificado = true;
                }
            } else {
                // Fallback: confiar en el status de la URL
                if ($status === 'approved' || $collectionStatus === 'approved') {
                    $pagoVerificado = true;
                }
            }

        } catch (\Exception $e) {
            Log::error('Error al verificar pago', ['error' => $e->getMessage()]);

            // Fallback en caso de error
            if ($status === 'approved' || $collectionStatus === 'approved') {
                $pagoVerificado = true;
            }
        }

        // SOLO actualizar si el pago fue verificado
        if ($pagoVerificado) {

            if ($pago->estado !== 'completado') {
                $pago->estado = 'completado';
                $pago->save();
                Log::info('Pago completado', ['pago_id' => $pago->id]);
            }

            if ($reserva->estado !== 'completada') {
                $reserva->estado = 'completada';
                $reserva->save();
                Log::info('Reserva completada', ['reserva_id' => $reserva->id]);
            }

            // Crear notificación (evitar duplicados)
            $notificacionExiste = Notificacion::where('user_id', $reserva->user_id)
                ->where('tipo', 'reserva')
                ->where('contenido', 'LIKE', '%Tu pago se acreditó correctamente%')
                ->where('created_at', '>', now()->subMinutes(5))
                ->exists();

            if (!$notificacionExiste) {
                Notificacion::create([
                    'user_id' => $reserva->user_id,
                    'titulo' => 'Pago Completado',
                    'contenido' => 'Tu pago se acreditó correctamente. Tu reserva ha sido confirmada.',
                    'tipo' => 'reserva',
                    'leida' => false,
                ]);
            }

            $reserva->load('cancha');

            return view('mercadopago.success', [
                'reserva' => $reserva,
                'pago' => $pago,
            ]);

        } else {
            // Pago no aprobado
            Log::warning('Pago no aprobado', [
                'payment_id' => $paymentId,
                'status' => $status,
            ]);

            return redirect()->route('home')->with('warning',
                'Tu pago está pendiente de confirmación.');
        }
    }

    /**
     * Confirmar inscripción al torneo después de regresar de MercadoPago
     */
    public function confirmarTorneo(Request $request)
    {
        $torneoId = $request->get('torneo_id') ?? session('torneo_pendiente_id');
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');
        $preferenceId = $request->get('preference_id');
        $collectionId = $request->get('collection_id');
        $collectionStatus = $request->get('collection_status');

        Log::info('Confirmando inscripción a torneo', [
            'torneo_id' => $torneoId,
            'payment_id' => $paymentId,
            'status' => $status,
            'all_params' => $request->all()
        ]);

        if (!$torneoId) {
            return redirect()->route('torneos.index')->with('error', 'No se encontró el torneo.');
        }

        $torneo = Torneo::find($torneoId);

        if (!$torneo) {
            return redirect()->route('torneos.index')->with('error', 'Torneo no encontrado.');
        }

        // Buscar el pago asociado - CORREGIDO: auth()->id()
        $pago = Pago::where('user_id', auth()->id())
                    ->where('concepto', 'torneo')
                    ->where('estado', 'pendiente')
                    ->latest()
                    ->first();

        // Verificar el pago si es posible
        $pagoVerificado = false;

        try {
            if ($paymentId || $collectionId) {
                $realPaymentId = $paymentId ?? $collectionId;
                $client = new PaymentClient();
                /** @var \MercadoPago\Resources\Payment $payment */
                $payment = $client->get($realPaymentId);

                Log::info('Verificación de pago de torneo', [
                    'payment_id' => $realPaymentId,
                    'status' => $payment->status,
                ]);

                if ($payment->status === 'approved') {
                    $pagoVerificado = true;
                }
            } else {
                // Fallback: confiar en el status de la URL
                if ($status === 'approved' || $collectionStatus === 'approved') {
                    $pagoVerificado = true;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error al verificar pago de torneo', ['error' => $e->getMessage()]);

            // Fallback
            if ($status === 'approved' || $collectionStatus === 'approved') {
                $pagoVerificado = true;
            }
        }

        // Solo procesar si el pago fue verificado
        if ($pagoVerificado) {
            try {
                DB::beginTransaction();

                // Actualizar pago
                if ($pago && $pago->estado !== 'completado') {
                    $pago->estado = 'completado';
                    $pago->save();
                }

                // Verificar si ya está inscrito - CORREGIDO: auth()->id()
                $yaInscrito = $torneo->participantes->contains(auth()->id());

                if (!$yaInscrito) {
                    // Inscribir al usuario - CORREGIDO: auth()->id()
                    $torneo->participantes()->attach(auth()->id(), [
                        'estado' => 'confirmado',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Usuario inscrito en torneo', [
                        'user_id' => auth()->id(),
                        'torneo_id' => $torneo->id
                    ]);
                }

                // Crear notificación (evitar duplicados) - CORREGIDO: auth()->id()
                $notificacionExiste = Notificacion::where('user_id', auth()->id())
                    ->where('tipo', 'torneo')
                    ->where('contenido', 'LIKE', '%inscripción confirmada%')
                    ->where('created_at', '>', now()->subMinutes(5))
                    ->exists();

                if (!$notificacionExiste) {
                    Notificacion::create([
                        'user_id' => auth()->id(),
                        'titulo' => 'Inscripción Confirmada',
                        'contenido' => "Tu inscripción al torneo '{$torneo->nombre}' ha sido confirmada. ¡Buena suerte!",
                        'tipo' => 'torneo',
                        'leida' => false,
                    ]);
                }

                DB::commit();

                session()->forget('torneo_pendiente_id');

                Log::info('Inscripción a torneo confirmada exitosamente', ['torneo_id' => $torneo->id]);

                return view('mercadopago.torneo-success', [
                    'torneo' => $torneo,
                    'pago' => $pago,
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error al confirmar inscripción a torneo', ['error' => $e->getMessage()]);
                return redirect()->route('torneos.index')
                    ->with('error', 'Ocurrió un error al confirmar tu inscripción.');
            }
        } else {
            // Pago no verificado
            Log::warning('Pago de torneo no aprobado', [
                'torneo_id' => $torneoId,
                'payment_id' => $paymentId,
                'status' => $status,
            ]);

            return redirect()->route('torneos.index')->with('warning',
                'Tu pago está pendiente de confirmación. Te notificaremos cuando se complete.');
        }
    }

    /**
     * Confirmar reserva de clase después de regresar de MercadoPago
     */
    public function confirmarClase(Request $request)
    {
        $reservaId = $request->get('reserva_id') ?? session('reserva_clase_pendiente_id');
        $paymentId = $request->get('payment_id');
        $status = $request->get('status');
        $collectionId = $request->get('collection_id');
        $collectionStatus = $request->get('collection_status');

        Log::info('Confirmando reserva de clase', [
            'reserva_id' => $reservaId,
            'payment_id' => $paymentId,
            'status' => $status,
        ]);

        if (!$reservaId) {
            return redirect()->route('clases.index')->with('error', 'No se encontró la reserva.');
        }

        $reserva = ReservaClase::find($reservaId);

        if (!$reserva) {
            return redirect()->route('clases.index')->with('error', 'Reserva no encontrada.');
        }

        // Buscar el pago asociado - CORREGIDO: auth()->id()
        $pago = Pago::where('user_id', auth()->id())
                    ->where('concepto', 'clase')
                    ->where('estado', 'pendiente')
                    ->latest()
                    ->first();

        // Verificar el pago
        $pagoVerificado = false;

        try {
            if ($paymentId || $collectionId) {
                $realPaymentId = $paymentId ?? $collectionId;
                $client = new PaymentClient();
                /** @var \MercadoPago\Resources\Payment $payment */
                $payment = $client->get($realPaymentId);

                Log::info('Verificación de pago de clase', [
                    'payment_id' => $realPaymentId,
                    'status' => $payment->status,
                ]);

                if ($payment->status === 'approved') {
                    $pagoVerificado = true;
                }
            } else {
                if ($status === 'approved' || $collectionStatus === 'approved') {
                    $pagoVerificado = true;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error al verificar pago de clase', ['error' => $e->getMessage()]);

            if ($status === 'approved' || $collectionStatus === 'approved') {
                $pagoVerificado = true;
            }
        }

        if ($pagoVerificado) {
            try {
                DB::beginTransaction();

                // Actualizar pago
                if ($pago && $pago->estado !== 'completado') {
                    $pago->estado = 'completado';
                    $pago->save();
                }

                // Confirmar reserva
                if ($reserva->estado !== 'confirmada') {
                    $reserva->confirmar();
                }

                // Crear notificación - CORREGIDO: auth()->id()
                $notificacionExiste = Notificacion::where('user_id', auth()->id())
                    ->where('tipo', 'clase')
                    ->where('contenido', 'LIKE', '%clase confirmada%')
                    ->where('created_at', '>', now()->subMinutes(5))
                    ->exists();

                if (!$notificacionExiste) {
                    Notificacion::create([
                        'user_id' => auth()->id(),
                        'titulo' => 'Clase Confirmada',
                        'contenido' => "Tu clase con {$reserva->profesor->user->name} para el {$reserva->fecha_clase->format('d/m/Y')} ha sido confirmada.",
                        'tipo' => 'clase',
                        'leida' => false,
                    ]);
                }

                DB::commit();

                session()->forget('reserva_clase_pendiente_id');

                Log::info('Reserva de clase confirmada exitosamente', ['reserva_id' => $reserva->id]);

                return view('mercadopago.clase-success', [
                    'reserva' => $reserva,
                    'pago' => $pago,
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error al confirmar reserva de clase', ['error' => $e->getMessage()]);
                return redirect()->route('clases.index')
                    ->with('error', 'Ocurrió un error al confirmar tu reserva.');
            }
        } else {
            Log::warning('Pago de clase no aprobado', [
                'reserva_id' => $reservaId,
                'payment_id' => $paymentId,
                'status' => $status,
            ]);

            return redirect()->route('clases.index')->with('warning',
                'Tu pago está pendiente de confirmación.');
        }
    }

    /**
     * Redirección si el pago de torneo falla o se cancela
     */
    public function failureTorneo(Request $request)
    {
        Log::info('Pago de torneo fallido', $request->all());

        session()->forget('torneo_pendiente_id');

        return redirect()->route('torneos.index')
            ->with('error', 'El pago fue cancelado o falló. Puedes intentarlo nuevamente.');
    }

    /**
     * Redirección si el pago de clase falla o se cancela
     */
    public function failureClase(Request $request)
    {
        Log::info('Pago de clase fallido', $request->all());

        $reservaId = session('reserva_clase_pendiente_id');

        if ($reservaId) {
            // ELIMINAR la reserva temporal para liberar el horario
            $reserva = ReservaClase::find($reservaId);
            if ($reserva && $reserva->estado === 'pendiente') {
                Log::info('Eliminando reserva pendiente no pagada', ['reserva_id' => $reserva->id]);
                $reserva->delete();
            }
        }

        // También buscar por pago pendiente del usuario - CORREGIDO: auth()->id()
        $pago = Pago::where('user_id', auth()->id())
                    ->where('concepto', 'clase')
                    ->where('estado', 'pendiente')
                    ->latest()
                    ->first();

        if ($pago) {
            $pago->delete(); // Eliminar también el registro de pago pendiente
        }

        session()->forget('reserva_clase_pendiente_id');

        return redirect()->route('clases.index')
            ->with('info', 'El pago fue cancelado. El horario está disponible nuevamente.');
    }

    /**
     * Redirección si el pago falla o se cancela (RESERVAS)
     */
    public function failure(Request $request)
    {
        Log::info('Pago fallido', $request->all());

        return redirect()->route('home')
            ->with('error', 'El pago fue cancelado o falló. Puedes intentarlo nuevamente.');
    }

    /**
     * Confirmar pago después de regresar de MercadoPago (RESERVAS)
     */
    public function confirmar(Request $request)
    {
        $reservaId = $request->get('reserva_id') ?? session('reserva_pendiente_id');

        Log::info('Confirmando reserva', ['reserva_id' => $reservaId]);

        if (!$reservaId) {
            return redirect()->route('profile.show')->with('error', 'No se encontró la reserva.');
        }

        $reserva = Reserva::find($reservaId);

        if (!$reserva) {
            return redirect()->route('profile.show')->with('error', 'Reserva no encontrada o expirada.');
        }

        // Verificar que no haya expirado
        if ($reserva->created_at < now()->subMinutes(5)) {
            $reserva->delete();
            Notificacion::create([
                'user_id' => $reserva->user_id,
                'titulo' => 'Reserva expirada',
                'contenido' => 'Tu reserva ha caducado por falta de pago.',
                'tipo' => 'reserva',
                'leida' => false,
            ]);
            return redirect()->route('profile.show')->with('error', 'Esta reserva ha expirado.');
        }

        // Buscar el pago asociado
        $pago = Pago::where('user_id', $reserva->user_id)
                    ->where('concepto', 'reserva')
                    ->where('estado', 'pendiente')
                    ->latest()
                    ->first();

        if ($pago) {
            $pago->estado = 'completado';
            $pago->save();
        }

        $reserva->estado = 'completada';
        $reserva->save();

        // Crear notificación
        $notificacionExiste = Notificacion::where('user_id', $reserva->user_id)
            ->where('tipo', 'reserva')
            ->where('contenido', 'LIKE', '%Tu pago se acreditó correctamente%')
            ->where('created_at', '>', now()->subMinutes(5))
            ->exists();

        if (!$notificacionExiste) {
            Notificacion::create([
                'user_id' => $reserva->user_id,
                'titulo' => 'Pago Completado',
                'contenido' => 'Tu pago se acreditó correctamente. Tu reserva ha sido confirmada.',
                'tipo' => 'reserva',
                'leida' => false,
            ]);
        }

        session()->forget('reserva_pendiente_id');

        $reserva->load('cancha');

        Log::info('Reserva confirmada exitosamente', ['reserva_id' => $reserva->id]);

        return view('mercadopago.success', [
            'reserva' => $reserva,
            'pago' => $pago,
        ]);
    }
}
