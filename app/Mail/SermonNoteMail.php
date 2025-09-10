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

class SermonNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public TeachingSeries $series;
    public string $recipientEmail;
    public string $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(TeachingSeries $series, string $recipientEmail, string $recipientName = '')
    {
        $this->series = $series;
        $this->recipientEmail = $recipientEmail;
        $this->recipientName = $recipientName ?: 'Friend';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sermon Notes: ' . $this->series->title,
            to: [$this->recipientEmail],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sermon-note',
            with: [
                'series' => $this->series,
                'recipientName' => $this->recipientName,
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

        if ($this->series->sermon_notes) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->series->sermon_notes)
                ->as($this->series->title . ' - Sermon Notes.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
