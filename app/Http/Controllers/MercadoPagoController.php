<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Reserva;
use App\Models\Pago;
use App\Models\Notificacion;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
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
                "success" => route('mercadopago.success'),
                "failure" => route('mercadopago.failure'),
                "pending" => route('mercadopago.success'),
            ],
            // QUITAR auto_return temporalmente
            "external_reference" => "reserva-{$reserva->id}",
        ];

        Log::info('Datos de preferencia', $preferenceData);

        // Crear preferencia
        $preference = $client->create($preferenceData);

        Log::info('Preferencia creada', [
            'id' => $preference->id,
            'init_point' => $preference->init_point ?? null,
            'sandbox_init_point' => $preference->sandbox_init_point ?? null,
        ]);

        // Guardar pago
        Pago::create([
            'user_id' => $reserva->user_id,
            'concepto' => 'reserva',
            'id_referencia' => $preference->id,
            'monto' => $precio,
            'metodo_pago' => 'mercadopago',
            'estado' => 'pendiente',
        ]);

        $initPoint = $preference->init_point ?? $preference->sandbox_init_point ?? null;

        if (!$initPoint) {
            Log::error('No hay init_point');
            return back()->with('error', 'No se pudo generar el enlace de pago.');
        }

        Log::info('URL de redirección', ['url' => $initPoint]);

        // Redirigir
        return redirect()->away($initPoint);

    } catch (MPApiException $e) {
        $responseContent = $e->getApiResponse()->getContent();

        Log::error('MPApiException', [
            'status' => $e->getStatusCode(),
            'response' => $responseContent,
        ]);

        return back()->with('error', 'Error: ' . ($responseContent['message'] ?? 'Error desconocido'));

    } catch (\Exception $e) {
        Log::error('Exception', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
        ]);

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

        Log::info('Callback success de MercadoPago', [
            'payment_id' => $paymentId,
            'status' => $status,
            'preference_id' => $preferenceId,
            'external_reference' => $externalRef,
        ]);

        // Buscar el pago por preference_id
        $pago = Pago::where('id_referencia', $preferenceId)->first();

        if ($pago && $status === 'approved') {
            // Marcar pago como completado
            $pago->estado = 'completado';
            $pago->save();

            // Buscar reserva asociada por external_reference
            $reservaId = $externalRef ? str_replace('reserva-', '', $externalRef) : null;
            $reserva = $reservaId ? Reserva::find($reservaId) : null;

            if ($reserva) {
                $reserva->estado = 'completada';
                $reserva->save();

                Log::info('Reserva completada', ['reserva_id' => $reserva->id]);
            }

            // Crear notificación para el usuario
            Notificacion::create([
                'user_id' => $pago->user_id,
                'titulo' => 'Pago Completado',
                'contenido' => 'Tu pago se acreditó correctamente. Tu reserva ha sido confirmada.',
                'tipo' => 'reserva',
                'leida' => false,
            ]);

            // Mostrar vista de éxito con mensaje y botón
            return view('mercadopago.success', [
                'reserva' => $reserva,
                'pago' => $pago,
            ]);
        }

        // Si el estado no es aprobado o no se encuentra el pago
        Log::warning('Pago no completado o no encontrado', [
            'preference_id' => $preferenceId,
            'status' => $status,
        ]);

        return redirect()->route('home')
            ->with('error', 'El pago no se completó o fue cancelado.');
    }

    /**
     * Redirección si el pago falla o se cancela
     */
    public function failure(Request $request)
    {
        Log::info('Callback failure de MercadoPago', [
            'params' => $request->all(),
        ]);

        return redirect()->route('home')
            ->with('error', 'El pago fue cancelado o falló. Puedes intentarlo nuevamente.');
    }
}
