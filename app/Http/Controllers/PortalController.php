<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\TeamCorporation;
use App\Models\Milestone;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $proyectos = \App\Models\Project::where('user_id', $user->id)->get();

        return view('portal.dashboard', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $tokenClickUp = config('services.clickup.token');
        $folderId = config('services.clickup.folder_id');
        $clickupListId = null;

        if ($tokenClickUp && $folderId) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $tokenClickUp,
                    'Content-Type' => 'application/json',
                ])->timeout(10)->post("https://api.clickup.com/api/v2/folder/{$folderId}/list", [
                    'name' => $request->nombre,
                ]);

                if ($response->successful()) {
                    $clickupListId = $response->json('id') ?? null;
                } else {
                    Log::warning("No se pudo crear la lista en ClickUp: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Error de conexión al crear lista en ClickUp: " . $e->getMessage());
            }
        }

        $proyecto = Project::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'servicio' => $request->servicio,
            'user_id' => auth()->id(),
            'developer_id' => $request->developer_id,
            'priority' => $request->priority,
            'siguiente_entrega' => $request->siguiente_entrega,
            'estado' => 'Prospecto',
            'clickup_list_id' => $clickupListId,
        ]);
    }

    public function proyecto($id)
    {
        $user = auth()->user();

        if (in_array($user->role, ['superadmin', 'admin'])) {
            $proyecto = Project::with(['user', 'developer', 'team', 'milestones', 'assets'])->findOrFail($id);
        } else {
            $proyecto = Project::with(['user', 'developer', 'team', 'milestones', 'assets'])
                 ->where('user_id', $user->id)
                 ->findOrFail($id);
        }

        return view('portal.proyecto', compact('proyecto'));
    }

    public function adminDashboard()
    {
        $user = auth()->user();

        $totalProyectosGlobal = Project::count();
        $promedioProgresoGlobal = $totalProyectosGlobal > 0 ? round(Project::avg('progreso'), 1) : 0;

        $fasesGlobal = [
            'proyectosProspecto' => Project::where('estado', 'Prospecto')->count(),
            'proyectosDesarrollo' => Project::where('estado', 'En Desarrollo')->count(),
            'proyectosPruebas' => Project::where('estado', 'En Pruebas')->count(),
            'proyectosFinalizados' => Project::where('estado', 'Finalizado')->count(),
        ];

        $totalPorFasesGlobal = array_sum($fasesGlobal);

        if ($user->role === 'superadmin') {
            $proyectos = Project::with(['user', 'developer'])->get();
            $clientesRegistrados = User::where('role', 'cliente')->count();
            $proyectosActivos = Project::whereIn('estado', ['En Desarrollo', 'En Pruebas'])->count();
            $invitacionesPendientes = 0;
        } else {
            $proyectos = $user->proyectos()->with(['user', 'developer'])->get();
            $clientesRegistrados = 0;
            $proyectosActivos = $proyectos->whereIn('estado', ['En Desarrollo', 'En Pruebas'])->count();
            $invitacionesPendientes = 0;
        }

        return view('admin.dashboard', compact(
            'proyectos',
            'clientesRegistrados',
            'proyectosActivos',
            'invitacionesPendientes',
            'totalProyectosGlobal',
            'promedioProgresoGlobal',
            'fasesGlobal',
            'totalPorFasesGlobal'
        ));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
            'estado' => 'required|string|in:Prospecto,En Desarrollo,En Pruebas,Finalizado',
        ]);

        $proyecto = Project::findOrFail($request->id);
        $proyecto->estado = $request->estado;
        $proyecto->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.'
        ]);
    }

    public function adminProyectos()
    {
        $user = auth()->user();

        $priorityOrderSql = "CASE 
            WHEN LOWER(priority) = 'critico' THEN 1 
            WHEN LOWER(priority) = 'alto' THEN 2 
            WHEN LOWER(priority) = 'medio' THEN 3 
            WHEN LOWER(priority) = 'bajo' THEN 4 
            ELSE 5 
        END";

        $dateOrderSql = "CASE WHEN siguiente_entrega IS NULL OR siguiente_entrega = '' THEN 1 ELSE 0 END, siguiente_entrega ASC";

        if ($user->role === 'superadmin') {
            $proyectos = Project::with(['user', 'developer'])
                ->orderByRaw($priorityOrderSql)
                ->orderByRaw($dateOrderSql)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $proyectos = $user->proyectos()->with(['user', 'developer'])
                ->orderByRaw($priorityOrderSql)
                ->orderByRaw($dateOrderSql)
                ->orderBy('projects.id', 'desc')
                ->get();
        }

        $desarrolladores = User::whereIn('role', ['superadmin', 'admin', 'empleado'])->orderBy('name', 'asc')->get();

        return view('admin.proyectos', compact('proyectos', 'desarrolladores'));
    }

    public function updateLeader(Request $request)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Acceso denegado.'], 403);
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'developer_id' => 'required|exists:users,id',
        ]);

        $proyecto = Project::findOrFail($request->project_id);
        $proyecto->developer_id = $request->developer_id;
        $proyecto->save();

        // Si no está en la tabla de equipo del proyecto, agregarlo
        $existeEnEquipo = $proyecto->team()->where('user_id', $request->developer_id)->exists();
        if (!$existeEnEquipo) {
            $proyecto->team()->attach($request->developer_id, [
                'importancia' => 'Crítica',
                'sueldo_proyecto' => 0,
            ]);
        }

        $nuevoLider = User::find($request->developer_id);

        return response()->json([
            'success' => true,
            'developer_id' => $request->developer_id,
            'developer_name' => $nuevoLider ? $nuevoLider->name : 'Sin asignar',
            'message' => 'Líder asignado exitosamente al proyecto.'
        ]);
    }

    public function syncClickUpManual(Request $request)
    {
        $tokenClickUp = config('services.clickup.token');
        if (!$tokenClickUp) {
            return response()->json(['success' => false, 'message' => 'Token de ClickUp no configurado.'], 400);
        }

        $proyectos = Project::whereNotNull('clickup_list_id')->get();
        $actualizados = 0;

        foreach ($proyectos as $proyecto) {
            try {
                $taskResponse = Http::withHeaders([
                    'Authorization' => $tokenClickUp,
                ])->timeout(5)->get("https://api.clickup.com/api/v2/list/{$proyecto->clickup_list_id}/task", [
                    'include_closed' => true
                ]);

                if ($taskResponse->successful()) {
                    $tasks = $taskResponse->json()['tasks'] ?? [];
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
                        $progresoReal = round(($closedTasks / $totalTasks) * 100, 1);
                    } else {
                        $progresoReal = 0;
                    }

                    if ($proyecto->progreso != $progresoReal) {
                        $proyecto->progreso = $progresoReal;
                        if ($progresoReal == 100) {
                            $proyecto->estado = 'Finalizado';
                        } elseif ($proyecto->estado === 'Prospecto' && $progresoReal > 0) {
                            $proyecto->estado = 'En Desarrollo';
                        }
                        $proyecto->save();
                        $actualizados++;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error sincronizando ClickUp: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Sincronización completada. {$actualizados} proyectos actualizados.",
            'actualizados' => $actualizados
        ]);
    }

    public function adminProyectosCrear()
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        $clientes = User::where('role', 'cliente')->orderBy('name')->get();
        $desarrolladores = User::whereIn('role', ['superadmin', 'admin', 'empleado'])->orderBy('name')->get();

        return view('admin.proyectos_crear', compact('clientes', 'desarrolladores'));
    }

    public function adminProyectosEditar($id)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        $proyecto = Project::with(['user', 'developer', 'team'])->findOrFail($id);
        $clientes = User::where('role', 'cliente')->orderBy('name')->get();
        $desarrolladores = User::whereIn('role', ['superadmin', 'admin', 'empleado'])->orderBy('name')->get();

        return view('admin.proyectos_editar', compact('proyecto', 'clientes', 'desarrolladores'));
    }

    public function adminProyectosUpdate(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'servicio' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'developer_id' => 'required|exists:users,id',
            'priority' => 'required|string|in:critico,alto,medio,bajo',
            'estado' => 'required|string|in:Prospecto,En Desarrollo,En Pruebas,Finalizado',
            'progreso' => 'nullable|numeric|min:0|max:100',
            'siguiente_entrega' => 'nullable|date',
            'clickup_list_id' => 'nullable|string|max:100',
        ]);

        $proyecto = Project::findOrFail($id);

        $proyecto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'servicio' => $request->servicio,
            'user_id' => $request->user_id,
            'developer_id' => $request->developer_id,
            'priority' => $request->priority,
            'estado' => $request->estado,
            'progreso' => $request->progreso ?? $proyecto->progreso,
            'siguiente_entrega' => $request->siguiente_entrega,
            'clickup_list_id' => $request->clickup_list_id,
        ]);

        if ($request->developer_id) {
            $existeEnEquipo = $proyecto->team()->where('user_id', $request->developer_id)->exists();
            if (!$existeEnEquipo) {
                $proyecto->team()->attach($request->developer_id, [
                    'importancia' => ucfirst($request->priority),
                    'sueldo_proyecto' => 0,
                ]);
            }
        }

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function adminProyectosStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'servicio' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'developer_id' => 'required|exists:users,id',
            'priority' => 'required|string',
            'siguiente_entrega' => 'nullable|date',
        ]);

        $tokenClickUp = config('services.clickup.token');
        $folderId = config('services.clickup.folder_id');
        $clickupListId = null;

        if ($tokenClickUp && $folderId) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $tokenClickUp,
                    'Content-Type' => 'application/json',
                ])->timeout(10)->post("https://api.clickup.com/api/v2/folder/{$folderId}/list", [
                    'name' => $request->nombre,
                ]);

                if ($response->successful()) {
                    $clickupListId = $response->json('id') ?? null;
                } else {
                    Log::warning("No se pudo crear la lista en ClickUp: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("Error de conexión al crear lista en ClickUp: " . $e->getMessage());
            }
        }

        $proyecto = \App\Models\Project::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'servicio' => $request->servicio,
            'user_id' => $request->user_id,
            'developer_id' => $request->developer_id,
            'priority' => $request->priority,
            'siguiente_entrega' => $request->siguiente_entrega,
            'estado' => 'Prospecto',
            'clickup_list_id' => $clickupListId,
        ]);

        $proyecto->team()->attach($request->developer_id, [
            'importancia' => ucfirst($request->priority),
            'sueldo_proyecto' => 0,
        ]);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto registrado y asignado exitosamente.');
    }

    public function getProyectoJson($id)
    {
        $proyecto = Project::with(['user', 'developer', 'team', 'milestones.payments', 'assets'])->findOrFail($id);

        return response()->json([
            'id' => $proyecto->id,
            'nombre' => $proyecto->nombre,
            'descripcion' => $proyecto->descripcion ?? 'Sin descripción técnica asignada.',
            'servicio' => $proyecto->servicio,
            'estado' => $proyecto->estado,
            'priority' => $proyecto->priority ?? 'medio',
            'progreso' => $proyecto->progreso ?? 0,
            'user' => $proyecto->user,
            'developer' => $proyecto->developer,
            'team' => $proyecto->team,
            'milestones' => $proyecto->milestones,
            'assets' => $proyecto->assets,
        ]);
    }
    public function adminClientes()
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        $clientes = User::where('role', 'cliente')->orderBy('created_at', 'desc')->get();
        return view('admin.clientes', compact('clientes'));
    }

    public function adminEquipo()
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $miembros = User::whereIn('role', ['superadmin', 'admin', 'empleado'])
            ->with(['corporation', 'proyectos', 'proyectosLiderados'])
            ->orderBy('role', 'asc')
            ->get();

        return view('admin.equipo.index', compact('miembros'));
    }

    public function adminEquipoCrear()
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $proyectos = Project::all();
        return view('admin.equipo.crear', compact('proyectos'));
    }

    public function adminEquipoStore(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,empleado',
            'edad' => 'nullable|integer|min:18',
            'capacity' => 'required|integer|between:0,40',
            'proyectos' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
        ]);

        TeamCorporation::create([
            'user_id' => $user->id,
            'edad' => $data['edad'],
            'capacity' => $data['capacity'],
        ]);

        $syncData = [];
        if (!empty($request->proyectos)) {
            foreach ($request->proyectos as $proy) {
                if (!empty($proy['id'])) {
                    $syncData[$proy['id']] = [
                        'sueldo_proyecto' => $proy['sueldo'] ?? 0,
                        'importancia' => $proy['importancia'] ?? 'Media'
                    ];

                    if (!empty($proy['es_lider']) && $proy['es_lider'] == '1') {
                        Project::where('id', $proy['id'])->update(['developer_id' => $user->id]);
                    }
                }
            }
        }
        $user->proyectos()->sync($syncData);

        return redirect()->route('admin.equipo')->with('success', 'Miembro operativo integrado al Nexo.');
    }

    public function adminEquipoEditar($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $miembro = User::with(['corporation', 'proyectos'])->findOrFail($id);

        if (!in_array($miembro->role, ['superadmin', 'admin', 'empleado'])) {
            abort(404);
        }

        $proyectos = Project::all();
        return view('admin.equipo.editar', compact('miembro', 'proyectos'));
    }

    public function adminEquipoUpdate(Request $request, $id)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $miembro = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $miembro->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,admin,empleado',
            'edad' => 'nullable|integer|min:18',
            'capacity' => 'required|integer|between:0,40',
            'proyectos' => 'nullable|array',
        ]);

        $miembro->name = $data['name'];
        $miembro->email = $data['email'];
        $miembro->role = $data['role'];
        if (!empty($data['password'])) {
            $miembro->password = bcrypt($data['password']);
        }
        $miembro->save();

        $miembro->corporation()->updateOrCreate(
            ['user_id' => $miembro->id],
            ['edad' => $data['edad'], 'capacity' => $data['capacity']]
        );

        $syncData = [];
        if (!empty($request->proyectos)) {
            foreach ($request->proyectos as $proy) {
                if (!empty($proy['id'])) {
                    $syncData[$proy['id']] = [
                        'sueldo_proyecto' => $proy['sueldo'] ?? 0,
                        'importancia' => $proy['importancia'] ?? 'Media',
                    ];

                    $esLider = !empty($proy['es_lider']) && $proy['es_lider'] == '1';
                    if ($esLider) {
                        Project::where('id', $proy['id'])->update(['developer_id' => $miembro->id]);
                    } else {
                        $proyModel = Project::find($proy['id']);
                        if ($proyModel && $proyModel->developer_id == $miembro->id) {
                            $proyModel->developer_id = null;
                            $proyModel->save();
                        }
                    }
                }
            }
        }

        // Sincronizar tabla pivote project_user (miembro asignado a trabajar en el proyecto)
        $miembro->proyectos()->sync($syncData);

        return redirect()->route('admin.equipo')->with('success', 'Ecosistema de personal actualizado.');
    }

    public function adminEquipoDestroy($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $miembro = User::findOrFail($id);

        if ($miembro->id === auth()->id()) {
            abort(400);
        }

        $miembro->delete();

        return redirect()->route('admin.equipo')->with('success', 'Registro purgado del sistema.');
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'No tienes permisos para eliminar proyectos.');
        }

        $proyecto = \App\Models\Project::findOrFail($id);

        $tokenClickUp = config('services.clickup.token');
        if ($proyecto->clickup_list_id && $tokenClickUp) {
            try {
                Http::withHeaders([
                    'Authorization' => $tokenClickUp,
                ])->delete("https://api.clickup.com/api/v2/list/{$proyecto->clickup_list_id}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error al eliminar lista en ClickUp: " . $e->getMessage());
            }
        }

        $proyecto->delete();

        return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto eliminado correctamente y enviado a la papelera.');
    }

    public function restore($id)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'No tienes permisos para restaurar proyectos.');
        }

        $proyecto = \App\Models\Project::withTrashed()->findOrFail($id);
        $proyecto->restore();

        return redirect()->route('admin.proyectos.index')->with('success', 'Proyecto restaurado exitosamente.');
    }

    public function saveProjectMilestones(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $proyecto = Project::findOrFail($id);

        $request->validate([
            'milestones' => 'nullable|array',
            'milestones.*.name' => 'required|string|max:255',
            'milestones.*.cost' => 'required|numeric|min:0',
            'milestones.*.id' => 'nullable',
        ]);

        $incomingMilestones = $request->input('milestones', []);
        $incomingIds = [];

        foreach ($incomingMilestones as $item) {
            $cost = floatval($item['cost'] ?? 0);
            $name = trim($item['name'] ?? 'Hito');

            if (!empty($item['id'])) {
                $milestone = Milestone::where('project_id', $proyecto->id)->find($item['id']);
                if ($milestone) {
                    $milestone->update([
                        'name' => $name,
                        'cost' => $cost,
                    ]);
                    $incomingIds[] = $milestone->id;
                    continue;
                }
            }

            $newMilestone = Milestone::create([
                'project_id' => $proyecto->id,
                'name' => $name,
                'cost' => $cost,
                'is_paid' => false,
                'due_date' => now()->addMonths(1),
                'status' => 'pending',
            ]);
            $incomingIds[] = $newMilestone->id;
        }

        // Eliminar los hitos que fueron retirados en la UI
        Milestone::where('project_id', $proyecto->id)
            ->whereNotIn('id', $incomingIds)
            ->delete();

        $milestones = $proyecto->milestones()->get();

        return response()->json([
            'success' => true,
            'message' => 'Hitos financieros actualizados con éxito.',
            'milestones' => $milestones
        ]);
    }
}
