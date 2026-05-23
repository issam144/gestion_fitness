<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Abonnement;
use App\Models\TypeAbonnement;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * عرض قائمة الأعضاء مع كافة الإحصائيات
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->with('typeAbonnement');

        // 1. البحث (بالإسم أو الإيميل)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // 2. الفلترة حسب التاريخ
        if ($request->filled('date')) {
            $query->whereDate('expired_at', $request->date);
        }

        // 3. الفلترة حسب الحالة (نشط / منتهي أو غير مفعل)
        if ($request->filled('filter')) {
            if ($request->filter == 'active') {
                $query->where('expired_at', '>', Carbon::now());
            } elseif ($request->filter == 'expired') {
                $query->where(function($q) {
                    $q->where('expired_at', '<=', Carbon::now())
                      ->orWhereNull('expired_at');
                });
            }
        }

        // 4. الإحصائيات
        $totalMembers = User::where('role', 'client')->count();
        
        $activeMembers = User::where('role', 'client')
                            ->where('expired_at', '>', Carbon::now())
                            ->count();

        $expiredMembers = User::where('role', 'client')
                            ->where(function($q) {
                                $q->where('expired_at', '<=', Carbon::now())
                                  ->orWhereNull('expired_at');
                            })->count();

        $totalCoachs = User::where('role', 'coach')->count(); 
        $totalRevenue = Abonnement::sum('montant_paye');
        
        $todaySeances = Seance::whereDate('created_at', Carbon::today())->get();

        $expirations = User::where('role', 'client')
            ->whereBetween('expired_at', [Carbon::now(), Carbon::now()->addDays(7)])
            ->get();

        $recentInscriptions = User::where('role', 'client')->latest()->take(5)->get();

        $members = $query->latest()->paginate(10)->withQueryString();

        return view('admin_space.members.index', compact(
            'members', 'totalMembers', 'activeMembers', 'expiredMembers', 
            'totalCoachs', 'totalRevenue', 'todaySeances', 'expirations', 'recentInscriptions'
        ));
    }

    /**
     * صفحة تسجيل عضو جديد
     */
    public function create() {
        $types = TypeAbonnement::all();
        return view('admin_space.members.create', compact('types'));
    }

    /**
     * حفظ العضو الجديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20', // حقل الهاتف
            'abonnement_id' => 'required|exists:type_abonnements,id',
        ]);

        // إنشاء العضو 
        $member = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone, 
            'password' => Hash::make('123456'), // كود افتراضي
            'role' => 'client',
            'abonnement_id' => $request->abonnement_id,
            'expired_at' => null, // كيبقى null حتى تخلصو في صفحة Abonnements
            'must_change_password' => true,
            'is_approved' => true, // تم تعديلها لـ TRUE لكي لا يذهب لصفحة التفعيل (Validation) ✅
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Membre enregistré avec succès ! Vous pouvez maintenant activer son contrat dans la section Abonnements.');
    }

    /**
     * صفحة التعديل
     */
    public function edit($id) {
        $member = User::findOrFail($id);
        $types = TypeAbonnement::all();
        return view('admin_space.members.edit', compact('member', 'types'));
    }

    /**
     * تحديث بيانات العضو
     */
    public function update(Request $request, $id) {
        $member = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'abonnement_id' => 'required|exists:type_abonnements,id',
        ]);

        $member->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'abonnement_id' => $request->abonnement_id,
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Profil mis à jour !');
    }

    /**
     * حذف العضو
     */
    public function destroy($id) {
        User::destroy($id);
        return redirect()->back()->with('success', 'Membre supprimé.');
    }
}