<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LabPostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Password;

Route::get('/', function () {
    return view('home');
});


Auth::routes();

Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('/password/send-auto-reset', function () {
    $user = auth()->user();

    $status = Password::broker()->sendResetLink(['email' => $user->email]);

    return back()->with('status', __($status));
})->name('password.auto_send');

Route::middleware(['auth'])->group(function () {
    
    // DASHBOARD PRINCIPAL
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // PERFIL DE USUARIO (La ruta que se ve en blanco)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::get('/admin/profile', [ProfileController::class, 'profile'])->name('profile');
    

    // CONFIGURACIÓN DE PERFIL Y 2FA
    Route::get('/settings', [ProfileController::class, 'index'])->name('profile.edit');
    Route::get('/admin/settings', [ProfileController::class, 'index'])->name('settings');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/admin/verify-password', [ProfileController::class, 'verifyAjax'])->name('password.verify.ajax');

    // FLUJO DE 2FA
    Route::post('/settings/2fa-manual', [ProfileController::class, 'toggleTwoFactor'])->name('custom.2fa.toggle');
    Route::post('/settings/2fa-confirm', [ProfileController::class, 'confirmTwoFactor'])->name('custom.2fa.confirm');
    Route::post('/settings/2fa-cancel', [ProfileController::class, 'cancelTwoFactor'])->name('custom.2fa.cancel');

    // ADMINISTRACIÓN DE RECURSOS
    Route::resource('projects', ProjectController::class);
    Route::resource('milestones', MilestoneController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('users', UserController::class);
    Route::resource('messages', MessageController::class);
    Route::resource('lab_posts', LabPostController::class);

    Route::get('/logout', function () {
    Auth::logout();
    Session::flush();
    return redirect('/');
    });
});