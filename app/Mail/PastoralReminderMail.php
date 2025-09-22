<?php

namespace App\Mail;

use App\Models\PastoralNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PastoralReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public PastoralNotification $notification;
    public bool $isForMember;

    /**
     * Create a new message instance.
     */
    public function __construct(PastoralNotification $notification)
    {
        $this->notification = $notification;
        $this->isForMember = isset($notification->metadata['notification_target']) &&
                           $notification->metadata['notification_target'] === 'member';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notification->subject,
            from: env('MAIL_FROM_ADDRESS', 'noreply@citylifecc.com'),
            replyTo: $this->isForMember ? ['pastor@citylifecc.com'] : null,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->isForMember ? 'emails.pastoral.member-reminder' : 'emails.pastoral.staff-reminder',
            with: [
                'notification' => $this->notification,
                'member' => $this->notification->member,
                'reminder' => $this->notification->pastoralReminder,
                'isForMember' => $this->isForMember,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
