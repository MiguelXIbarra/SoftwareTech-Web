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
    $url = url(route('password.reset', [
        'token' => $token,
        'email' => $notifiable->getEmailForPasswordReset(),
    ], false));

    return (new MailMessage)
        ->subject('Verificación de restablecimiento de contraseña')
        ->view('emails.password_reset', [
            'url' => $url,
            'name' => $notifiable->name
        ]);
    });
}
}