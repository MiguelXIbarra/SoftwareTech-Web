<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ActivationController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/portal/activar/{token}', [ActivationController::class, 'showActivationForm'])->name('portal.activate.form');
Route::post('/portal/activar/{token}', [ActivationController::class, 'activate'])->name('portal.activate.submit');

Route::middleware(['auth', 'role:cliente'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'index'])->name('dashboard');
    Route::get('/proyecto/{id}', [PortalController::class, 'proyecto'])->name('proyecto');
});

Route::middleware(['auth', 'role:superadmin,admin,empleado'])->prefix('console')->name('admin.')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/clientes', [PortalController::class, 'adminClientes'])->name('clientes.index');
    Route::post('/clientes/store', [ActivationController::class, 'storeCliente'])->name('clientes.store');

    Route::get('/proyectos', [PortalController::class, 'adminProyectos'])->name('proyectos.index');
    Route::get('/proyectos/crear', [PortalController::class, 'adminProyectosCrear'])->name('proyectos.crear');
    Route::post('/proyectos/store', [PortalController::class, 'adminProyectosStore'])->name('proyectos.store');
    Route::post('/proyectos/update-status', [PortalController::class, 'updateStatus'])->name('proyectos.updateStatus');

    Route::get('/api/proyectos/{id}', [PortalController::class, 'getProyectoJson'])->name('api.proyectos.show');
    Route::delete('/proyectos/{id}', [PortalController::class, 'destroy'])->name('proyectos.destroy');
});

Route::post('/api/clickup/webhook', [PortalController::class, 'handleClickUpWebhook']);
