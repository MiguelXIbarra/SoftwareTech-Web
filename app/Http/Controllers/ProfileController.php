<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.settings', ['user' => Auth::user()]);
    }

    public function profile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])
                        ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('status', 'Contraseña actualizada correctamente.');
    }

    public function verifyAjax(Request $request) 
    {
        $isValid = \Illuminate\Support\Facades\Hash::check($request->current_password, auth()->user()->password);
        
        return response()->json(['valid' => $isValid]);
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        if ($user->two_factor_confirmed_at) {
            $user->update(['two_factor_secret' => null, 'two_factor_confirmed_at' => null]);
            return redirect()->route('profile.edit')->with('status', 'Protección 2FA desactivada.');
        }

        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < 16; $i++) { $secret .= $chars[rand(0, 31)]; }

        $user->update(['two_factor_secret' => encrypt($secret), 'two_factor_confirmed_at' => null]);
        return redirect()->route('profile.edit')->with('show_qr_modal', true);
    }

    public function cancelTwoFactor()
    {
        Auth::user()->update(['two_factor_secret' => null, 'two_factor_confirmed_at' => null]);
        return redirect()->route('profile.edit')->with('status', 'Vinculación cancelada.');
    }

    public function confirmTwoFactor(Request $request)
    {
        $request->validate(['code' => 'required|numeric|digits:6']);
        Auth::user()->update(['two_factor_confirmed_at' => now()]);
        return redirect()->route('profile.edit')->with('status', '¡Seguridad 2FA activada con éxito!');
    }
}