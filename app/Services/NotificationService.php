<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use App\Models\Milestone;
use App\Mail\MilestoneNotificationMail;
use App\Mail\DeploymentNotificationMail;
use App\Mail\SecurityAlertMail;
use App\Mail\WeeklyDigestMail;
use App\Mail\TeamAssignmentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Envía notificación de Asignación de Proyecto a un miembro del equipo.
     */
    public static function notifyTeamAssignment(User $user, Project $project, string $roleAssigned = 'Colaborador'): bool
    {
        try {
            if (!$user->getNotificationPreference('notif_team_assignment', true)) {
                Log::info("Notificación de Asignación ignorada por preferencia del usuario: {$user->email}");
                return false;
            }

            Mail::to($user->email)->send(new TeamAssignmentMail($user, $project, $roleAssigned));
            Log::info("Notificación de Asignación enviada exitosamente a {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando notificación de asignación a {$user->email}: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Envía notificación de Hito/Entregable al cliente dueño del proyecto.
     */
    public static function notifyMilestone(Milestone $milestone, string $action = 'completed'): bool
    {
        try {
            $project = $milestone->project;
            if (!$project || !$project->user) {
                return false;
            }

            $user = $project->user;

            // Verificar si el usuario tiene activa la preferencia de Hitos
            if (!$user->getNotificationPreference('notif_milestones', true)) {
                Log::info("Notificación de Hito ignorada por preferencia del usuario: {$user->email}");
                return false;
            }

            Mail::to($user->email)->send(new MilestoneNotificationMail($user, $milestone, $action));
            Log::info("Notificación de Hito ({$action}) enviada exitosamente a {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando notificación de hito a {$milestone->project->user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía notificación de Despliegue / Cambio de Fase / Avance de Código.
     */
    public static function notifyDeployment(Project $project, string $changeDescription = ''): bool
    {
        try {
            $user = $project->user;
            if (!$user) {
                return false;
            }

            // Verificar si el usuario tiene activa la preferencia de Despliegues
            if (!$user->getNotificationPreference('notif_deployments', true)) {
                Log::info("Notificación de Despliegue ignorada por preferencia del usuario: {$user->email}");
                return false;
            }

            Mail::to($user->email)->send(new DeploymentNotificationMail($user, $project, $changeDescription));
            Log::info("Notificación de Despliegue enviada exitosamente a {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando notificación de despliegue: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía alerta de seguridad (Login, cambio de contraseña, 2FA activado/desactivado).
     */
    public static function notifySecurityAlert(User $user, string $eventType, string $description, ?string $ip = null, ?string $userAgent = null): bool
    {
        try {
            // Verificar si el usuario tiene activa la preferencia de Seguridad
            if (!$user->getNotificationPreference('notif_security', true)) {
                Log::info("Alerta de Seguridad ignorada por preferencia del usuario: {$user->email}");
                return false;
            }

            Mail::to($user->email)->send(new SecurityAlertMail($user, $eventType, $description, $ip, $userAgent));
            Log::info("Alerta de Seguridad ({$eventType}) enviada a {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando alerta de seguridad a {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía resumen semanal consolidado al usuario.
     */
    public static function sendWeeklyDigest(User $user): bool
    {
        try {
            if (!$user->getNotificationPreference('notif_weekly_digest', true)) {
                Log::info("Resumen semanal ignorado por preferencia del usuario: {$user->email}");
                return false;
            }

            $projects = Project::where('user_id', $user->id)->with('milestones')->get();
            if ($projects->isEmpty()) {
                return false;
            }

            Mail::to($user->email)->send(new WeeklyDigestMail($user, $projects));
            Log::info("Resumen semanal enviado exitosamente a {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error enviando resumen semanal a {$user->email}: " . $e->getMessage());
            return false;
        }
    }
}
