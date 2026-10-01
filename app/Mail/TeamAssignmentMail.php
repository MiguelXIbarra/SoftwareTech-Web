<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Project;

class TeamAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Project $project;
    public string $roleAssigned;
    public string $consoleUrl;

    public function __construct(User $user, Project $project, string $roleAssigned = 'Colaborador')
    {
        $this->user = $user;
        $this->project = $project;
        $this->roleAssigned = $roleAssigned;
        $this->consoleUrl = route('admin.proyectos.index');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "💼 Asignación a Proyecto: [{$this->project->nombre}] - Software Tech",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team_assignment',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
