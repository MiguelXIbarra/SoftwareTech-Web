<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\NotificationService;

class SendWeeklyDigestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:send-weekly-digest {--user= : ID de usuario específico opcional}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía el resumen semanal por correo a todos los usuarios con la preferencia habilitada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificUserId = $this->option('user');

        if ($specificUserId) {
            $user = User::find($specificUserId);
            if (!$user) {
                $this->error("Usuario con ID {$specificUserId} no encontrado.");
                return 1;
            }
            $sent = NotificationService::sendWeeklyDigest($user);
            if ($sent) {
                $this->info("Resumen semanal enviado exitosamente a {$user->email}.");
            } else {
                $this->warn("No se envió el resumen a {$user->email} (preferencia deshabilitada o sin proyectos).");
            }
            return 0;
        }

        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if ($user->getNotificationPreference('notif_weekly_digest', true)) {
                $sent = NotificationService::sendWeeklyDigest($user);
                if ($sent) {
                    $count++;
                    $this->line("Resumen enviado a: {$user->email}");
                }
            }
        }

        $this->info("Proceso completado. Se enviaron {$count} resúmenes semanales.");
        return 0;
    }
}
