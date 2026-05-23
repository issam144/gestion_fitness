<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Coach;
use App\Models\Seance;
use App\Models\TypeSeance;
use App\Models\Abonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// استيراد التنبيهات (تأكد أن هاد الملفات كاينين عندك)
use App\Notifications\CoachAbsenceNotification;
use App\Notifications\SessionCanceledNotification;

class CoachController extends Controller
{
    /** 
     * ==========================================
     * --- PARTIE ADMIN (Gestion des Coachs) --- 
     * ==========================================
     */
    
    public function index(Request $request) 
    {
        $query = Coach::query()->with(['user', 'typeSeance']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                })->orWhere('telephone', 'like', "%$search%");
            });
        }

        if ($request->filled('filter') && $request->filter !== 'all') {
            $query->where('statut', $request->filter);
        }

        $coachs = $query->latest()->paginate(10)->withQueryString();
        $categories = TypeSeance::all(); 

        return view('admin_space.coachs.index', compact('coachs', 'categories'));
    }

    public function create() {
        $categories = TypeSeance::all(); 
        return view('admin_space.coachs.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'type_seance_id' => 'required|exists:type_seances,id',
            'telephone' => 'required|string',
        ]);

        $category = TypeSeance::find($request->type_seance_id);

        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => Hash::make($request->password), 
            'role' => 'coach',
            'is_approved' => true 
        ]);

        Coach::create([
            'user_id' => $user->id, 
            'type_seance_id' => $request->type_seance_id, 
            'specialite' => $category->nom, 
            'telephone' => $request->telephone, 
            'statut' => 'actif'
        ]);

        return redirect()->route('admin.coachs.index')->with('success', 'Coach ajouté avec succès !');
    }

    public function edit($id) {
        $coach = Coach::with('user')->findOrFail($id);
        $categories = TypeSeance::all(); 
        return view('admin_space.coachs.edit', compact('coach', 'categories'));
    }

    public function update(Request $request, $id) {
        $coach = Coach::findOrFail($id);
        $user = $coach->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'type_seance_id' => 'required|exists:type_seances,id',
            'telephone' => 'required'
        ]);

        $category = TypeSeance::find($request->type_seance_id);
        $user->update(['name' => $request->name, 'email' => $request->email]);
        
        $coach->update([
            'type_seance_id' => $request->type_seance_id,
            'specialite' => $category->nom, 
            'telephone' => $request->telephone
        ]);

        return redirect()->route('admin.coachs.index')->with('success', 'Mise à jour réussie !');
    }

    public function destroy($id) {
        $coach = Coach::findOrFail($id);
        if($coach->user) { $coach->user->delete(); }
        $coach->delete();
        return redirect()->route('admin.coachs.index')->with('success', 'Coach supprimé avec succès !');
    }

    /** 
     * ==========================================
     * --- PARTIE COACH (Espace Opérationnel) --- 
     * ==========================================
     */

    public function dashboard()
    {
        $user = Auth::user();
        $coach = Coach::where('user_id', $user->id)->firstOrFail();

        // حساب عدد الكليان الحقيقيين المخلصين فـ رياضة هاد الكوتش
        $totalMembers = User::where('role', 'client')
            ->whereHas('abonnements', function($query) use ($coach) {
                $query->where('date_fin', '>', now()) 
                      ->whereHas('typeSeances', function($q) use ($coach) {
                          $q->where('type_seance_id', $coach->type_seance_id); 
                      });
            })
            ->distinct()
            ->count();

        $todaySessions = Seance::with('typeSeance')
                            ->where('coach_id', $coach->id)
                            ->whereDate('date_seance', Carbon::today())
                            ->orderBy('heure_seance', 'asc')->get();

        $todaySessionsCount = $todaySessions->count();
        $nextSession = $todaySessions->where('heure_seance', '>=', now()->format('H:i'))->first();

        return view('coach_space.dashboard', compact('coach', 'todaySessions', 'todaySessionsCount', 'totalMembers', 'nextSession'));
    }

    public function members()
    {
        $user = Auth::user();
        $coach = Coach::where('user_id', $user->id)->firstOrFail();

        // جلب كاع الكليان اللي عندهم اشتراك مخلص وفيه رياضة الكوتش
        $members = User::where('role', 'client')
            ->whereHas('abonnements', function($query) use ($coach) {
                $query->where('date_fin', '>', now()) 
                      ->whereHas('typeSeances', function($q) use ($coach) {
                          $q->where('type_seance_id', $coach->type_seance_id); 
                      });
            })
            ->distinct()
            ->paginate(12); 

        return view('coach_space.members', compact('members', 'coach'));
    }

    public function presencesHub()
    {
        $coach = Coach::where('user_id', Auth::id())->firstOrFail();

        $seances = Seance::where('coach_id', $coach->id)
                         ->whereDate('date_seance', Carbon::today()) 
                         ->with('typeSeance')
                         ->orderBy('heure_seance', 'asc')
                         ->paginate(10); 

        return view('coach_space.seances', compact('seances'))->with('mode', 'presence');
    }

    public function seances() 
    {
        $coach = Coach::where('user_id', Auth::id())->firstOrFail();
        
        $seances = Seance::with('typeSeance')
                        ->where('coach_id', $coach->id)
                        ->orderBy('date_seance', 'desc') 
                        ->paginate(10);

        return view('coach_space.seances', compact('seances'))->with('mode', 'archive');
    }

    public function markPresent($id) {
        $seance = Seance::findOrFail($id);
        $seance->update(['statut_coach' => 'present']);
        
        return redirect()->route('coach.seance.members', $id)->with('success', 'MISSION VALIDÉE : ACCÈS AU SQUAD DÉPLOYÉ.');
    }

    public function markAbsent(Request $request, $id) {
        $seance = Seance::with('typeSeance')->findOrFail($id);
        $seance->update([
            'statut_coach' => 'absent',
            'note_admin' => $request->reason ?? 'Signal d\'absence'
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new CoachAbsenceNotification($seance));
        }

        return back()->with('warning', 'SIGNAL D\'ABSENCE TRANSMIS À L\'ADMINISTRATION.');
    }

    public function cancelSession($id) {
        $seance = Seance::with(['typeSeance', 'members'])->findOrFail($id);
        $seance->update(['statut_coach' => 'annulee']);

        foreach ($seance->members as $member) {
            $member->notify(new SessionCanceledNotification($seance));
        }

        return back()->with('danger', 'SÉANCE ANNULÉE : TOUS LES CLIENTS ONT ÉTÉ NOTIFIÉS.');
    }

    public function viewSessionMembers($id) {
        $seance = Seance::with(['typeSeance', 'members'])->findOrFail($id);
        $members = $seance->members;
        return view('coach_space.session_members', compact('seance', 'members'));
    }

    public function markMemberAttendance(Request $request)
    {
        DB::table('seance_member')->updateOrInsert(
            ['seance_id' => $request->seance_id, 'user_id' => $request->user_id],
            ['is_present' => true, 'updated_at' => now()]
        );
        return response()->json(['success' => true, 'message' => 'UNITÉ IDENTIFIÉE']);
    }

    public function profile() {
        $user = Auth::user();
        $coach = Coach::with('typeSeance')->where('user_id', $user->id)->firstOrFail();
        return view('coach_space.profile', compact('user', 'coach'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'specialite' => 'nullable|string'
        ]);

        // تحديث جدول Users
        $user->name = $request->name;
        $user->save();
        
        // تحديث جدول Coach (مباشرة بـ Query لضمان الحفظ)
        $updateData = [
            'telephone' => $request->telephone,
            'specialite' => $request->specialite ?? $user->coach->specialite
        ];

        if ($request->hasFile('image')) {
            if ($user->coach->image) { Storage::disk('public')->delete($user->coach->image); }
            $updateData['image'] = $request->file('image')->store('coachs', 'public');
        }

        Coach::where('user_id', $user->id)->update($updateData);

        return back()->with('success', 'BASE DE DONNÉES MISE À JOUR : PROFIL MODIFIÉ');
    }
}