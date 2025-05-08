<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendLotteryNumbersMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $combinations;

    /**
     * Create a new notification instance.
     */
    public function __construct($combinations)
    {
        $this->combinations = $combinations;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('rodmayes@gmail.com', 'Rodmayes'),
            subject: 'Send Lottery Magik Numbers',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sendLotteryNumbers',
            with: ['combinations' => $this->combinations]
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
