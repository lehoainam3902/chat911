<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateFacebookUser
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login/facebook')->with('error', 'Bạn phải đăng nhập bằng Facebook để truy cập trang này.');
        }

        return $next($request);
    }
}