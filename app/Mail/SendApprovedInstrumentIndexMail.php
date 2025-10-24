<?php

namespace App\Mail;

use App\Models\Engineers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendApprovedInstrumentIndexMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $ticket)
    {
        $this->data = $data;
        $this->ticket = $ticket;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Instrument Index Approved ['.$this->ticket.']',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'InstrumentIndex.approved',
            with: [
                'requestor' => $this->data->loopNumberRequest->engineers->name,
                'sessionId' => $this->data->loopNumberRequest->sessionId,
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
