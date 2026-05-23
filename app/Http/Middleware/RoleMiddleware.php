<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. إلا مكنش مكونيكطي، يمشي لـ Login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. إلا كان مكونيكطي ولكن الرول غلط، نصيفطوه لـ /home وهي غتوزعهم
        if (Auth::user()->role !== $role) {
            return redirect('/home')->with('error', "Accès refusé !");
        }

        return $next($request);
    }
}