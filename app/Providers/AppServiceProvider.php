<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Opcional: para estilos de paginación

class AppServiceProvider extends ServiceProvider
{
    /**
     * La ruta a la que los usuarios son redirigidos después de autenticarse.
     *
     * @var string
     */
    public const HOME = '/projects'; // Aquí defines que vayan a proyectos en lugar de /home

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Si usas Bootstrap para las tablas del manual, añade esto:
        Paginator::useBootstrap();
    }
}