<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('clickup:register-webhook {url?}', function ($url = null) {
    $token = config('services.clickup.token');
    $folderId = config('services.clickup.folder_id');

    if (!$token || !$folderId) {
        $this->error('Faltan CLICK_UP_API_TOKEN o CLICK_UP_FOLDER_ID en el archivo .env o en config/services.php');
        return;
    }

    $webhookUrl = $url ?? (config('app.url') . '/api/clickup/webhook');
    $this->info("Registrando webhook en ClickUp para URL: {$webhookUrl}");

    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json'
        ])->post("https://api.clickup.com/api/v2/folder/{$folderId}/webhook", [
                    'endpoint' => $webhookUrl,
                    'events' => [
                        'listCreated',
                        'listDeleted',
                        'taskCreated',
                        'taskUpdated',
                        'taskStatusUpdated',
                        'taskDeleted'
                    ]
                ]);

        if ($response->successful()) {
            $this->info('Webhook registrado exitosamente en ClickUp!');
            $this->line(json_encode($response->json(), JSON_PRETTY_PRINT));
        } else {
            $this->error('Error al registrar webhook en ClickUp: ' . $response->body());
        }
    } catch (\Exception $e) {
        $this->error('Excepción: ' . $e->getMessage());
    }
})->purpose('Registrar webhook en ClickUp para sincronizar creación y eliminación de listas');

Artisan::command('clickup:sync-team', function (\App\Services\ClickUpService $clickUpService) {
    if (!$clickUpService->isConfigured()) {
        $this->error('ClickUp no está configurado en .env o config/services.php.');
        return;
    }

    $this->info('Consultando usuarios del Workspace de ClickUp...');
    $workspaceUsers = $clickUpService->getWorkspaceUsers();
    $this->info('Usuarios encontrados en ClickUp: ' . count($workspaceUsers));

    $teamUsers = \App\Models\User::whereIn('role', ['superadmin', 'admin', 'empleado'])->get();
    $this->info('Miembros del equipo local (excluyendo clientes): ' . $teamUsers->count());

    $rows = [];
    foreach ($teamUsers as $user) {
        $clickupId = $clickUpService->resolveClickUpUserId($user);
        $rows[] = [
            $user->id,
            $user->name,
            $user->email,
            $user->role,
            $clickupId ?? 'NO ENCONTRADO EN CLICKUP',
            $clickupId ? 'Vinculado' : 'Pendiente / Email no coincide',
        ];
    }

    $this->table(['ID', 'Nombre', 'Email', 'Rol', 'ClickUp User ID', 'Estado'], $rows);
})->purpose('Sincronizar usuarios del equipo operativo con IDs de ClickUp');

Artisan::command('clickup:sync-project {id}', function ($id, \App\Services\ClickUpService $clickUpService) {
    $project = \App\Models\Project::with(['developer', 'team', 'user'])->find($id);

    if (!$project) {
        $this->error("Proyecto #{$id} no encontrado.");
        return;
    }

    $this->info("Sincronizando proyecto: {$project->nombre} (Lista ID: {$project->clickup_list_id})");
    $result = $clickUpService->syncProjectMembers($project);
    $this->line(json_encode($result, JSON_PRETTY_PRINT));
})->purpose('Sincronizar líder y desarrolladores de un proyecto con su lista de ClickUp');

