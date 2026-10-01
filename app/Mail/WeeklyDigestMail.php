<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Collection;

class WeeklyDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Collection $projects;
    public string $portalUrl;

    public function __construct(User $user, Collection $projects)
    {
        $this->user = $user;
        $this->projects = $projects;
        $this->portalUrl = route('portal.dashboard');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📊 Resumen Semanal de Avance y Métricas - Software Tech',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly_digest',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
