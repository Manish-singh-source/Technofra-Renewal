<?php

namespace App\Mail;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $service;
    public $emailSubject;
    public $emailMessage;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Service $service
     * @param string $subject
     * @param string $message
     */
    public function __construct(Service $service, string $subject, string $message)
    {
        $this->service = $service;
        $this->emailSubject = $subject;
        $this->emailMessage = $message;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.renewal',
            with: [
                'service' => $this->service,
                'emailMessage' => $this->emailMessage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
