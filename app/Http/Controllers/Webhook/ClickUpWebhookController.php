<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\WebhookLog;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickUpWebhookController extends Controller
{
    /**
     * Procesa la notificación entrante de webhook de ClickUp con idempotencia y logging
     */
    public function handle(Request $request)
    {
        $tokenClickUp = config('services.clickup.token');
        $payload = $request->all();

        // Extraer identificadores del evento
        $eventId = $request->input('webhook_id') 
            ?? $request->input('event_id') 
            ?? ($request->input('task_id') ? $request->input('task_id') . '_' . time() : md5(json_encode($payload)));
        
        $eventType = $request->input('event') ?? 'task_update';
        
        $listId = $request->input('list_id')
            ?? $request->input('task.list.id')
            ?? $request->input('history_items.0.parent_id');

        // Control de Idempotencia: Si ya se procesó este eventId recientemente con éxito
        $existingLog = WebhookLog::where('event_id', $eventId)
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->first();

        if ($existingLog) {
            return response()->json(['status' => 'already_processed', 'log_id' => $existingLog->id], 200);
        }

        $proyecto = $listId ? Project::where('clickup_list_id', $listId)->first() : null;

        // Registrar entrada en el log de webhooks
        $webhookLog = WebhookLog::create([
            'provider' => 'clickup',
            'event_id' => $eventId,
            'event_type' => $eventType,
            'list_id' => $listId,
            'project_id' => $proyecto?->id,
            'payload' => $payload,
            'status' => 'pending',
            'attempts' => 1,
        ]);

        if (!$listId) {
            $webhookLog->update([
                'status' => 'ignored',
                'error_message' => 'No se encontró list_id en la carga del webhook.',
                'processed_at' => now(),
            ]);
            return response()->json(['status' => 'no_list_id', 'log_id' => $webhookLog->id], 200);
        }

        if (!$proyecto) {
            $webhookLog->update([
                'status' => 'ignored',
                'error_message' => "No se encontró ningún proyecto asociado a la lista de ClickUp: {$listId}",
                'processed_at' => now(),
            ]);
            return response()->json(['status' => 'ignored', 'log_id' => $webhookLog->id], 200);
        }

        // Procesar sincronización
        $result = self::syncProjectFromClickUpList($proyecto, $listId, $tokenClickUp);

        if ($result['success']) {
            $webhookLog->update([
                'status' => 'success',
                'processed_at' => now(),
                'error_message' => null,
            ]);
            return response()->json(['status' => 'success', 'progreso' => $result['progreso'], 'log_id' => $webhookLog->id], 200);
        } else {
            $webhookLog->update([
                'status' => 'failed',
                'error_message' => $result['error'],
                'processed_at' => now(),
            ]);
            return response()->json(['status' => 'failed', 'error' => $result['error'], 'log_id' => $webhookLog->id], 500);
        }
    }

    /**
     * Sincroniza el avance de un proyecto consultando las tareas de su lista en ClickUp
     */
    public static function syncProjectFromClickUpList(Project $proyecto, string $listId, ?string $tokenClickUp = null): array
    {
        $token = $tokenClickUp ?: config('services.clickup.token');

        if (!$token) {
            return ['success' => false, 'error' => 'Token de ClickUp no configurado'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(8)->get("https://api.clickup.com/api/v2/list/{$listId}/task", [
                'include_closed' => true
            ]);

            if (!$response->successful()) {
                $errorMsg = "ClickUp API Error ({$response->status()}): " . ($response->json('err') ?? $response->body());
                Log::warning($errorMsg);
                return ['success' => false, 'error' => $errorMsg];
            }

            $tasks = $response->json()['tasks'] ?? [];
            $totalTasks = count($tasks);

            if ($totalTasks > 0) {
                $closedTasks = 0;

                foreach ($tasks as $task) {
                    $statusType = strtolower($task['status']['type'] ?? '');
                    $statusName = strtolower($task['status']['status'] ?? '');

                    if (in_array($statusType, ['closed', 'done']) || in_array($statusName, ['complete', 'closed', 'done', 'completado', 'finalizado'])) {
                        $closedTasks++;
                    }
                }

                $nuevoProgreso = round(($closedTasks / $totalTasks) * 100, 1);
            } else {
                $nuevoProgreso = 0;
            }

            $progresoAnterior = $proyecto->progreso;
            $estadoAnterior = $proyecto->estado;

            if ($proyecto->progreso != $nuevoProgreso) {
                $proyecto->progreso = $nuevoProgreso;

                if ($nuevoProgreso == 100) {
                    $proyecto->estado = 'Finalizado';
                } elseif ($proyecto->estado === 'Prospecto' && $nuevoProgreso > 0) {
                    $proyecto->estado = 'En Desarrollo';
                }

                $proyecto->save();

                // Registrar en la bitácora de actividad
                ActivityLog::log(
                    'webhook_synced',
                    "Progreso actualizado vía sincronización de ClickUp de {$progresoAnterior}% a {$nuevoProgreso}% ({$closedTasks}/{$totalTasks} tareas cerradas).",
                    $proyecto->id,
                    [
                        'list_id' => $listId,
                        'total_tasks' => $totalTasks,
                        'closed_tasks' => $closedTasks,
                        'old_progress' => $progresoAnterior,
                        'new_progress' => $nuevoProgreso,
                        'old_status' => $estadoAnterior,
                        'new_status' => $proyecto->estado
                    ]
                );
            }

            return ['success' => true, 'progreso' => $nuevoProgreso, 'total_tasks' => $totalTasks];
        } catch (\Exception $e) {
            $errorMsg = "Excepción al conectar con ClickUp: " . $e->getMessage();
            Log::error($errorMsg);
            return ['success' => false, 'error' => $errorMsg];
        }
    }
}
