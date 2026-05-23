<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Coach;
use App\Models\Seance;
use App\Models\Abonnement;
use App\Models\TypeAbonnement;
use App\Models\TypeSeance;
use App\Models\CoachRating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Stripe\Stripe;
use Stripe\Charge;

class DashboardController extends Controller
{
    /** 
     * --- ESPACE ADMIN ---
     */
    public function adminIndex() 
    {
        $totalMembers = User::where('role', 'client')->count();
        $activeMembers = Abonnement::where('date_fin', '>', now())->count();
        $totalCoachs = Coach::count(); 
        $totalRevenue = Abonnement::sum('montant_paye') ?? 0; 
        $todaySeances = Seance::whereDate('date_seance', now()->today())->with(['coach.user', 'type_seance'])->get();
        return view('admin_space.dashboard', compact('totalMembers', 'activeMembers', 'totalCoachs', 'totalRevenue', 'todaySeances'));
    }

    public function profile() 
    {
        return view('admin_space.profile', ['user' => Auth::user()]);
    }

    /** 
     * --- ESPACE CLIENT ---
     */
    public function clientIndex() 
    {
        $user = User::find(Auth::id());

        if (!$user->expired_at || Carbon::parse($user->expired_at)->isPast()) {
            return redirect()->route('client.plans')->with('info', 'Accès restreint. Activez votre protocole.');
        }

        $daysLeft = max(0, (int)now()->diffInDays(Carbon::parse($user->expired_at), false));
        $totalPresence = DB::table('seance_member')->where('user_id', $user->id)->count();
        $recentSessions = $user->seances()->with('type_seance')->orderBy('date_seance', 'desc')->take(5)->get();
        $abonnement = Abonnement::where('user_id', $user->id)->latest()->first();

        return view('client_space.dashboard', compact('user', 'daysLeft', 'totalPresence', 'recentSessions', 'abonnement'));
    }

    public function clientProfile() 
    {
        return view('client_space.profile', ['user' => Auth::user()]);
    }

    /** 
     * --- ESPACE COACH ---
     */
    public function coachIndex() 
    {
        $user = Auth::user();
        $coach = $user->coach;
        if (!$coach) return redirect()->route('login');
        
        $members = User::where('role', 'client')->whereHas('abonnements', function($q) use ($coach) {
            $q->where('date_fin', '>', now())->whereHas('typeSeances', function($sq) use ($coach) {
                $sq->where('type_seance_id', $coach->type_seance_id);
            });
        })->distinct()->get();

        $todaySessions = Seance::where('coach_id', $coach->id)->whereDate('date_seance', now()->today())->with('type_seance')->get();
        return view('coach_space.dashboard', compact('todaySessions', 'members', 'coach'));
    }

    /** 
     * --- SPORTS & PLANS ---
     */
    public function allSports() 
    {
        $sports = TypeSeance::all();
        return view('client_space.sports.index', compact('sports'));
    }

    public function showPlans() { 
        return view('client_space.plans', ['plans' => TypeAbonnement::all(), 'sports' => TypeSeance::all()]); 
    }
    
    public function checkout($plan_id) { 
        $plan = TypeAbonnement::findOrFail($plan_id);
        session()->forget('custom_squad');
        return view('client_space.checkout', compact('plan')); 
    }

    public function checkoutCustom(Request $request) {
        $request->validate(['sports' => 'required|array', 'months' => 'required|integer']);
        $total = (200 + ((count($request->sports)-1)*50)) * $request->months;
        session(['custom_squad' => ['sports_ids' => $request->sports, 'months' => $request->months, 'total_price' => $total]]);
        return view('client_space.checkout', ['plan' => null, 'totalPrice' => $total, 'isCustom' => true]);
    }

    /** 
     * --- STRIPE PAYMENT ---
     */
    public function processPayment(Request $request) 
    {
        $user = Auth::user();
        $isCustom = session()->has('custom_squad');
        $customData = session('custom_squad');

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $amount = $isCustom ? $customData['total_price'] : TypeAbonnement::findOrFail($request->plan_id)->prix;
            $duration = $isCustom ? (int)$customData['months'] : (int)TypeAbonnement::find($request->plan_id)->duree_mois;
            $planId = $isCustom ? null : $request->plan_id;

            Charge::create([
                "amount" => $amount * 100, "currency" => "mad",
                "source" => $request->stripeToken, "description" => "FitPro Payment - " . $user->email,
            ]);

            $expirationDate = now()->addMonths($duration);

            DB::transaction(function () use ($user, $planId, $amount, $expirationDate, $isCustom, $customData) {
                DB::table('users')->where('id', $user->id)->update([
                    'abonnement_id' => $planId, 'expired_at' => $expirationDate, 'is_approved' => 1, 'updated_at' => now()
                ]);

                $abonnement = Abonnement::create([
                    'user_id' => $user->id, 'type_abonnement_id' => $planId,
                    'date_debut' => now(), 'date_fin' => $expirationDate, 'montant_paye' => $amount,
                ]);

                if ($isCustom) {
                    $abonnement->typeSeances()->sync($customData['sports_ids']);
                } else {
                    $sportsOfPlan = TypeAbonnement::find($planId)->typeSeances->pluck('id');
                    $abonnement->typeSeances()->sync($sportsOfPlan);
                }
            });

            Auth::login(User::find($user->id)); 
            session()->forget('custom_squad');
            session()->save();

            return redirect('/client/dashboard')->with('success', 'SYSTÈME ACTIVÉ !');
        } catch (\Exception $e) {
            return redirect()->route('client.plans')->with('error', $e->getMessage());
        }
    }

    /** 
     * --- COACHES & RATINGS ---
     */
    public function coachsBySport(Request $request, $sportId) {
        $user = Auth::user();
        $sport = TypeSeance::findOrFail($sportId);
        $hasAccess = Abonnement::where('user_id', $user->id)->where('date_fin', '>', now())
                     ->whereHas('typeSeances', function($q) use ($sportId) { $q->where('type_seance_id', $sportId); })->exists();
        $coachs = Coach::where('type_seance_id', $sportId)->with('user')->paginate(8);
        return view('client_space.sports.coachs', compact('coachs', 'sport', 'hasAccess'));
    }

    public function joinCoach(Request $request, $id) {
        User::where('id', Auth::id())->update(['coach_id' => $id]);
        return back()->with('success', 'COMMANDANT ASSIGNÉ !');
    }

    public function rateCoach(Request $request, $id) {
        $request->validate(['stars' => 'required|integer']);
        CoachRating::updateOrCreate(['user_id' => Auth::id(), 'coach_id' => $id], ['stars' => $request->stars]);
        $coach = Coach::findOrFail($id);
        $coach->update(['rating' => CoachRating::where('coach_id', $id)->avg('stars')]);
        return back()->with('success', 'ÉVALUÉ !');
    }

    /** 
     * --- GLOBAL PLANNING ---
     */
    public function globalPlanning() 
    { 
        $allSeances = Seance::with(['type_seance', 'coach.user'])->orderBy('date_seance', 'asc')->get();
        $groupedSeances = $allSeances->groupBy('date_seance'); 

        $startOfWeek = now()->startOfWeek();
        $weeklyPlanning = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDate = $startOfWeek->copy()->addDays($i);
            $dateString = $currentDate->toDateString();
            $weeklyPlanning[$dateString] = [
                'day_name' => $currentDate->translatedFormat('l'),
                'date'     => $currentDate->format('d M'),
                'sessions' => $groupedSeances->get($dateString, collect())
            ];
        }

        return view('client_space.planning', [
            'allSeances' => $allSeances,
            'weeklyPlanning' => $weeklyPlanning
        ]); 
    }

    /** 
     * --- HISTORY & RECEIPTS ---
     */
    public function clientHistory() { 
        $sessions = Auth::user()->seances()->latest()->paginate(10); 
        return view('client_space.history', compact('sessions')); 
    }

    public function downloadReceipt() {
        $user = Auth::user();
        $abonnement = Abonnement::where('user_id', $user->id)->latest()->first();
        $pdf = Pdf::loadView('client_space.receipt', compact('user', 'abonnement'));
        return $pdf->download('receipt-fitpro.pdf');
    }
}