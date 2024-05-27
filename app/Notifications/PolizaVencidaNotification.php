<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Poliza;

class PolizaVencidaNotification extends Notification
{
    use Queueable;

    protected $poliza;

    /**
     * Create a notification instance.
     *
     * @param  Poliza  $poliza
     * @return void
     */
    public function __construct(Poliza $poliza)
    {
        $this->poliza = $poliza;
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
            ->subject('Su póliza ha vencido')
            ->line('Estimado cliente,')
            ->line('La póliza ' . $this->poliza->nombre . ' ha vencido.')
            ->line('Por favor, realice el pago correspondiente para mantener su póliza activa.')
            ->line('Gracias por su atención.');
    }
}
