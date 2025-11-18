<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudMembresia;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class MembresiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();

        // Verificar si ya es socio
        $esSocio = $usuario->role === 'socio';

        // Verificar si tiene solicitud pendiente
        $solicitudPendiente = SolicitudMembresia::where('user_id', $usuario->id)
            ->where('estado', 'pendiente')
            ->first();

        return view('membresia.index', compact('esSocio', 'solicitudPendiente'));
    }

    public function iniciarPago(Request $request)
    {
        $usuario = Auth::user();

        // Verificar si ya es socio
        if ($usuario->role === 'socio') {
            return back()->with('error', 'Ya eres socio de Hakipadel');
        }

        // Verificar si ya tiene solicitud pendiente
        $solicitudExistente = SolicitudMembresia::where('user_id', $usuario->id)
            ->where('estado', 'pendiente')
            ->first();

        if ($solicitudExistente) {
            return back()->with('error', 'Ya tienes una solicitud de membresía pendiente');
        }

        try {
            // Configurar MercadoPago con la sintaxis correcta
            MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));

            $client = new PreferenceClient();

            $preferenceData = [
                "items" => [
                    [
                        "title" => "Membresía Hakipadel",
                        "quantity" => 1,
                        "unit_price" => 100000,
                        "currency_id" => "COP",
                    ]
                ],
                "back_urls" => [
                    "success" => route('membresia.success'),
                    "failure" => route('membresia.failure'),
                    "pending" => route('membresia.pending')
                ],
                "external_reference" => "membresia-{$usuario->id}",
            ];

            Log::info('Creando preferencia de membresía', $preferenceData);

            /** @var \MercadoPago\Resources\Preference $preference */
            $preference = $client->create($preferenceData);

            // Crear solicitud de membresía
            $solicitud = SolicitudMembresia::create([
                'user_id' => $usuario->id,
                'estado' => 'pendiente',
                'payment_id' => $preference->id,
                'monto' => 100000
            ]);

            // Crear notificación para administradores
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notificacion::create([
                    'user_id' => $admin->id,
                    'tipo' => 'membresia',
                    'titulo' => 'Nueva Solicitud de Membresía',
                    'contenido' => $usuario->name . ' ha solicitado ser socio. Revisa el panel de administración.',
                    'leida' => false
                ]);
            }

            $initPoint = $preference->init_point ?? $preference->sandbox_init_point;

            if (!$initPoint) {
                return back()->with('error', 'No se pudo generar el enlace de pago.');
            }

            return redirect()->away($initPoint);

        } catch (MPApiException $e) {
            $responseContent = $e->getApiResponse()->getContent();
            Log::error('MPApiException en membresía', ['response' => $responseContent]);
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());

        } catch (\Exception $e) {
            Log::error('Exception en membresía', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        return view('membresia.success');
    }

    public function failure(Request $request)
    {
        return view('membresia.failure');
    }

    public function pending(Request $request)
    {
        return view('membresia.pending');
    }
}
