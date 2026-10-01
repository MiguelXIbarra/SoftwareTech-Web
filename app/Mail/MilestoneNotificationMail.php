<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Milestone;
use App\Models\Project;

class MilestoneNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Milestone $milestone;
    public Project $project;
    public string $action;
    public string $portalUrl;

    /**
     * @param User $user
     * @param Milestone $milestone
     * @param string $action 'paid' | 'completed' | 'created' | 'updated'
     */
    public function __construct(User $user, Milestone $milestone, string $action = 'completed')
    {
        $this->user = $user;
        $this->milestone = $milestone;
        $this->project = $milestone->project;
        $this->action = $action;
        $this->portalUrl = route('portal.proyecto', $this->project->id);
    }

    public function envelope(): Envelope
    {
        $statusLabel = match($this->action) {
            'paid' => 'Hito Liquidado',
            'completed' => 'Hito Completado',
            'created' => 'Nuevo Hito Asignado',
            default => 'Actualización de Hito'
        };

        return new Envelope(
            subject: "📌 [{$this->project->nombre}] {$statusLabel}: {$this->milestone->name} - Software Tech",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.milestone_notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
