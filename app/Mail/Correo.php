<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Correo extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;

    /**
     * Create a new message instance.
     */
    public function __construct($nombre)
    {
        $this->nombre = $nombre;
    }

    public function build()//llamando a la vista
    {
        return $this->view('mail.correo')
                    ->subject('Bienvenido a nuestra aplicación');
    }

    
}
