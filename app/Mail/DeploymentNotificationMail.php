<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Project;

class DeploymentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Project $project;
    public string $changeDescription;
    public string $portalUrl;

    public function __construct(User $user, Project $project, string $changeDescription = '')
    {
        $this->user = $user;
        $this->project = $project;
        $this->changeDescription = $changeDescription ?: "El estado actual del proyecto es '{$project->estado}' con un avance global del {$project->progreso}%.";
        $this->portalUrl = route('portal.proyecto', $project->id);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🚀 [{$this->project->nombre}] Actualización de Despliegue ({$this->project->estado}) - Software Tech",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deployment_notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
