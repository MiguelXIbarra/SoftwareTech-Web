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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    
    // DASHBOARD PRINCIPAL
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // PERFIL DE USUARIO (La ruta que se ve en blanco)
    Route::get('/admin/profile', [ProfileController::class, 'profile'])->name('profile.show');

    // CONFIGURACIÓN DE PERFIL Y 2FA
    Route::get('/settings', [ProfileController::class, 'index'])->name('profile.edit');
    Route::get('/admin/settings', [ProfileController::class, 'index'])->name('settings');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

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
});