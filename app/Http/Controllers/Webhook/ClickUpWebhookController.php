<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Http;

class ClickUpWebhookController extends Controller
{
    public function handle(Request $request)
    {
        if ($request->input('event') === 'listDeleted') {
            $listId = $request->input('list_id');

            if ($listId) {
                $proyecto = Project::where('clickup_list_id', $listId)->first();
                if ($proyecto) {
                    $proyecto->delete();
                    return response()->json(['status' => 'project_soft_deleted'], 200);
                }
            }
            return response()->json(['status' => 'list_deleted_ignored'], 200);
        }

        $listId = $request->input('list_id');

        if (!$listId) {
            return response()->json(['status' => 'no_list_id'], 200);
        }

        $proyecto = Project::where('clickup_list_id', $listId)->first();

        if (!$proyecto) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $tokenClickUp = env('CLICK_UP_API_TOKEN');

        if ($tokenClickUp) {
            $response = Http::withHeaders([
                'Authorization' => $tokenClickUp,
            ])->get("https://api.clickup.com/api/v2/list/{$listId}/task", [
                'include_closed' => true
            ]);

            if ($response->successful()) {
                $tasks = $response->json()['tasks'] ?? [];
                $totalTasks = count($tasks);

                if ($totalTasks > 0) {
                    $closedTasks = 0;

                    foreach ($tasks as $task) {
                        if (isset($task['status']['type']) && $task['status']['type'] === 'closed') {
                            $closedTasks++;
                        }
                    }

                    $nuevoProgreso = round(($closedTasks / $totalTasks) * 100, 1);

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
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
