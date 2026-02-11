<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LabPostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Autenticación (Login, Register, etc.)
Auth::routes();

// Grupo de rutas protegidas por login
Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Gestión de Proyectos de Software Technologies
    Route::resource('projects', ProjectController::class);

    // Gestión de Hitos y Pagos
    Route::resource('milestones', MilestoneController::class);
    Route::resource('payments', PaymentController::class);

    // Laboratorio Tecnológico y Blog
    Route::resource('lab_posts', LabPostController::class);

    // Comunicación Interna
    Route::resource('messages', MessageController::class);

    // Administración de Usuarios (Solo accesible para el Admin)
    Route::resource('users', UserController::class);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
