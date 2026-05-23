<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // إذا كان المستخدم أدمن، لا تطلب منه تغيير كلمة المرور أبداً ✅
            if ($user->role === 'admin') {
                return $next($request);
            }

            // إذا كان مستخدماً عادياً (كليان أو كوتش) ويجب عليه التغيير
            if ($user->must_change_password) {
                if (!$request->is('change-password*') && !$request->is('logout')) {
                    return redirect()->route('password.change');
                }
            }
        }

        return $next($request);
    }
}