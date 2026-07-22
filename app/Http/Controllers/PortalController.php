<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class PortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $proyectos = \App\Models\Project::where('user_id', $user->id)->get();

        return view('portal.dashboard', compact('proyectos'));
    }

    public function proyecto($id)
    {
        $user = auth()->user();

        if (in_array($user->role, ['superadmin', 'admin'])) {
            $proyecto = Project::with(['user', 'developer', 'team'])->findOrFail($id);
        } else {
            $proyecto = Project::with(['user', 'developer', 'team'])
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
            'proyectosProspecto'   => Project::where('estado', 'Prospecto')->count(),
            'proyectosDesarrollo'  => Project::where('estado', 'En Desarrollo')->count(),
            'proyectosPruebas'     => Project::where('estado', 'En Pruebas')->count(),
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

    public function adminProyectos()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $proyectos = Project::with(['user', 'developer'])->get();
        } else {
            $proyectos = $user->proyectos()->with(['user', 'developer'])->get();
        }

        return view('admin.proyectos', compact('proyectos'));
    }

    public function adminProyectosCrear()
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403);
        }

        $clientes = User::where('role', 'cliente')->get();
        $desarrolladores = User::whereIn('role', ['admin', 'empleado'])->get();

        return view('admin.proyectos_crear', compact('clientes', 'desarrolladores'));
    }

    public function adminProyectosStore(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'descripcion'      => 'required|string',
            'servicio'         => 'required|string',
            'user_id'          => 'required|exists:users,id',
            'developer_id'     => 'required|exists:users,id',
            'priority'         => 'required|string',
            'siguiente_entrega'=> 'nullable|date',
        ]);

        $proyecto = \App\Models\Project::create([
            'nombre'            => $request->nombre,
            'descripcion'       => $request->descripcion,
            'servicio'          => $request->servicio,
            'user_id'           => $request->user_id,
            'developer_id'      => $request->developer_id,
            'priority'          => $request->priority,
            'siguiente_entrega' => $request->siguiente_entrega,
            'estado'            => 'Prospecto',
        ]);

        $proyecto->team()->attach($request->developer_id, [
            'importancia'     => ucfirst($request->priority),
            'sueldo_proyecto' => 0,
        ]);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto registrado y asignado exitosamente.');
    }

    public function getProyectoJson($id)
    {
        $proyecto = Project::with(['user', 'developer', 'team'])->findOrFail($id);

        return response()->json([
            'id'          => $proyecto->id,
            'nombre'      => $proyecto->nombre,
            'descripcion' => $proyecto->descripcion ?? 'Sin descripción técnica asignada.',
            'servicio'    => $proyecto->servicio,
            'estado'      => $proyecto->estado,
            'priority'    => $proyecto->priority ?? 'medio',
            'progreso'    => $proyecto->progreso ?? 0,
            'user'        => $proyecto->user,
            'developer'   => $proyecto->developer,
            'team'        => $proyecto->team,
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
            ->with(['corporation', 'proyectos'])
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
                        'importancia' => $proy['importancia'] ?? 'Media'
                    ];
                }
            }
        }
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
}
