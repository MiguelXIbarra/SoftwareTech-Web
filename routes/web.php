<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LabPostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home');
});

Auth::routes(['reset' => false]);

Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::get('/password/reset', function () {
    return view('auth.passwords.email');
});

Route::post('/password/email', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'IDENTIDAD NO ENCONTRADA: El correo no existe en el Lab.']);
    }

    $status = Password::broker()->sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', [
        'token' => $token, 
        'email' => request()->email
    ]);
})->name('password.reset');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', [
        'token' => $token, 
        'email' => request()->email
    ]);
});

Route::post('/password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::post('/password/send-auto-reset', function () {
        $status = Password::broker()->sendResetLink(['email' => auth()->user()->email]);
        return back()->with('status', __($status));
    })->name('password.auto_send');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::get('/admin/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/settings', [ProfileController::class, 'index'])->name('profile.edit');
    Route::get('/admin/settings', [ProfileController::class, 'index'])->name('settings');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/admin/verify-password', [ProfileController::class, 'verifyAjax'])->name('password.verify.ajax');

    Route::post('/settings/2fa-manual', [ProfileController::class, 'toggleTwoFactor'])->name('custom.2fa.toggle');
    Route::post('/settings/2fa-confirm', [ProfileController::class, 'confirmTwoFactor'])->name('custom.2fa.confirm');
    Route::post('/settings/2fa-cancel', [ProfileController::class, 'cancelTwoFactor'])->name('custom.2fa.cancel');

    Route::resource('projects', ProjectController::class);
    Route::resource('milestones', MilestoneController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('users', UserController::class);
    Route::resource('messages', MessageController::class);
    Route::resource('lab_posts', LabPostController::class);

    Route::get('/logout', function () {
        Auth::logout();
        Session::flush();
        return redirect('/');
    })->name('main.logout');
});