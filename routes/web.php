<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\Auth\LoginController;

// Ruta principal - Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Portal Privado Protegido
Route::middleware(['auth'])->prefix('portal')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'index'])->name('portal.dashboard');
    Route::get('/proyecto/{id}', [PortalController::class, 'proyecto'])->name('portal.proyecto');
});
