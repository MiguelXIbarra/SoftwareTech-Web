<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ActivationController extends Controller
{
    public function showActivationForm($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        return view('auth.activate', compact('user', 'token'));
    }

    public function activate(Request $request, $token)
{
    $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::where('activation_token', $token)->firstOrFail();

    $user->update([
        'password' => Hash::make($request->password),
        'active' => 1,
        'activation_token' => null,
        'email_verified_at' => now(),
    ]);

    return redirect()->route('login')->with('success', '¡Cuenta activada correctamente! Ya puedes iniciar sesión.');
}

    public function storeCliente(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
    ]);

    $token = Str::random(60);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make(Str::random(16)),
        'role' => 'cliente',
        'active' => 0,
        'activation_token' => $token,
    ]);

    $linkActivacion = route('portal.activate.form', $token);

    return redirect('/console/clientes')->with('success', 'Cliente registrado. Link de activación: ' . $linkActivacion);
}
}
