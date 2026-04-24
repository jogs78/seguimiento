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
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
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

        $persona = $usuario->usa;
        
        // Obtener el tipo de remitente (usuario autenticado)
        $remitente = auth()->user();
        $remitenteTipo = $this->getRemitenteTipo($remitente);
        
        return Inertia::render('mail/correo', [
            'usuario' => [
                'id' => $persona->id,
                'nombre' => $persona->nombre ?? '',
                'apellido_paterno' => $persona->apellido_paterno ?? '',
                'apellido_materno' => $persona->apellido_materno ?? '',
                'correo' => $usuario->nombre_usuario,
                'tipo' => $type,
                'nombre_completo' => trim(($persona->nombre ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''))
            ],
            'remitente' => [
                'tipo' => $remitenteTipo,
                'nombre' => $this->getRemitenteNombre($remitente)
            ]
        ]);
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
            // Obtener el destinatario y remitente
            $destinatarioNombre = $request->input('destinatario_nombre', 'Usuario');
            $remitente = auth()->user();
            $remitenteTipo = $this->getRemitenteTipo($remitente);
            $remitenteNombre = $this->getRemitenteNombre($remitente);
            
            // Enviar correo usando la plantilla HTML
            Mail::send('mail.plantilla', [
                'subject' => $request->subject,
                'content' => $request->content,
                'destinatario_nombre' => $destinatarioNombre,
                'remitente_tipo' => $remitenteTipo,
                'remitente_nombre' => $remitenteNombre
            ], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject($request->subject);
            });

            return redirect()->back()->with('success', 'Correo enviado con éxito');
        } catch (Exception $e) {
            Log::error('Error al enviar el correo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo enviar el correo. Intenta más tarde.');
        }
    }
    
    private function getRemitenteTipo($remitente)
    {
        if (!$remitente) return 'Sistema';
        
        $tipo = $remitente->usa_type;
        
        $tipos = [
            'App\\Models\\Coordinador' => 'Coordinador',
            'App\\Models\\Estudiante' => 'Estudiante',
            'App\\Models\\Asesor' => 'Asesor Interno',
            'App\\Models\\Externo' => 'Asesor Externo',
        ];
        
        return $tipos[$tipo] ?? 'Usuario del Sistema';
    }
    
    private function getRemitenteNombre($remitente)
    {
        if (!$remitente || !$remitente->usa) return 'Sistema de Residencias';
        
        $persona = $remitente->usa;
        $nombre = trim(($persona->nombre ?? '') . ' ' . ($persona->apellido_paterno ?? '') . ' ' . ($persona->apellido_materno ?? ''));
        
        return $nombre ?: 'Usuario del Sistema';
    }
}
