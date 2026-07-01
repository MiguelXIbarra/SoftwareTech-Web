<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ((int)$user->active !== 1) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Esta cuenta de acceso no ha sido activada aún.',
                ]);
            }

            switch ($user->role) {
                case 'superadmin':
                case 'admin':
                case 'empleado':
                    return redirect()->route('admin.dashboard');
                case 'cliente':
                    return redirect()->route('portal.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
