<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | هاد الكنترولر كيتكلف بعملية الدخول وتوجيه المستخدم لبلاصتو
    |
    */

    use AuthenticatesUsers;

    /**
     * المسار الافتراضي (في حالة فشل التوجيه الخاص)
     */
    protected $redirectTo = '/home';

    /**
     * إنشاء نسخة جديدة من الكنترولر
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * هاد الدالة هي اللي كتنفذ مورا ما كيدخل المستخدم بنجاح
     */
    protected function authenticated(Request $request, $user)
    {
        // 1. أولاً: كنشوفو واش الأدمن قبلو (is_approved == 1)
        if (!$user->is_approved) {
            Auth::logout();
            return redirect('/login')->with('error', 'Votre compte n\'est pas encore approuvé par l\'administrateur.');
        }

        // 2. ثانياً: التوجيه باستعمال أسماء المسارات (Route Names) لتفادي خطأ 404
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        
        if ($user->role === 'coach') {
            return redirect()->route('coach.dashboard');
        } 

        if ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }

        // في حالة ما كاين حتى دور، صيفطو لـ home
        return redirect('/home');
    }
}