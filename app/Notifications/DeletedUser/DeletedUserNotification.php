<?php

namespace App\Notifications\DeletedUser;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeletedUserNotification extends Notification
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

        return (new MailMessage)
            ->subject('Cuenta eliminada')
            ->greeting('Hola ' . $full_name)
            ->line('Este correo electrónico es para informarle que su cuenta en ' . env('APP_NAME') . ' ha sido eliminada.')
            ->line('Si tiene alguna pregunta o cree que esta acción se realizó por error, comuníquese con nosotros a ' . env('MAIL_FROM_ADDRESS'))
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
