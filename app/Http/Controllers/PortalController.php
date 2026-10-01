<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\TeamCorporation;
use App\Models\Milestone;
use App\Services\ClickUpService;
use App\Mail\EmployeeActivationMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $proyectos = \App\Models\Project::where('user_id', $user->id)->get();

        return view('portal.dashboard', compact('proyectos'));
    }

    public function configuracion()
    {
        $user = auth()->user();

        // Retrieve existing secret or generate new one using TwoFactorService
        $totpSecret = $user->two_factor_secret ?: \App\Services\TwoFactorService::generateSecretKey();
        $isTwoFactorActive = $user->hasTwoFactorEnabled();
        $qrCodeUrl = \App\Services\TwoFactorService::getQrCodeUrl($user->email, $totpSecret);
        $otpauthUri = \App\Services\TwoFactorService::getOtpAuthUri($user->email, $totpSecret);

        return view('portal.configuracion', compact('user', 'totpSecret', 'isTwoFactorActive', 'qrCodeUrl', 'otpauthUri'));
    }

    public function updatePerfil(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado por otro usuario.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('portal.configuracion')->with('success', 'Perfil corporativo actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
            'password.required' => 'Debes ingresar una nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual ingresada es incorrecta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Notificar alerta de seguridad
        \App\Services\NotificationService::notifySecurityAlert($user, 'Cambio de Contraseña', 'Tu contraseña de acceso al portal fue actualizada exitosamente.', $request->ip(), $request->userAgent());

        return redirect()->route('portal.configuracion')->with('success', 'Contraseña actualizada con éxito.');
    }

    public function updateTwoFactor(Request $request)
    {
        $user = auth()->user();
        $enabled = $request->has('two_factor_enabled');

        if ($enabled) {
            $secret = $request->input('two_factor_secret') ?: ($user->two_factor_secret ?: \App\Services\TwoFactorService::generateSecretKey());
            $user->two_factor_secret = $secret;
            $user->two_factor_confirmed_at = now();
            $user->save();

            // Notificar alerta de seguridad
            \App\Services\NotificationService::notifySecurityAlert($user, 'Activación de 2FA', 'La autenticación en dos pasos (2FA) ha sido activada y vinculada en tu cuenta corporativa.', $request->ip(), $request->userAgent());

            return redirect()->route('portal.configuracion')->with('success', 'Autenticación en dos pasos (2FA) activada y guardada correctamente.');
        } else {
            $user->two_factor_confirmed_at = null;
            $user->save();

            // Notificar alerta de seguridad
            \App\Services\NotificationService::notifySecurityAlert($user, 'Desactivación de 2FA', 'La autenticación en dos pasos (2FA) ha sido desactivada en tu cuenta corporativa.', $request->ip(), $request->userAgent());

            return redirect()->route('portal.configuracion')->with('success', 'Autenticación en dos pasos (2FA) desactivada con éxito.');
        }
    }

    public function updateNotificaciones(Request $request)
    {
        $user = auth()->user();
        $user->notification_preferences = [
            'notif_milestones' => $request->has('notif_milestones') ? 1 : 0,
            'notif_deployments' => $request->has('notif_deployments') ? 1 : 0,
            'notif_security' => $request->has('notif_security') ? 1 : 0,
            'notif_weekly_digest' => $request->has('notif_weekly_digest') ? 1 : 0,
        ];
        $user->save();

        return redirect()->route('portal.configuracion')->with('success', 'Preferencias de notificaciones guardadas exitosamente.');
    }

    public function adminConfiguracion()
    {
        $user = auth()->user();

        // Retrieve existing secret or generate new one using TwoFactorService
        $totpSecret = $user->two_factor_secret ?: \App\Services\TwoFactorService::generateSecretKey();
        $isTwoFactorActive = $user->hasTwoFactorEnabled();
        $qrCodeUrl = \App\Services\TwoFactorService::getQrCodeUrl($user->email, $totpSecret);
        $otpauthUri = \App\Services\TwoFactorService::getOtpAuthUri($user->email, $totpSecret);
        $corporation = $user->corporation;

        return view('admin.configuracion', compact('user', 'corporation', 'totpSecret', 'isTwoFactorActive', 'qrCodeUrl', 'otpauthUri'));
    }

    public function adminUpdatePerfil(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'capacity' => 'nullable|integer|between:0,40',
            'edad' => 'nullable|integer|min:18',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo ya se encuentra en uso por otro miembro.',
            'capacity.between' => 'La capacidad semanal debe estar entre 0 y 40 horas.',
            'edad.min' => 'La edad debe ser mayor o igual a 18 años.',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->has('capacity') || $request->has('edad')) {
            $user->corporation()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'capacity' => $request->input('capacity', 40),
                    'edad' => $request->input('edad'),
                ]
            );
        }

        return redirect()->route('admin.configuracion')->with('success', 'Perfil operativo actualizado correctamente.');
    }

    public function adminUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
            'password.required' => 'Debes ingresar una nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual ingresada es incorrecta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Notificar alerta de seguridad
        \App\Services\NotificationService::notifySecurityAlert($user, 'Cambio de Contraseña Operativa', 'Tu contraseña de acceso a la consola de administración fue actualizada exitosamente.', $request->ip(), $request->userAgent());

        return redirect()->route('admin.configuracion')->with('success', 'Contraseña operativa actualizada con éxito.');
    }

    public function adminUpdateTwoFactor(Request $request)
    {
        $user = auth()->user();
        $enabled = $request->has('two_factor_enabled');

        if ($enabled) {
            $secret = $request->input('two_factor_secret') ?: ($user->two_factor_secret ?: \App\Services\TwoFactorService::generateSecretKey());
            $user->two_factor_secret = $secret;
            $user->two_factor_confirmed_at = now();
            $user->save();

            // Notificar alerta de seguridad
            \App\Services\NotificationService::notifySecurityAlert($user, 'Activación de 2FA Operativo', 'La autenticación en dos pasos (2FA) ha sido activada en tu cuenta de equipo/administración.', $request->ip(), $request->userAgent());

            return redirect()->route('admin.configuracion')->with('success', 'Autenticación en dos pasos (2FA) activada y guardada correctamente.');
        } else {
            $user->two_factor_confirmed_at = null;
            $user->save();

            // Notificar alerta de seguridad
            \App\Services\NotificationService::notifySecurityAlert($user, 'Desactivación de 2FA Operativo', 'La autenticación en dos pasos (2FA) ha sido desactivada en tu cuenta de equipo/administración.', $request->ip(), $request->userAgent());

            return redirect()->route('admin.configuracion')->with('success', 'Autenticación en dos pasos (2FA) desactivada con éxito.');
        }
    }

    public function adminUpdateNotificaciones(Request $request)
    {
        $user = auth()->user();
        $user->notification_preferences = [
            'notif_team_assignment' => $request->has('notif_team_assignment') ? 1 : 0,
            'notif_deployments' => $request->has('notif_deployments') ? 1 : 0,
            'notif_security' => $request->has('notif_security') ? 1 : 0,
            'notif_weekly_digest' => $request->has('notif_weekly_digest') ? 1 : 0,
        ];
        $user->save();

        return redirect()->route('admin.configuracion')->with('success', 'Preferencias operativas y de notificaciones guardadas exitosamente.');
    }

    public function updatePreferencias(Request $request)
    {
        return redirect()->route('portal.configuracion')->with('success', 'Preferencias guardadas con éxito.');
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

        // Notificar despliegue / cambio de fase al cliente dueño del proyecto
        \App\Services\NotificationService::notifyDeployment($proyecto, "El estado del proyecto ha sido actualizado a '{$proyecto->estado}'.");

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

        if ($nuevoLider) {
            \App\Services\NotificationService::notifyTeamAssignment($nuevoLider, $proyecto, 'Líder de Proyecto');
        }

        return response()->json([
            'success' => true,
            'developer_id' => $request->developer_id,
            'developer_name' => $nuevoLider ? $nuevoLider->name : 'Sin asignar',
            'message' => 'Líder asignado exitosamente al proyecto.'
        ]);
    }

    public function syncClickUpManual(Request $request)
    {
        if ($request->has('id')) {
            $proyecto = Project::findOrFail($request->id);
            if (!$proyecto->clickup_list_id) {
                return response()->json(['success' => false, 'message' => 'Este proyecto no tiene lista de ClickUp vinculada.'], 400);
            }
            $result = \App\Http\Controllers\Webhook\ClickUpWebhookController::syncProjectFromClickUpList($proyecto, $proyecto->clickup_list_id);
            return response()->json([
                'success' => $result['success'],
                'progreso' => $result['progreso'] ?? $proyecto->progreso,
                'message' => $result['success'] ? "Proyecto sincronizado exitosamente ({$result['progreso']}%)." : ($result['error'] ?? 'Error al sincronizar')
            ]);
        }

        return $this->adminSyncAllClickUp();
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

    public function adminProyectosUpdate(Request $request, $id, ClickUpService $clickUpService)
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

        // Sincronizar permisos de líder y colaboradores en ClickUp (excluyendo cliente)
        if ($proyecto->clickup_list_id) {
            $clickUpService->syncProjectMembers($proyecto);
        }

        // Notificar actualización al cliente si cambió fase o especificación
        \App\Services\NotificationService::notifyDeployment($proyecto, "Se han actualizado las especificaciones y estado del proyecto a '{$proyecto->estado}' con avance del {$proyecto->progreso}%.");

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto actualizado y roles sincronizados exitosamente.');
    }

    public function adminProyectosStore(Request $request, ClickUpService $clickUpService)
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

        // Crear la lista en ClickUp si está configurado
        $clickupListId = $clickUpService->createProjectList($request->nombre, $request->descripcion);

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

        // Sincronizar al líder con permisos de admin/full en ClickUp (el cliente NO se incluye)
        if ($clickupListId) {
            $clickUpService->syncProjectMembers($proyecto);
        }

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto registrado y sincronizado en ClickUp exitosamente.');
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

    public function adminEquipoStore(Request $request, ClickUpService $clickUpService)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,empleado',
            'clickup_user_id' => 'nullable|string|max:100',
            'edad' => 'nullable|integer|min:18',
            'capacity' => 'required|integer|between:0,40',
            'proyectos' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'clickup_user_id' => $data['clickup_user_id'] ?? null,
        ]);

        // Intentar auto-resolver el ClickUp User ID si no se proporcionó manualmente
        if (empty($user->clickup_user_id)) {
            $clickUpService->resolveClickUpUserId($user);
        }

        TeamCorporation::create([
            'user_id' => $user->id,
            'edad' => $data['edad'],
            'capacity' => $data['capacity'],
        ]);

        $syncData = [];
        $affectedProjects = [];
        if (!empty($request->proyectos)) {
            foreach ($request->proyectos as $proy) {
                if (!empty($proy['id'])) {
                    $syncData[$proy['id']] = [
                        'sueldo_proyecto' => $proy['sueldo'] ?? 0,
                        'importancia' => $proy['importancia'] ?? 'Media'
                    ];
                    $affectedProjects[] = $proy['id'];

                    if (!empty($proy['es_lider']) && $proy['es_lider'] == '1') {
                        Project::where('id', $proy['id'])->update(['developer_id' => $user->id]);
                    }
                }
            }
        }
        $user->proyectos()->sync($syncData);

        // Sincronizar permisos en las listas de ClickUp de los proyectos asociados
        foreach ($affectedProjects as $proyId) {
            $proyModel = Project::find($proyId);
            if ($proyModel && $proyModel->clickup_list_id) {
                $clickUpService->syncProjectMembers($proyModel);
            }
        }

        return redirect()->route('admin.equipo')->with('success', 'Miembro operativo integrado y sincronizado.');
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

    public function adminEquipoUpdate(Request $request, $id, ClickUpService $clickUpService)
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
            'clickup_user_id' => 'nullable|string|max:100',
            'edad' => 'nullable|integer|min:18',
            'capacity' => 'required|integer|between:0,40',
            'proyectos' => 'nullable|array',
        ]);

        $miembro->name = $data['name'];
        $miembro->email = $data['email'];
        $miembro->role = $data['role'];
        if (array_key_exists('clickup_user_id', $data)) {
            $miembro->clickup_user_id = $data['clickup_user_id'];
        }
        if (!empty($data['password'])) {
            $miembro->password = bcrypt($data['password']);
        }
        $miembro->save();

        // Si no tiene ClickUp User ID, intentar auto-resolverlo
        if (empty($miembro->clickup_user_id)) {
            $clickUpService->resolveClickUpUserId($miembro);
        }

        $miembro->corporation()->updateOrCreate(
            ['user_id' => $miembro->id],
            ['edad' => $data['edad'], 'capacity' => $data['capacity']]
        );

        $syncData = [];
        $affectedProjects = $miembro->proyectos->pluck('id')->toArray();

        if (!empty($request->proyectos)) {
            foreach ($request->proyectos as $proy) {
                if (!empty($proy['id'])) {
                    $syncData[$proy['id']] = [
                        'sueldo_proyecto' => $proy['sueldo'] ?? 0,
                        'importancia' => $proy['importancia'] ?? 'Media',
                    ];
                    $affectedProjects[] = $proy['id'];

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

        // Sincronizar listas en ClickUp de todos los proyectos afectados
        $affectedProjects = array_unique($affectedProjects);
        foreach ($affectedProjects as $proyId) {
            $proyModel = Project::find($proyId);
            if ($proyModel && $proyModel->clickup_list_id) {
                $clickUpService->syncProjectMembers($proyModel);
            }
        }

        return redirect()->route('admin.equipo')->with('success', 'Ecosistema de personal y permisos en ClickUp actualizados.');
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

    public function destroy($id, ClickUpService $clickUpService)
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'No tienes permisos para eliminar proyectos.');
        }

        $proyecto = \App\Models\Project::findOrFail($id);

        if ($proyecto->clickup_list_id) {
            $clickUpService->deleteProjectList($proyecto->clickup_list_id);
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

            // Notificar al cliente la creación del nuevo hito
            \App\Services\NotificationService::notifyMilestone($newMilestone, 'created');
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

    /**
     * Consulta ClickUp y devuelve los usuarios disponibles para importar al portal.
     */
    public function adminEquipoClickUpPreview(ClickUpService $clickUpService)
    {
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        if (!$clickUpService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'ClickUp no está configurado en el sistema.'
            ], 400);
        }

        $workspaceUsers = $clickUpService->getWorkspaceUsers();
        $clickUpError = $clickUpService->getLastError();

        if (empty($workspaceUsers) && !empty($clickUpError)) {
            return response()->json([
                'success' => false,
                'message' => "ClickUp reportó: {$clickUpError}. Verifica tu Token de API o genera uno nuevo en ClickUp (Settings > Apps) si reiniciaste tu Workspace."
            ], 400);
        }

        $existingUsers = User::all()->keyBy(function ($u) {
            return strtolower(trim($u->email));
        });

        $available = [];
        $existing = [];

        foreach ($workspaceUsers as $email => $u) {
            if ($existingUsers->has($email)) {
                $localUser = $existingUsers->get($email);
                // Si existe pero no tenía clickup_user_id guardado, actualizarlo
                if (empty($localUser->clickup_user_id) && !empty($u['id']) && !$localUser->isClient()) {
                    $localUser->clickup_user_id = $u['id'];
                    $localUser->saveQuietly();
                }

                $existing[] = [
                    'id' => $localUser->id,
                    'name' => $localUser->name,
                    'email' => $localUser->email,
                    'role' => $localUser->role,
                    'active' => $localUser->active,
                    'clickup_id' => $u['id'],
                    'status' => $localUser->active == 1 ? 'Activo' : 'Pendiente de Activación',
                ];
            } else {
                $available[] = [
                    'clickup_id' => $u['id'],
                    'name' => $u['username'] ?: explode('@', $email)[0],
                    'email' => $email,
                    'clickup_role' => $u['role'] ?? null,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'total_clickup' => count($workspaceUsers),
            'available' => $available,
            'already_in_portal' => $existing,
        ]);
    }

    /**
     * Importa uno o varios usuarios de ClickUp al portal generando token de activación.
     */
    public function adminEquipoClickUpImport(Request $request, ClickUpService $clickUpService)
    {
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $request->validate([
            'users' => 'required|array|min:1',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email|max:255',
            'users.*.clickup_id' => 'required|string|max:100',
            'users.*.role' => 'nullable|string|in:empleado,admin,superadmin',
            'send_emails' => 'nullable|boolean',
        ]);

        $imported = [];
        $shouldSendEmails = $request->boolean('send_emails', true);

        foreach ($request->users as $userData) {
            $email = strtolower(trim($userData['email']));

            // Verificar si ya existe
            $existing = User::where('email', $email)->first();
            if ($existing) {
                if (empty($existing->clickup_user_id)) {
                    $existing->clickup_user_id = $userData['clickup_id'];
                    $existing->save();
                }
                continue;
            }

            $token = Str::random(60);
            $userRole = $userData['role'] ?? 'empleado';

            $user = User::create([
                'name' => $userData['name'],
                'email' => $email,
                'password' => Hash::make(Str::random(24)),
                'role' => $userRole,
                'clickup_user_id' => $userData['clickup_id'],
                'active' => 0,
                'activation_token' => $token,
            ]);

            TeamCorporation::create([
                'user_id' => $user->id,
                'capacity' => 40,
                'edad' => null,
            ]);

            $activationUrl = route('portal.activate.form', $token);
            $emailSent = false;

            if ($shouldSendEmails) {
                $maxRetries = 2;
                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    try {
                        Mail::to($user->email)->send(new EmployeeActivationMail($user, $activationUrl));
                        $emailSent = true;
                        break;
                    } catch (\Exception $e) {
                        Log::warning("ClickUp Import: Intento {$attempt} al enviar email a {$user->email} falló: " . $e->getMessage());
                        if ($attempt < $maxRetries) {
                            sleep(2); // Esperar 2 segundos antes de reintentar para superar rate limits (e.g., Mailtrap)
                        } else {
                            Log::error("ClickUp Import: Error definitivo enviando email de activación a {$user->email} - " . $e->getMessage());
                        }
                    }
                }
                // Pausa preventiva de 1.2 segundos entre envíos para respetar límites de SMTP/Mailtrap Free (1 msg/seg)
                usleep(1200000);
            }

            $imported[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'activation_url' => $activationUrl,
                'email_sent' => $emailSent,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($imported) . ' usuarios importados exitosamente desde ClickUp.',
            'imported' => $imported,
        ]);
    }

    /**
     * Envía o reenvía el correo de activación con el enlace de acceso a un miembro del equipo.
     */
    public function adminEquipoSendActivation($id)
    {
        if (auth()->user()->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $user = User::findOrFail($id);

        if ($user->isClient()) {
            return response()->json(['success' => false, 'message' => 'Los clientes se gestionan desde el panel de clientes.'], 400);
        }

        if (empty($user->activation_token)) {
            $user->activation_token = Str::random(60);
            $user->active = 0;
            $user->save();
        }

        $activationUrl = route('portal.activate.form', $user->activation_token);
        $emailSent = false;
        $errorMessage = null;

        try {
            Mail::to($user->email)->send(new EmployeeActivationMail($user, $activationUrl));
            $emailSent = true;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            Log::error("Error enviando email de activación a {$user->email}: " . $e->getMessage());
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'email_sent' => $emailSent,
                'activation_url' => $activationUrl,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'message' => $emailSent
                    ? "Correo de activación enviado exitosamente a {$user->email}."
                    : "No se pudo enviar el correo automáticamente ({$errorMessage}), pero puedes copiar el enlace directo.",
            ]);
        }

        $msg = $emailSent
            ? "Correo de activación enviado a {$user->email}."
            : "Enlace generado. No se pudo enviar el correo automático.";

        return redirect()->route('admin.equipo')
            ->with('success', $msg)
            ->with('activation_link', $activationUrl)
            ->with('user_name', $user->name);
    }

    /**
     * Sincroniza masivamente todos los proyectos vinculados a ClickUp.
     */
    public function adminSyncAllClickUp()
    {
        $proyectos = Project::whereNotNull('clickup_list_id')
            ->where('clickup_list_id', '!=', '')
            ->get();

        $synced = 0;
        $failed = 0;
        $errors = [];

        foreach ($proyectos as $proyecto) {
            $res = \App\Http\Controllers\Webhook\ClickUpWebhookController::syncProjectFromClickUpList(
                $proyecto,
                $proyecto->clickup_list_id
            );

            if ($res['success']) {
                $synced++;
            } else {
                $failed++;
                $errors[] = "{$proyecto->nombre}: " . ($res['error'] ?? 'Error de conexión');
            }
        }

        return back()->with('info', "Sincronización completada: {$synced} proyectos actualizados correctamente" . ($failed > 0 ? " ({$failed} con error)." : "."));
    }

    /**
     * Reintenta el procesamiento de un WebhookLog fallido o pendiente.
     */
    public function adminRetryWebhook($id)
    {
        $log = \App\Models\WebhookLog::findOrFail($id);
        $log->increment('attempts');

        if (!$log->list_id) {
            $log->update([
                'status' => 'ignored',
                'error_message' => 'No cuenta con list_id válido para reintentar.',
            ]);
            return back()->with('error', 'El webhook no tiene un ID de lista válido para reintentar.');
        }

        $proyecto = $log->project ?? Project::where('clickup_list_id', $log->list_id)->first();

        if (!$proyecto) {
            $log->update([
                'status' => 'ignored',
                'error_message' => "No se encontró proyecto para la lista {$log->list_id}",
            ]);
            return back()->with('error', "No se encontró ningún proyecto vinculado a la lista {$log->list_id}.");
        }

        $result = \App\Http\Controllers\Webhook\ClickUpWebhookController::syncProjectFromClickUpList(
            $proyecto,
            $log->list_id
        );

        if ($result['success']) {
            $log->update([
                'status' => 'success',
                'error_message' => null,
                'project_id' => $proyecto->id,
                'processed_at' => now(),
            ]);
            return back()->with('success', "Webhook reintentado con éxito. Proyecto '{$proyecto->nombre}' actualizado.");
        } else {
            $log->update([
                'status' => 'failed',
                'error_message' => $result['error'],
                'processed_at' => now(),
            ]);
            return back()->with('error', 'Fallo al reintentar webhook: ' . $result['error']);
        }
    }
}

