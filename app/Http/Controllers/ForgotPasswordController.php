<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return Inertia::render('acceso/forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,nombre_usuario',
        ], [
            'email.exists' => 'No encontramos una cuenta con este correo electrónico.'
        ]);

        $usuario = Usuario::where('nombre_usuario', $request->email)->firstOrFail();
        
        // Crear token con el timestamp actual (cuando se genera el enlace)
        $tokenCreatedAt = Carbon::now();
        
        $token = Crypt::encryptString(json_encode([
            'email' => $usuario->nombre_usuario,
            'created_at' => $tokenCreatedAt->timestamp,
            'expires' => $tokenCreatedAt->copy()->addMinutes(10)->timestamp
        ]));
        
        $resetUrl = route('password.reset', ['token' => $token]);
        
        try {
            Mail::send('mail.reset-password-link', [
                'nombre' => $this->getNombrePersona($usuario),
                'reset_url' => $resetUrl,
                'expires_minutes' => 10
            ], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Recuperación de contraseña - Sistema de Residencias');
            });

            return redirect()->route('Inicio_Sesion')
                ->with('success', 'Se ha enviado un enlace de recuperación a tu correo. Válido por 10 minutos y un solo uso.');

        } catch (\Exception $e) {
            //\Log::error('Error al enviar correo: ' . $e->getMessage());
            return back()->with('error', 'Error al enviar el correo. Intenta más tarde.');
        }
    }

    public function showResetForm($token)
    {
        return Inertia::render('acceso/reset-password', [
            'token' => $token
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Descifrar token
            $data = json_decode(Crypt::decryptString($request->token), true);
            
            $now = Carbon::now();
            $tokenCreatedAt = Carbon::createFromTimestamp($data['created_at']);
            $tokenExpiresAt = Carbon::createFromTimestamp($data['expires']);
            
            //  Verificar expiración por tiempo
            if ($now->gt($tokenExpiresAt)) {
                return redirect()->route('password.request')
                    ->with('error', 'El enlace de recuperación ha expirado. Solicita uno nuevo.');
            }
            
            // Buscar al usuario
            $usuario = Usuario::where('nombre_usuario', $data['email'])->firstOrFail();
            
            // Verificar si la contraseña fue cambiada DESPUÉS de crear el token
            // Si el usuario cambió su contraseña en los últimos 10 minutos, el token es inválido
            $ultimoCambio = $usuario->updated_at;
            
            if ($ultimoCambio && $ultimoCambio->gt($tokenCreatedAt)) {
                return redirect()->route('password.request')
                    ->with('error', 'La contraseña fue modificada recientemente. Solicita un nuevo enlace de recuperación.');
            }
            
            // Verificación adicional: si el updated_at es posterior a la creación del token
            // significa que ya se usó este token o se cambió por otro medio
            if ($ultimoCambio && $ultimoCambio->timestamp > $data['created_at']) {
                return redirect()->route('password.request')
                    ->with('error', 'Este enlace de recuperación ya fue utilizado. Solicita uno nuevo.');
            }
            
            // Actualizar contraseña (esto automáticamente cambia updated_at)
            $usuario->contraseña = Hash::make($request->password);
            $usuario->save();
            
            //\Log::info('Contraseña actualizada para usuario', ['email' => $data['email']]);
            
            return redirect()->route('Inicio_Sesion')
                ->with('success', 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
                
        } catch (\Exception $e) {
            \Log::error('Error al resetear contraseña: ' . $e->getMessage());
            return redirect()->route('password.request')
                ->with('error', 'Enlace inválido o expirado. Solicita una nueva recuperación.');
        }
    }
    
    private function getNombrePersona($usuario)
    {
        $persona = $usuario->usa;
        return $persona ? ($persona->nombre . ' ' . ($persona->apellido_paterno ?? '')) : 'Usuario';
    }
}