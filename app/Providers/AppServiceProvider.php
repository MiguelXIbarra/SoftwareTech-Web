<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function register(): void
    {
        //
    }

    public function boot(): void
{
    Paginator::useBootstrap();

    ResetPassword::toMailUsing(function ($notifiable, $token) {
        
        $url = url('/password/reset/' . $token . '?email=' . urlencode($notifiable->getEmailForPasswordReset()));

        return (new MailMessage)
            ->subject('Restablecimiento de Contraseña | Software Tech')
            ->view('emails.password_reset', [
                'url' => $url,
                'name' => $notifiable->name
            ]);
    });
}
}