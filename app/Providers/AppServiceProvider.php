<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * La ruta a la que los usuarios son redirigidos después de autenticarse.
     *
     * @var string
     */
    public const HOME = '/home';

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
    Paginator::useBootstrap();
    
    ResetPassword::toMailUsing(function ($notifiable, $token) {
        return (new MailMessage)
            ->subject('Verificación de Seguridad - Software Tech')
            ->greeting('Hola, Investigador/a')
            ->line('Se ha detectado una solicitud de actualización de identidad para tu acceso al Innovation Lab.')
            ->action('ACTUALIZAR CONTRASEÑA', url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('Si no has solicitado este cambio, ignora este correo.')
            ->salutation('Atentamente, El Equipo de Software Tech');
    });
}
}