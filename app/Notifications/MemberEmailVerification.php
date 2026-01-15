<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberEmailVerification extends Notification
{
    use Queueable;

    public $verificationToken;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $verificationToken)
    {
        $this->verificationToken = $verificationToken;
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
        $verificationUrl = route('member.verify-email', ['token' => $this->verificationToken]);

        return (new MailMessage)
            ->subject('Verify Your Email Address - CityLife Church')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Thank you for registering with CityLife Church.')
            ->line('Please click the button below to verify your email address:')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 24 hours.')
            ->line('After email verification, an administrator will review and approve your account.')
            ->line('If you did not create an account, no further action is required.');
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
