<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // تسجيل الـ Middleware Aliases هنا ✅
        $middleware->alias([
            'role'           => \App\Http\Middleware\RoleMiddleware::class,
            'approved'       => \App\Http\Middleware\CheckApproval::class,
            'check.password' => \App\Http\Middleware\CheckPasswordChange::class,
            // هاد السطر هو اللي كيخليك تخدم بـ 'subscribed' فـ الـ Routes ديالك
            'subscribed'     => \App\Http\Middleware\CheckSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();