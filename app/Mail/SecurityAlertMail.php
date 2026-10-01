<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SecurityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $eventType;
    public string $description;
    public ?string $ip;
    public ?string $userAgent;
    public string $portalUrl;

    public function __construct(User $user, string $eventType, string $description, ?string $ip = null, ?string $userAgent = null)
    {
        $this->user = $user;
        $this->eventType = $eventType;
        $this->description = $description;
        $this->ip = $ip ?: request()->ip();
        $this->userAgent = $userAgent ?: request()->userAgent();
        $this->portalUrl = route('portal.configuracion');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔒 Alerta de Seguridad: {$this->eventType} - Software Tech",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.security_alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
