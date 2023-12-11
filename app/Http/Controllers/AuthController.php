<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            return redirect('/login/facebook')->with('error', 'Đăng nhập bằng Facebook thất bại.');
        }

        // Bạn có thể xử lý người dùng ở đây
        // Nếu cần, bạn cũng có thể đăng nhập người dùng vào hệ thống
        // Ví dụ: Auth::login($user);

        return redirect('/')->with('success', 'Đăng nhập bằng Facebook thành công.');
    }
}
