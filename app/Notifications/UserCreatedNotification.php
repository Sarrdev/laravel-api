<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    public $email;
    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
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
        return (new MailMessage)
            ->subject('Votre compte a été créé')
            ->greeting('Bonjour ' . $notifiable->prenom . ' ' .$notifiable->nom)
            ->line('Votre compte a été créé avec succès.')
            ->line('Email : ' . $this->email)
            ->line('Mot de passe : ' . $this->password)
            ->line('Veuillez vous connecter et changer votre mot de passe dès que possible.')
            ->salutation('Cordialement, L\'équipe informatique SGF.')
            ->action('Notification Action', url('/'));
            
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
