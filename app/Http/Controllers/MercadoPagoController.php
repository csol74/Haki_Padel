<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Reserva;
use App\Models\Pago;
use App\Models\Notificacion;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
    }

    /**
     * Crear la preferencia y redirigir a MercadoPago
     */
    public function createPreference($reserva)
{
    try {
        if (!$reserva instanceof Reserva) {
            $reserva = Reserva::findOrFail($reserva);
        }

        $reserva->load('cancha');

        if (!$reserva->cancha) {
            return back()->with('error', 'La reserva no tiene una cancha asociada.');
        }

        $precioOriginal = $reserva->cancha->precio_hora ?? 20000;
        $precio = (int) round((float) $precioOriginal);
        $canchaNombre = $reserva->cancha->nombre ?? 'Cancha';

        $client = new PreferenceClient();

        $preferenceData = [
            "items" => [
                [
                    "title" => "Reserva - {$canchaNombre}",
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

        Log::info('Creando preferencia', $preferenceData);

        $preference = $client->create($preferenceData);

        // Guardar pago
        Pago::create([
            'user_id' => $reserva->user_id,
            'concepto' => 'reserva',
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
     * Redirección luego del pago exitoso
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
        return redirect()->route('mercadopago.confirmar', $request->all());
    }

    /**
     * Redirección si el pago falla o se cancela
     */
    public function failure(Request $request)
    {
        Log::info('Pago fallido', $request->all());

        return redirect()->route('home')
            ->with('error', 'El pago fue cancelado o falló. Puedes intentarlo nuevamente.');
    }
    /**
 * Confirmar pago después de regresar de MercadoPago
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
