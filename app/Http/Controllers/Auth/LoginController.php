<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ])->onlyInput('email');
        }

        // Check if 2FA is active
        if ($user->hasTwoFactorEnabled()) {
            $request->session()->put('login.id', $user->id);
            $request->session()->put('login.remember', $request->filled('remember'));

            return redirect()->route('two-factor.login');
        }

        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        // Procesar detección de nuevo dispositivo o ubicación inusual
        $this->handleSuccessfulLogin($user, $request, false);

        return redirect()->route($user->role === 'cliente' ? 'portal.dashboard' : 'admin.dashboard');
    }

    public function showTwoFactorForm(Request $request)
    {
        if (!$request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('login.id');
        $user = User::find($userId);

        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge', compact('user'));
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'Debes ingresar el código de verificación de 6 dígitos.',
        ]);

        $userId = $request->session()->get('login.id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('login');
        }

        $cleanCode = preg_replace('/\s+/', '', $request->code);

        if (!TwoFactorService::verifyCode($user->two_factor_secret, $cleanCode)) {
            return back()->withErrors([
                'code' => 'El código de 6 dígitos es incorrecto o ha expirado. Por favor, verifica tu app autenticadora.',
            ]);
        }

        $remember = $request->session()->get('login.remember', false);
        $request->session()->forget(['login.id', 'login.remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Procesar detección de nuevo dispositivo o ubicación inusual
        $this->handleSuccessfulLogin($user, $request, true);

        return redirect()->route($user->role === 'cliente' ? 'portal.dashboard' : 'admin.dashboard');
    }

    /**
     * Rastrea IPs conocidas y alerta únicamente cuando el acceso proviene de una IP/ubicación no habitual.
     */
    private function handleSuccessfulLogin(User $user, Request $request, bool $with2FA = false): void
    {
        $currentIp = $request->ip();
        $knownIps = $user->known_ips ?? [];
        $isNewLocation = !in_array($currentIp, $knownIps);

        if ($isNewLocation) {
            $knownIps[] = $currentIp;
            $user->known_ips = array_values(array_unique($knownIps));

            $eventTitle = $with2FA ? 'Nuevo Acceso con 2FA (Ubicación Inusual)' : 'Nuevo Inicio de Sesión Inusual';
            $description = "Se detectó un acceso a tu cuenta desde una dirección IP o dispositivo no habitual ({$currentIp}). Si fuiste tú, puedes ignorar este aviso.";

            \App\Services\NotificationService::notifySecurityAlert($user, $eventTitle, $description, $currentIp, $request->userAgent());
        }

        $user->last_login_ip = $currentIp;
        $user->last_login_at = now();
        $user->saveQuietly();
    }

    public function cancelTwoFactor(Request $request)
    {
        $request->session()->forget(['login.id', 'login.remember']);
        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
