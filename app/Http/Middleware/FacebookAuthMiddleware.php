<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FacebookAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            // Người dùng chưa đăng nhập, chuyển hướng đến trang đăng nhập Facebook
            return redirect('/login/facebook')->with('error', 'Bạn cần đăng nhập bằng Facebook để truy cập.');
        }

        return $next($request);
    }
}
