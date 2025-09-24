<?php

namespace App\Http\Controllers;

use App\Mail\Correo;
use App\Models\Usuario;
use App\Models\Asesor;
use App\Models\Externo;
use App\Models\Estudiante;
use App\Models\Coordinador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;


class CorreoController extends Controller
{
    
    public function create($type, $id)
{
    $typeMap = [
        'estudiante' => \App\Models\Estudiante::class,
        'asesor' => \App\Models\Asesor::class,
        'externo' => \App\Models\Externo::class,
        'coordinador' => \App\Models\Coordinador::class,
    ];

    $realType = $typeMap[$type] ?? abort(404);

    $usuario = Usuario::where('usa_type', $realType)
                      ->where('usa_id', $id)
                      ->firstOrFail();

    return view('mail.correo', compact('usuario'));
}

        public function send(Request $request)
    {
        $request->validate([
        'email' => 'required|email',
        'subject' => 'required|string',
        'content' => 'required|string',
    ], [
        'email.required' => 'El Correo es obligatorio.',
        'email.email' => 'El Correo debe ser válido.',
        'subject.required' => 'El asunto es requerido.',
        'content.required' => 'Falta agregar contenido al correo.',
    ]);

        try {
            // Intentar enviar el correo
            Mail::raw($request->content, function ($message) use ($request) {
                $message->to($request->email)
                        ->subject($request->subject);
            });

            return redirect()->back()->with('success', 'Correo enviado con éxito');
        } catch (Exception $e) {
            // Registrar el error (opcional pero recomendado)
            Log::error('Error al enviar el correo: ' . $e->getMessage());

            // Redirigir con mensaje de error
            return redirect()->back()->with('error', 'No se pudo enviar el correo. Intenta más tarde.');
        }
    }
}
