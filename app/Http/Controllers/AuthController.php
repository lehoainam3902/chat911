<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function  redirect()
    {
        return Socialite::driver('github')->redirect();
    }
    public function callback()
    {
        $user = Socialite::driver('github')->user();
    }
}
