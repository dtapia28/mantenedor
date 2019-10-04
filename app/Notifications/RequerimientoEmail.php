<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequerimientoEmail extends Notification
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
                    ->subject('Requerimiento por vencer')
                    ->greeting('Hola, '.$this->notifiable->nombre)
                    ->line('Te informamos que la fecha del siguiente requerimiento está por vencer pronto:')
                    ->line('Requerimiento: '.$this->notifiable->idReq)
                    ->line('Porcentaje avance: '.$this->notifiable->porcentaje)
                    ->line('Fecha de cierre: '.$this->notifiable->fecha)
                    ->line('Si quieres ir al requerimiento indicado, por favor da clic en el siguiente botón:')
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
