<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickUpWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $tokenClickUp = config('services.clickup.token');

        // Extraer el ID de lista desde la carga del webhook (eventos de tareas)
        $listId = $request->input('list_id')
            ?? $request->input('task.list.id')
            ?? $request->input('history_items.0.parent_id');

        if (!$listId) {
            return response()->json(['status' => 'no_list_id'], 200);
        }

        $proyecto = Project::where('clickup_list_id', $listId)->first();

        if (!$proyecto) {
            return response()->json(['status' => 'ignored'], 200);
        }

        // Recalcular únicamente el porcentaje de avance del proyecto basado en tareas completadas
        if ($tokenClickUp) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $tokenClickUp,
                ])->timeout(5)->get("https://api.clickup.com/api/v2/list/{$listId}/task", [
                            'include_closed' => true
                        ]);

                if ($response->successful()) {
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

                    if ($proyecto->progreso != $nuevoProgreso) {
                        $proyecto->progreso = $nuevoProgreso;

                        if ($nuevoProgreso == 100) {
                            $proyecto->estado = 'Finalizado';
                        } elseif ($proyecto->estado === 'Prospecto' && $nuevoProgreso > 0) {
                            $proyecto->estado = 'En Desarrollo';
                        }

                        $proyecto->save();
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error de conexión con ClickUp al calcular progreso: " . $e->getMessage());
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
