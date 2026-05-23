<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeAbonnement; // هادي ضرورية
use App\Models\TypeSeance;    // وهادي ضرورية

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // إذا كان المشترك Admin يدخل للـ Dashboard
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.abonnements.index');
        }
        
        // إذا كان Client عادي يمشي لصفحة اختيار الخطط
        return redirect()->route('client.plans');
    }

    // هادي هي الدالة لي غاتحل ليك مشكل Undefined variable $sports ✅
    public function showPlans()
    {
        // كنجيبو البيانات من الداتابيز
        $plans = TypeAbonnement::all(); 
        $sports = \App\Models\TypeSeance::all();        

        // كنصيفطوهم للـ View
        return view('client_space.plans', [
            'plans' => $plans,
            'sports' => $sports
        ]);
    }
}