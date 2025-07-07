<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailRegisteredUser extends Mailable
{
    // use Queueable, SerializesModels;
    use SerializesModels;

    private User $user;
    private String $password;
    private $type_email;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, String $password, $type_email)
    {
        //
        $this->user = $user;
        $this->password = $password;
        $this->type_email = $type_email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Mail Registered User',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mails.SendMailRegisteredUser',
            with:[
                'user' => $this->user,
                'password' => $this->password,
                'type_email' => $this->type_email,
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
