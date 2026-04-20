<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LabPostController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AssetController;
use App\Models\User;
use Illuminate\Http\Request;

// --- RUTAS PÚBLICAS ---
Route::get('/', function () {
    return view('home');
});

Auth::routes(['reset' => false]);

// --- SISTEMA DE RECUPERACIÓN DE IDENTIDAD (PASSWORD RESET) ---

// 1. Vista para solicitar el enlace (Olvide mi contraseña)
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->name('password.request');

// 2. Envío del correo con el enlace
Route::post('/password/email', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'IDENTIDAD NO ENCONTRADA: El correo no existe en el Lab.']);
    }

    $status = Password::broker()->sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// 3. Vista para ingresar la nueva contraseña (desde el correo)
Route::get('/password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', [
        'token' => $token, 
        'email' => request()->email
    ]);
})->name('password.reset');

// 4. PROCESAR LA ACTUALIZACIÓN (Esta ruta conecta con tu ResetPasswordController)
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// --- RUTAS PÚBLICAS (Fuera del middleware auth) ---
Route::post('/login/check-2fa', [LoginController::class, 'check2fa'])->name('login.check.2fa');
Route::post('/login/2fa', [LoginController::class, 'loginWith2fa'])->name('login.2fa');

// --- RUTAS PROTEGIDAS (LAB CORE) ---
Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Reset automático desde el perfil
    Route::post('/password/send-auto-reset', function () {
        $status = Password::broker()->sendResetLink(['email' => auth()->user()->email]);
        return back()->with('status', __($status));
    })->name('password.auto_send');

    // Gestión de Perfil y Configuración
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::get('/admin/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/settings', [ProfileController::class, 'index'])->name('profile.edit');
    Route::get('/admin/settings', [ProfileController::class, 'index'])->name('settings');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/admin/verify-password', [ProfileController::class, 'verifyAjax'])->name('password.verify.ajax');

    // Seguridad 2FA
    Route::post('/settings/2fa-manual', [ProfileController::class, 'toggleTwoFactor'])->name('custom.2fa.toggle');
    Route::post('/settings/2fa-confirm', [ProfileController::class, 'confirmTwoFactor'])->name('custom.2fa.confirm');
    Route::post('/settings/2fa-cancel', [ProfileController::class, 'cancelTwoFactor'])->name('custom.2fa.cancel');
    Route::post('/settings/2fa-deactivate', [ProfileController::class, 'deactivateTwoFactor'])->name('custom.2fa.deactivate');

    // Recursos del Sistema
    Route::resource('projects', ProjectController::class);
    Route::resource('milestones', MilestoneController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('users', UserController::class);
    Route::resource('emails', EmailController::class);
    Route::resource('lab_posts', LabPostController::class);

    //Manejo de Archivos en Storage
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
    Route::post('/assets/store', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/assets/image/{id}', [AssetController::class, 'getImage'])->name('assets.image');
    Route::get('/assets/video/{id}', [AssetController::class, 'getVideo'])->name('assets.video');

    // Salida del Sistema
    Route::get('/logout', function () {
        Auth::logout();
        Session::flush();
        return redirect('/');
    })->name('main.logout');
});