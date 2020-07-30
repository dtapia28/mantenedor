<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    
    public $subject = 'Reporte Requerimientos';
    public $hoy, $ayer, $valores;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($hoy, $ayer, $valores)
    {
        $this->hoy = $hoy;
        $this->ayer = $ayer;
        $this->valores = $valores;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.envia_reporte_semanal');
    }
}
