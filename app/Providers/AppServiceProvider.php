<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. استخدام بوتستراب 5 فـ الـ Pagination
        Paginator::useBootstrapFive();

        // 2. مشاركة المتغيرات مع جميع الـ Views (View Composer)
        View::composer('*', function ($view) {
            
            // كنتأكدو أولاً واش المستخدم مسجل (Logged in)
            if (Auth::check()) {
                $user = Auth::user();

                // أولاً: إذا كان المستخدم Admin (نصيفطو عدد الطلبات المعلقة)
                if ($user->role === 'admin') {
                    $pendingCount = User::where('is_approved', false)->count();
                    $view->with('pendingApprovalsCount', $pendingCount);
                }

                // ثانياً: إذا كان المستخدم Client (نصيفطو عدد أيام الاشتراك)
                if ($user->role === 'client') {
                    $days = $user->expired_at 
                        ? max(0, (int)Carbon::now()->diffInDays(Carbon::parse($user->expired_at), false)) 
                        : 0;
                    $view->with('daysLeft', $days);
                }
            }
        });
    }
}