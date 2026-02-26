<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required',
        // Eliminamos la validación de 'image' porque ahora es un texto Base64
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->role = $request->role;

    // Lógica para procesar el recorte Base64
    if ($request->filled('profile_photo_base64')) {
        $imageData = $request->profile_photo_base64;
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        
        $fileName = 'profile_photos/' . time() . '.jpg';
        \Storage::disk('public')->put($fileName, base64_decode($image));
        
        $user->profile_photo = $fileName;
    }

    $user->save();

    return redirect()->route('users.index')->with('message', 'Usuario creado correctamente');
}

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // ESTE ES EL MÉTODO QUE TE DABA ERROR PORQUE NO EXISTÍA
    public function edit($id)
{
    // Buscamos al usuario por su ID
    $user = User::findOrFail($id);
    // Lo enviamos a la vista
    return view('users.edit', compact('user'));
}

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required',
    ]);

    // Procesar la imagen recortada (Base64)
    if ($request->filled('profile_photo_base64')) {
        $imageData = $request->profile_photo_base64;
        $image = str_replace('data:image/jpeg;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
    
        // Esto guarda la imagen en storage/app/public/profile_photos
        $fileName = 'profile_photos/' . time() . '.jpg';
        \Storage::disk('public')->put($fileName, base64_decode($image));
    
        // Guardamos 'profile_photos/archivo.jpg' en la DB
        $user->profile_photo = $fileName;
}

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;

    if ($request->filled('password')) {
        $user->password = \Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('users.index')->with('message', 'Perfil actualizado con éxito');
}

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        $user->delete();
        return redirect()->route('users.index')->with('message', 'Usuario eliminado');
    }
}