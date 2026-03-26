<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'check2fa', 'loginWith2fa']);
        $this->middleware('auth')->only('logout');
    }

    public function check2fa(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $has2FA = !is_null($user->two_factor_confirmed_at) && !empty($user->two_factor_secret);
            return response()->json(['requires2FA' => $has2FA, 'success' => true]);
        }
        return response()->json(['requires2FA' => false, 'success' => false], 401);
    }

    public function loginWith2fa(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            try {
                $google2fa = app('pragmarx.google2fa');
                if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
                    Auth::login($user, $request->filled('remember'));
                    return redirect()->intended($this->redirectTo);
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['email' => 'Fallo de protocolo de seguridad.']);
            }
        }
        return redirect()->back()->withErrors(['email' => 'Código de autenticación incorrecto.']);
    }

    protected function loggedOut(Request $request) { return redirect('/'); }
}