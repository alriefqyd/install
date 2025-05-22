<?php

namespace App\Mail;

use App\Models\Engineers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSubmissionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'New Loop Number Request Submitted';
        return new Envelope(
            subject: $subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $requestor = Engineers::where('id', $this->data->engineers_id)->first()->name;
        return new Content(
            view: 'loopNumber.email.success',
            with: [
                'data' => $this->data,
                'requestor' => $requestor,
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
        $attachments = [
            Attachment::fromPath(storage_path("app/public/{$this->data->p_and_id_document}"))
                ->as(basename($this->data->p_and_id_document))
        ];

        if (!empty($this->data->hmi_document)) {
            $attachments[] = Attachment::fromPath(storage_path("app/public/{$this->data->hmi_document}"))
                ->as(basename($this->data->hmi_document));
        }

        return  $attachments;
    }
}
