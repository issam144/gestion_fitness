<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. كنتأكدو واش المستخدم مسجل (Logged in)
        // 2. كنتأكدو واش هو "client" (حيت الأدمن والكوتش ما كيساليش ليهم الاشتراك)
        if (Auth::check() && Auth::user()->role === 'client') {
            
            $user = Auth::user();

            // 3. التحقق من وجود اشتراك (abonnement_id) ومن تاريخ الانتهاء (expired_at)
            // إلا ما عندوش ID ديال الاشتراك، أو التاريخ ديال دابا فات تاريخ الانتهاء
            if (!$user->abonnement_id || ($user->expired_at && now()->gt($user->expired_at))) {
                
                // صيفطو لصفحة اختيار العروض (Plans) مع رسالة تنبيه
                return redirect()->route('client.plans')->with('error', 'Votre abonnement est expiré ou inexistant. Veuillez souscrire à un pack.');
            }
        }

        // إلا كان كولشي مزيان، خليه يدوز
        return $next($request);
    }
}