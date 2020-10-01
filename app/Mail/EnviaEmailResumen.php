<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviaEmailResumen extends Mailable
{
    use Queueable, SerializesModels;
    
    public $subject = 'Prueba de envío mail req vencidos semanal';
    public $valores;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($valores)
    {
        $this->valores = $valores;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.envia_resumen_req_vencidos');
    }
}
