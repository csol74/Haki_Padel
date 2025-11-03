<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactoController extends Controller
{
    /**
     * Mostrar la página de contacto.
     */
    public function index()
    {
        return view('contacto.index');
    }

    /**
     * Procesar el formulario de contacto.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'tipo_consulta' => 'required|string|in:reservas,clases,torneos,instalaciones,otro',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string|min:10',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'tipo_consulta.required' => 'Debe seleccionar un tipo de consulta.',
            'asunto.required' => 'El asunto es obligatorio.',
            'mensaje.required' => 'El mensaje es obligatorio.',
            'mensaje.min' => 'El mensaje debe tener al menos 10 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Aquí puedes enviar el email o guardar en base de datos
            // Por ahora solo simulamos el envío exitoso
            
            return redirect()->back()->with('success', '¡Mensaje enviado exitosamente! Te responderemos pronto.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un problema al enviar tu mensaje. Inténtalo de nuevo.');
        }
    }
}