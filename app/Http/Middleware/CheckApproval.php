<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApproval
{
    /**
     * هاد الدالة كتحبس أي واحد مازال ما تقبلش من طرف الأدمن
     */
    public function handle(Request $request, Closure $next): Response
    {
        // إذا كان المستخدم داخل (Authenticated) ولكن مازال ما تقبلش (is_approved == false)
        if (Auth::check() && !Auth::user()->is_approved) {
            Auth::logout(); // كنخرجوه
            return redirect()->route('login')->with('error', 'Votre compte est en attente de validation par l\'administrateur.');
        }

        return $next($request);
    }
}