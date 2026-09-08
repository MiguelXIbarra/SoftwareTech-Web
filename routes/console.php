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

