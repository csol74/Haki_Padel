<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudMembresia;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SolicitudMembresiaController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudMembresia::with(['usuario', 'aprobadoPor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.membresias', compact('solicitudes'));
    }

    public function aprobar($id)
    {
        $solicitud = SolicitudMembresia::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada');
        }

        // ⭐⭐ DEBUG: Verificar datos antes de actualizar
        \Log::info('=== APROBANDO MEMBRESÍA ===', [
            'solicitud_id' => $solicitud->id,
            'user_id' => $solicitud->user_id,
            'usuario_actual_role' => $solicitud->usuario ? $solicitud->usuario->role : 'NO ENCONTRADO'
        ]);

        // Actualizar solicitud
        $solicitud->update([
            'estado' => 'aprobada',
            'fecha_aprobacion' => now(),
            'aprobada_por' => Auth::id()
        ]);

        // ⭐⭐ ACTUALIZAR ROL DEL USUARIO A SOCIO - FORMA CORRECTA ⭐⭐
        $usuario = User::find($solicitud->user_id);
        if ($usuario) {
            // Usar save() en lugar de update() para asegurar que funcione
            $usuario->role = 'socio';
            $usuario->save();

            \Log::info('=== USUARIO ACTUALIZADO ===', [
                'user_id' => $usuario->id,
                'nuevo_role' => $usuario->role,
                'email' => $usuario->email
            ]);
        } else {
            \Log::error('Usuario no encontrado para la solicitud', ['solicitud_id' => $solicitud->id]);
            return back()->with('error', 'Usuario no encontrado');
        }

        // Notificar al usuario
        Notificacion::create([
            'user_id' => $usuario->id,
            'tipo' => 'membresia',
            'titulo' => '¡Membresía Aprobada!',
            'contenido' => '¡Felicidades! Tu solicitud de membresía ha sido aprobada. Ahora eres socio y disfrutas de descuentos exclusivos.',
            'leida' => false
        ]);

        return back()->with('success', 'Membresía aprobada exitosamente. El usuario ahora es socio.');
    }

    public function rechazar(Request $request, $id)
    {
        $solicitud = SolicitudMembresia::findOrFail($id);

        if ($solicitud->estado !== 'pendiente') {
            return back()->with('error', 'Esta solicitud ya fue procesada');
        }

        // Actualizar solicitud
        $solicitud->update([
            'estado' => 'rechazada',
            'notas' => $request->input('notas', 'Solicitud rechazada por el administrador')
        ]);

        // Notificar al usuario
        Notificacion::create([
            'user_id' => $solicitud->user_id,
            'tipo' => 'membresia',
            'titulo' => 'Solicitud de Membresía Rechazada',
            'contenido' => 'Tu solicitud de membresía ha sido rechazada. ' . ($request->notas ? "Motivo: {$request->notas}" : 'Contacta con nosotros para más información.'),
            'leida' => false
        ]);

        return back()->with('success', 'Solicitud rechazada');
    }
}
