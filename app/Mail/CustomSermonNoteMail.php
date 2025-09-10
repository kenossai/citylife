<?php

namespace App\Mail;

use App\Models\TeachingSeries;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class CustomSermonNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public TeachingSeries $series;
    public string $recipientEmail;
    public string $recipientName;
    public string $customSubject;
    public string $customMessage;
    public bool $attachNotes;

    /**
     * Create a new message instance.
     */
    public function __construct(
        TeachingSeries $series,
        string $recipientEmail,
        string $recipientName,
        string $customSubject,
        string $customMessage,
        bool $attachNotes = true
    ) {
        $this->series = $series;
        $this->recipientEmail = $recipientEmail;
        $this->recipientName = $recipientName ?: 'Friend';
        $this->customSubject = $customSubject;
        $this->customMessage = $customMessage;
        $this->attachNotes = $attachNotes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->customSubject,
            to: [$this->recipientEmail],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.custom-sermon-note',
            with: [
                'series' => $this->series,
                'recipientName' => $this->recipientName,
                'customMessage' => $this->customMessage,
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
        $attachments = [];

        if ($this->attachNotes && $this->series->sermon_notes) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->series->sermon_notes)
                ->as($this->series->title . ' - Sermon Notes.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
