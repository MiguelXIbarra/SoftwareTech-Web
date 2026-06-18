<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Ruta principal - Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    return 'Pantalla de Login en mantenimiento.';
})->name('login');
