<?php

namespace App\Notifications;

use App\Models\RegistrationInterest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRegistrationInterest extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Membership Interest - ' . $this->interest->email)
            ->view('emails.admin.new-registration-interest', [
                'interest' => $this->interest,
                'admin' => $notifiable,
            ]);
    }

    /**
     * Get the array representation of the notification (for database).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'interest_id' => $this->interest->id,
            'email' => $this->interest->email,
            'submitted_at' => $this->interest->created_at,
            'message' => 'New membership interest from ' . $this->interest->email,
        ];
    }
}
