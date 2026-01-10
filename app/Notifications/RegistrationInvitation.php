<?php

namespace App\Notifications;

use App\Models\RegistrationInterest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    public $interest;

    /**
     * Create a new notification instance.
     */
    public function __construct(RegistrationInterest $interest)
    {
        $this->interest = $interest;
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
        $registrationUrl = route('register.with-token', ['token' => $this->interest->token]);

        return (new MailMessage)
            ->subject('Welcome to CityLife Church - Complete Your Registration')
            ->view('emails.registration-invitation', [
                'registrationUrl' => $registrationUrl,
                'interest' => $this->interest
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'interest_id' => $this->interest->id,
            'email' => $this->interest->email,
        ];
    }
}
