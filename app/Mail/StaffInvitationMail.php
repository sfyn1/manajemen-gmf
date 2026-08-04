<?php

namespace App\Mail;

use App\Models\StaffInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public StaffInvitation $invitation;
    public string $registrationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(StaffInvitation $invitation)
    {
        $this->invitation = $invitation;
        $this->registrationUrl = route('register.staff', ['token' => $invitation->token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $roleName = ucfirst($this->invitation->role);
        return new Envelope(
            subject: "Undangan Registrasi Staff ({$roleName}) — Gintung Master Fitness",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-invitation',
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
