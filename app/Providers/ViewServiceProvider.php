<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Email;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadCount = Email::whereHas('project', function($q) {
                    $q->where('client_id', Auth::id());
                })->count();

                $view->with('newEmailsCount', $unreadCount);
            }
        });
    }
}