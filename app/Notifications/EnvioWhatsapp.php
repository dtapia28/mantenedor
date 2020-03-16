<?php

namespace App\Notifications;

use App\Channels\Messages\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Requerimiento;
use App\Solicitante;
use App\Resolutor;


class EnvioWhatsapp extends Notification
{
  use Queueable;


  public $requerimiento;
  
  public function __construct(Requerimiento $requerimiento)
  {
    $this->requerimiento = $requerimiento;
  }
  
  public function via($notifiable)
  {
    return [WhatsAppChannel::class];
  }
  
  public function toWhatsApp($notifiable)
  {
    $solicitante = Solicitante::where('id', $this->requerimiento->idSolicitante)->first();
    $resolutor = Resolutor::where('id', $this->requerimiento->resolutor)->first();
    $link = "app.kinchika.com/requerimientos/".$this->requerimiento->id;
    
    return (new WhatsAppMessage)
        ->content("Hola, soy Kinchika y te estoy enviando este mensaje porque has sido asignado como resolutor al requerimiento {$this->requerimiento->id2}. La solicitud del requerimiento es: {$this->requerimiento->textoRequerimiento}.\n"
        ."Los datos del requerimiento son:\nID: {$this->requerimiento->id2}\nSolicitante: {$solicitante->nombreSolicitante}"
        ."\nResolutor: {$resolutor->nombreResolutor}\n Puedes ver con más detalle el requerimiento en el siguiente link: {$link}");
  }
}
