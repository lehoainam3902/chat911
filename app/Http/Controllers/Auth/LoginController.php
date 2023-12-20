<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login()
    {
        // Xử lý đăng nhập bằng email và mật khẩu nếu cần
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Đăng nhập bằng Facebook thất bại');
        }

        // Xử lý đăng nhập hoặc tạo người dùng ở đây

        return redirect()->intended('/dashboard');
    }
}
