<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/login';

    public function __construct() { $this->middleware('guest'); }
 
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password); 
        
        $user->setRememberToken(Str::random(60));
        $user->save();
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return view('auth.passwords.reset_success');
    }
}