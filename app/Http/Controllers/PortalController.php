<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $proyectos = Project::where('user_id', $user->id)->get();

        return view('portal.dashboard', compact('proyectos'));
    }

    public function adminDashboard()
    {
        $proyectosActivos = class_exists(Project::class) ? Project::count() : 0;

        $clientesRegistrados = User::where('role', 'cliente')->where('active', 1)->count();
        $invitacionesPendientes = User::where('role', 'cliente')->where('active', 0)->count();
        $clientesDesactivados = User::where('role', 'cliente')->where('active', 2)->count();

        return view('admin.dashboard', compact('proyectosActivos', 'clientesRegistrados', 'invitacionesPendientes', 'clientesDesactivados'));
    }

    public function proyecto($id)
    {
        $user = Auth::user();
        $proyecto = Project::with(['user', 'developer'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('portal.proyecto', compact('proyecto'));
    }

    public function adminClientes()
    {
        $clientes = User::where('role', 'cliente')->orderBy('created_at', 'desc')->get();
        return view('admin.clientes', compact('clientes'));
    }

    public function adminProyectos()
    {
        $proyectos = Project::with(['user', 'developer'])
            ->orderByRaw("FIELD(priority, 'critico', 'alto', 'medio', 'bajo') ASC")
            ->get();

        return view('admin.proyectos', compact('proyectos'));
    }

    public function adminProyectosCrear()
    {
        $clientes = User::where('role', 'cliente')->where('active', 1)->get();
        $desarrolladores = User::whereIn('role', ['superadmin', 'admin', 'empleado'])->get();

        return view('admin.proyectos_crear', compact('clientes', 'desarrolladores'));
    }

    public function adminProyectosStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'servicio' => 'required|in:IA,Desarrollo Web,Desarrollo Móvil,Ciberseguridad',
            'user_id' => 'required|exists:users,id',
            'developer_id' => 'required|exists:users,id',
            'priority' => 'required|in:critico,alto,medio,bajo',
            'siguiente_entrega' => 'nullable|date',
        ]);

        $clickupListId = null;
        $tokenClickUp = env('CLICK_UP_API_TOKEN');
        $folderId = env('CLICK_UP_FOLDER_ID');

        if ($tokenClickUp && $folderId) {
            $response = Http::withHeaders([
                'Authorization' => $tokenClickUp,
                'Content-Type' => 'application/json',
            ])->post("https://api.clickup.com/api/v2/folder/{$folderId}/list", [
                'name' => $request->nombre,
            ]);

            if ($response->successful()) {
                $clickupListId = $response->json()['id'] ?? null;
            }
        }

        Project::create([
            'nombre' => $request->nombre,
            'servicio' => $request->servicio,
            'user_id' => $request->user_id,
            'developer_id' => $request->developer_id,
            'priority' => $request->priority,
            'estado' => 'Prospecto',
            'progreso' => 0,
            'siguiente_entrega' => $request->siguiente_entrega,
            'clickup_list_id' => $clickupListId,
        ]);

        return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto registrado.');
    }

    public function handleClickUpWebhook(Request $request)
    {
        // Extraemos el parent_id desde el historial del evento que manda ClickUp
        $listId = $request->input('history_items.0.parent_id') ?? $request->input('list_id');

        if (!$listId) {
            \Log::error("No se detectó ID de lista.");
            return response()->json(['status' => 'success']);
        }

        \Log::info("1. Webhook recibido con éxito para LIST_ID: " . $listId);

        // Buscamos el proyecto con el número largo (901417603153)
        $proyecto = Project::where('clickup_list_id', $listId)->first();

        if (!$proyecto) {
            \Log::warning("2. No se encontró ningún proyecto local con el ID: " . $listId);
            return response()->json(['status' => 'success']);
        }

        \Log::info("3. Proyecto local asociado encontrado: " . $proyecto->nombre);
        $tokenClickUp = env('CLICK_UP_API_TOKEN');
        $teamId = "90141374013";

        $urlGlobal = "https://api.clickup.com/api/v2/team/{$teamId}/task?list_ids[]={$listId}&include_closed=true";

        $response = Http::withHeaders([
            'Authorization' => $tokenClickUp,
        ])->get($urlGlobal);

        if ($response->successful()) {
            $tasks = $response->json()['tasks'] ?? [];
            $totalTasks = count($tasks);
            \Log::info("4. Total de tareas recuperadas de ClickUp: " . $totalTasks);

            if ($totalTasks > 0) {
                $closedTasks = count(array_filter($tasks, function($task) {
                    return isset($task['status']['type']) && $task['status']['type'] === 'closed';
                }));
                \Log::info("5. Tareas cerradas encontradas: " . $closedTasks);

                $nuevoProgreso = round(($closedTasks / $totalTasks) * 100);
                $proyecto->progreso = $nuevoProgreso;

                if ($nuevoProgreso >= 100) {
                    $proyecto->estado = 'Finalizado';
                } elseif ($nuevoProgreso > 0 && strtolower($proyecto->estado) === 'prospecto') {
                    $proyecto->estado = 'En Desarrollo';
                }

                $proyecto->save();
                \Log::info("6. ¡ÉXITO! Base de datos actualizada con progreso: " . $nuevoProgreso);
            }
        } else {
            \Log::error("Error al consultar tareas en ClickUp. Status: " . $response->status());
        }

        return response()->json(['status' => 'success']);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
            'estado' => 'required|string'
        ]);

        $proyecto = Project::findOrFail($request->id);
        $proyecto->estado = $request->estado;
        $proyecto->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $proyecto = Project::findOrFail($id);
        $tokenClickUp = env('CLICK_UP_API_TOKEN');

        if ($tokenClickUp && $proyecto->clickup_list_id) {
            Http::withHeaders([
                'Authorization' => $tokenClickUp,
            ])->delete("https://api.clickup.com/api/v2/list/{$proyecto->clickup_list_id}");
        }

        $proyecto->delete();

        return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto eliminado localmente y en ClickUp.');
    }

    public function getProyectoJson($id)
    {
        $proyecto = Project::with(['user', 'developer'])->findOrFail($id);
        return response()->json($proyecto);
    }
}
