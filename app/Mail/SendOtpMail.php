<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Implement if you want to queue emails
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable // Optional: implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The One-Time Password code.
     * Public properties are automatically available in the view.
     *
     * @var string
     */
    public string $otp;

    /**
     * Create a new message instance.
     *
     * @param string $otp The OTP code to send.
     * @return void
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     * Defines the subject and sender.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your ChefGenius Verification Code'
        );
    }

    /**
     * Get the message content definition.
     * Specifies the Markdown view to use.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.send-otp'
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
