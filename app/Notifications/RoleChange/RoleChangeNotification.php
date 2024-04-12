<?php

namespace App\Notifications\RoleChange;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RoleChangeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $full_name  = Str::title($notifiable->name . ' ' . $notifiable->last_name);
        $role       = $notifiable->getRoleNames()->first() == 'admin' ? 'Administrador' : 'Usuario';

        return (new MailMessage)
            ->subject('Cambio de rol')
            ->greeting('Hola ' . $full_name)
            ->line('Este correo electrónico es para informarle que un administrador cambió su rol en la plataforma ' . env('APP_NAME'))
            ->line('Su nuevo rol es: ' . $role)
            ->line('Si tiene alguna pregunta o inquietud sobre este cambio, no dude en comunicarse con nosotros en ' . env('MAIL_FROM_ADDRESS'))
            ->line('Gracias')
            ->salutation('Atentamente, ' . env('APP_NAME'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
