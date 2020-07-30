<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class Mail_info extends Notification
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
                    ->subject('Detalle de requerimiento '.$this->notifiable->idReq)
                    ->greeting('Hola')
                    ->line('Este es el estado actual del requerimiento:')
                    ->line(new HtmlString('<strong>Requerimiento: '.$this->notifiable->idReq.'</strong>'))
                    ->line(new HtmlString('<strong>Solicitud: '.$this->notifiable->sol.'</strong>'))
                    ->line('Porcentaje avance: '.$this->notifiable->porcentaje."%")
                    ->line('Ultimo avance ingresado:')
                    ->line(new HtmlString('<p align="justify">'.$this->notifiable->avance.'</p>'))
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
