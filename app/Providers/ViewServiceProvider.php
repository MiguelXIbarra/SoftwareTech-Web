<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadCount = Message::whereHas('project', function($q) {
                    $q->where('client_id', Auth::id());
                })->count();

                $view->with('newMessagesCount', $unreadCount);
            }
        });
    }
}