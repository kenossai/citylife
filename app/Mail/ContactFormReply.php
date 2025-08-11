<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormReply extends Mailable
{
    use Queueable, SerializesModels;

    public ContactSubmission $submission;
    public string $replyMessage;
    public ?Contact $churchContact;
    public ?User $respondedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactSubmission $submission, string $replyMessage, ?User $respondedBy = null)
    {
        $this->submission = $submission;
        $this->replyMessage = $replyMessage;
        $this->respondedBy = $respondedBy;
        $this->churchContact = Contact::active()->first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . $this->submission->subject,
            from: new \Illuminate\Mail\Mailables\Address(
                $this->churchContact?->email ?? config('mail.from.address'),
                $this->churchContact?->church_name ?? config('app.name')
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form-reply',
            with: [
                'submission' => $this->submission,
                'replyMessage' => $this->replyMessage,
                'churchContact' => $this->churchContact,
                'respondedBy' => $this->respondedBy,
            ],
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
