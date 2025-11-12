<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Notificacion;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PagoController extends Controller
{
    public function procesarPago(Request $request, $idReserva)
    {
        $reserva = Reserva::findOrFail($idReserva);

        // Simular proceso de pago (puedes integrar pasarela real después)
        $pagoExitoso = true;

        if ($pagoExitoso) {
            // Crear registro del pago
            $pago = Pago::create([
                'user_id' => Auth::id(),
                'concepto' => 'reserva',
                'id_referencia' => $reserva->id,
                'monto' => 35000, // puedes cambiar según el tipo de cancha
                'metodo_pago' => 'tarjeta',
                'estado' => 'completado',
            ]);

            // Actualizar estado de reserva
            $reserva->update(['estado' => 'completada']);

            // Crear notificación en BD
            Notificacion::create([
                'user_id' => Auth::id(),
                'titulo' => 'Pago completado',
                'contenido' => "Tu reserva para la cancha #{$reserva->id_cancha} el {$reserva->fecha} ha sido confirmada. ID de pago: {$pago->id}.",
                'tipo' => 'reserva',
            ]);

            // Enviar correo al usuario autenticado
            Mail::raw("Tu pago ha sido procesado exitosamente.\n\nDetalles:\nReserva: {$reserva->id}\nCancha: {$reserva->id_cancha}\nFecha: {$reserva->fecha}\nHora: {$reserva->hora_inicio}-{$reserva->hora_fin}\n\nGracias por tu pago.", function ($message) {
                $message->to(Auth::user()->email)
                        ->subject('Pago confirmado - Reserva completada');
            });

            // Redirigir a la pantalla de éxito
            return redirect()->route('reservas.exito', ['id' => $reserva->id])
                             ->with('success', 'Pago completado y reserva confirmada.');
        }

        return back()->with('error', 'El pago no se pudo completar. Intenta nuevamente.');
    }

    public function exito($id)
    {
        $reserva = Reserva::findOrFail($id);
        return view('reservas.exito', compact('reserva'));
    }

}
