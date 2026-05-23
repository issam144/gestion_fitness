<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coach; 
use App\Models\Seance;
use App\Models\Abonnement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SessionCanceledNotification;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * الصفحة الرئيسية للأدمن (Dashboard) ✅
     */
    public function index()
    {
        $notifications = Auth::user()->unreadNotifications()->take(5)->get();

        $totalMembers = User::where('role', 'client')->count();
        $activeMembers = User::where('role', 'client')->where('is_approved', true)->count();
        $totalCoachs = Coach::count();
        
        $totalRevenue = DB::table('abonnements')->sum('montant_paye') ?? 0; 

        $todaySeances = Seance::with(['typeSeance', 'coach.user'])
                            ->whereDate('date_seance', Carbon::today())
                            ->orderBy('heure_seance', 'asc')
                            ->get();
        $todaySeancesCount = $todaySeances->count();

        $nextSession = Seance::with('typeSeance')
                            ->whereDate('date_seance', Carbon::today())
                            ->where('heure_seance', '>=', now()->format('H:i'))
                            ->orderBy('heure_seance', 'asc')
                            ->first();

        $startOfWeek = Carbon::now()->startOfWeek();
        $weeklyPlanning = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weeklyPlanning[$date->toDateString()] = [
                'day_name' => $date->translatedFormat('l'),
                'date' => $date->format('d M'),
                'sessions' => Seance::with(['typeSeance', 'coach.user'])
                                    ->whereDate('date_seance', $date)
                                    ->get()
            ];
        }

        $expirations = Abonnement::with('user')
                                ->where('date_fin', '<=', Carbon::now()->addDays(7))
                                ->where('date_fin', '>=', Carbon::now())
                                ->get();

        $recentInscriptions = User::where('role', 'client')
                                ->latest()
                                ->take(6)
                                ->get();

        return view('admin_space.dashboard', compact(
            'notifications', 'totalMembers', 'activeMembers', 'totalCoachs', 'totalRevenue',
            'todaySeances', 'todaySeancesCount', 'nextSession', 'weeklyPlanning', 'expirations', 'recentInscriptions'
        ));
    }

    /**
     * إدارة وحدات التدريب (Coachs Management) ✅
     * هادي هي اللي غتحكم ف السكنر والفيلتر ديال الكوتشات
     */
    public function coachsIndex(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all');

        $query = Coach::with(['user', 'typeSeance']);

        // سكنر بالسمية أو بـ ID
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', '%' . str_replace('SIG-', '', $search) . '%');
            });
        }

        // فيلتر actif / inactif
        if ($filter === 'actif') {
            $query->where('statut', 'actif');
        } elseif ($filter === 'inactif') {
            $query->where('statut', 'inactif');
        }

        $coachs = $query->latest()->paginate(10)->withQueryString();

        return view('admin_space.coachs.index', compact('coachs'));
    }

    /**
     * [NEW] جلب الباك المختار للعضو (AJAX) ✅
     */
    public function getMemberPack($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'abonnement_id' => $user->abonnement_id, 
        ]);
    }

    /**
     * عرض قائمة المستخدمين الذين ينتظرون التفعيل
     */
    public function pendingUsers(Request $request)
    {
        $search = $request->input('search');
        $query = User::where('is_approved', false);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('admin_space.pending_users', compact('users'));
    }

    /**
     * تقرير أداء المدربين (Intel Report) ✅
     */
    public function coachReports(Request $request) 
    {
        $search = $request->input('search');

        $coachs = Coach::with(['user', 'typeSeance']) 
            ->withCount(['seances' => function($query) {
                $query->where('statut_coach', 'present');
            }])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('typeSeance', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('admin_space.reports.coachs', compact('coachs'));
    }

    /**
     * قبول المستخدم وتفعيل رتبته
     */
    public function approveUser(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:admin,coach,client']);
        $user = User::findOrFail($id);
        
        $user->update([
            'role' => $request->role,
            'is_approved' => true
        ]);

        if ($request->role === 'coach') {
            Coach::firstOrCreate(['user_id' => $user->id], [
                'specialite' => 'À définir',
                'statut'     => 'actif',
                'telephone'  => '0600000000'
            ]);
        }

        return back()->with('success', "PROTOCOL EXECUTED: Access granted as " . strtoupper($request->role));
    }

    /**
     * إلغاء حصة من طرف الأدمن وإرسال تنبيه للمنخرطين
     */
    public function cancelSeanceByAdmin($id) 
    {
        $seance = Seance::with('members')->findOrFail($id);
        $seance->update(['statut_coach' => 'annulee']); 

        foreach ($seance->members as $member) {
            $member->notify(new SessionCanceledNotification($seance));
        }

        return back()->with('success', 'SÉANCE ANNULÉE : CLIENTS NOTIFIÉS PAR SIGNAL.');
    }

    /**
     * رفض الطلب ومسح البيانات
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', "TERMINATED: Incoming access request has been cleared.");
    }
}