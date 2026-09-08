<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ActivationController;
use App\Http\Controllers\Webhook\ClickUpWebhookController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contacto', [HomeController::class, 'guardarContacto'])->name('contacto.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/portal/activar/{token}', [ActivationController::class, 'showActivationForm'])->name('portal.activate.form');
Route::post('/portal/activar/{token}', [ActivationController::class, 'activate'])->name('portal.activate.submit');

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AssetController;

Route::middleware(['auth', 'role:cliente'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'index'])->name('dashboard');
    Route::get('/proyecto/{id}', [PortalController::class, 'proyecto'])->name('proyecto');
    Route::post('/milestones/{id}/pay-card', [PaymentController::class, 'payWithCard'])->name('milestones.payCard');
    Route::post('/milestones/{id}/comprobante', [AssetController::class, 'uploadMilestoneReceipt'])->name('milestones.comprobante');
});

Route::middleware(['auth'])->prefix('console')->name('admin.')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'adminDashboard'])->name('dashboard');

    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/equipo', [PortalController::class, 'adminEquipo'])->name('equipo');
        Route::get('/equipo/crear', [PortalController::class, 'adminEquipoCrear'])->name('equipo.crear');
        Route::post('/equipo/store', [PortalController::class, 'adminEquipoStore'])->name('equipo.store');
        Route::get('/equipo/{id}/editar', [PortalController::class, 'adminEquipoEditar'])->name('equipo.editar');
        Route::match(['put', 'post'], '/equipo/{id}', [PortalController::class, 'adminEquipoUpdate'])->name('equipo.update');
        Route::delete('/equipo/{id}', [PortalController::class, 'adminEquipoDestroy'])->name('equipo.destroy');
    });

    Route::middleware(['role:superadmin,admin'])->group(function () {
        Route::get('/clientes', [PortalController::class, 'adminClientes'])->name('clientes.index');
        Route::post('/clientes/store', [ActivationController::class, 'storeCliente'])->name('clientes.store');

        Route::get('/proyectos/crear', [PortalController::class, 'adminProyectosCrear'])->name('proyectos.crear');
        Route::post('/proyectos/store', [PortalController::class, 'adminProyectosStore'])->name('proyectos.store');
        Route::get('/proyectos/{id}/editar', [PortalController::class, 'adminProyectosEditar'])->name('proyectos.editar');
        Route::put('/proyectos/{id}', [PortalController::class, 'adminProyectosUpdate'])->name('proyectos.update');
        Route::delete('/proyectos/{id}', [PortalController::class, 'destroy'])->name('proyectos.destroy');
        Route::post('/proyectos/restaurar/{id}', [PortalController::class, 'restore'])->name('proyectos.restore');
        Route::post('/proyectos/sync-clickup', [PortalController::class, 'syncClickUpManual'])->name('proyectos.syncClickup');
        Route::post('/milestones/{id}/toggle-payment', [PaymentController::class, 'togglePaymentStatus'])->name('milestones.togglePayment');
        Route::post('/proyectos/update-leader', [PortalController::class, 'updateLeader'])->name('proyectos.updateLeader');
        Route::delete('/assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy');
        Route::post('/proyectos/{id}/milestones/save', [PortalController::class, 'saveProjectMilestones'])->name('proyectos.milestones.save');
    });

    Route::get('/proyectos', [PortalController::class, 'adminProyectos'])->name('proyectos.index');
    Route::post('/proyectos/update-status', [PortalController::class, 'updateStatus'])->name('proyectos.updateStatus');
    Route::get('/api/proyectos/{id}', [PortalController::class, 'getProyectoJson'])->name('api.proyectos.show');

    // Rutas de archivos y entregables
    Route::post('/proyectos/{id}/assets', [AssetController::class, 'storeProjectAsset'])->name('proyectos.assets.store');
    Route::post('/milestones/{id}/receipt', [AssetController::class, 'uploadMilestoneReceipt'])->name('milestones.receipt.store');
});

// Descarga autenticada de assets
Route::get('/assets/{id}/download', [AssetController::class, 'download'])->middleware('auth')->name('assets.download');

Route::post('/api/clickup/webhook', [ClickUpWebhookController::class, 'handle']);
