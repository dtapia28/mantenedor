<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FinalizadoNotifi extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notifiable)
    {
        $this->notifiable = $notifiable;
    }    

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Requerimiento terminado')
                    ->greeting('Hola')
                    ->line('Estás recibiendo este correo porque el siguiente requerimiento ha cambiado de estado a "Por autorizar":')
                    ->line('Requerimiento: '.$this->notifiable->idReq)
                    ->line('Solicitante: '.$this->notifiable->solicitante)
                    ->line('Resolutor: '.$this->notifiable->nombre)
                    ->line('Solicitud: '.$this->notifiable->sol)
                    ->action('ver Requerimiento', url('/requerimientos/'.$this->notifiable->id.''))
                    ->salutation('Saludos, '. config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
