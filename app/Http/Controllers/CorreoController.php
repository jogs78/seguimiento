<?php

namespace App\Http\Controllers;

use App\Mail\Correo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class CorreoController extends Controller
{
    
    /*public function enviarCorreo()
    {
        Mail::to('contacto@ittg.edu.mx')->send(new Correo('Juan'));
        return redirect()->back()->with('success', 'Correo enviado');
    }*/

    public function create($id)
    {
        $usuario = Usuario::findOrFail($id); // busca al destinatario
        return view('mail.correo', compact('usuario'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:500',
            'content' => 'required|string',
        ]);

        // Enviar el correo
        Mail::raw($request->content, function ($message) use ($request) {
            $message->to($request->email)
                    ->subject($request->subject);
        });

        return redirect()->back()->with('success', 'Correo enviado con éxito');
    }
}
