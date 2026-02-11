<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Dentro de la clase ProjectController
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Listado de todos los usuarios (clientes y administradores)
        $users = User::orderBy('name', 'asc')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Formulario para crear un usuario manualmente desde el admin
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('message', 'Usuario creado correctamente');
    }

    public function show($id)
    {
        // Ver el perfil detallado del usuario y sus proyectos vinculados
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        // Formulario para editar datos o cambiar el Rol
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validación para asegurar que el rol sea uno de los permitidos
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:client,admin,developer,superadmin', // Agrega superadmin aquí
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Aquí es donde fallaba en tu imagen

        return redirect()->route('users.index')->with('message', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        // Eliminar usuario del sistema
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('message', 'Usuario eliminado');
    }
}