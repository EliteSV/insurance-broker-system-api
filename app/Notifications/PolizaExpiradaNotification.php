<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Poliza;

class PolizaExpiradaNotification extends Notification
{
    use Queueable;

    protected $poliza;

    /**
     * Create a new notification instance.
     *
     * @param Poliza $poliza
     */
    public function __construct(Poliza $poliza)
    {
        $this->poliza = $poliza;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Poliza Expirada')
            ->line('Su póliza "' . $this->poliza->nombre . '" ha expirado.')
            ->line('Por favor, póngase en contacto con su corredor de seguros.')
            ->line('Gracias por usar nuestro servicio.');
    }
}
