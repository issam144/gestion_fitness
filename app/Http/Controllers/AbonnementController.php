<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\TypeAbonnement;
use App\Models\TypeSeance; 
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbonnementController extends Controller
{
    /**
     * عرض قائمة الاشتراكات للـ Admin
     */
    public function index(Request $request)
    {
        $query = Abonnement::with(['user', 'typeAbonnement', 'typeSeances']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $totalRevenue = Abonnement::sum('montant_paye');
        $activeAbonnementsCount = Abonnement::where('date_fin', '>=', now())->count();
        $abonnements = $query->latest()->paginate(10)->withQueryString();

        return view('admin_space.abonnements.index', compact('abonnements', 'totalRevenue', 'activeAbonnementsCount'));
    }

    /**
     * صفحة إنشاء اشتراك جديد (Admin)
     */
    public function create()
    {
        $types = TypeAbonnement::all(); 
        $sports = TypeSeance::all();    
        $users = User::where('role', 'client')->get(); 
        return view('admin_space.abonnements.create', compact('types', 'users', 'sports'));
    }

    /**
     * حفظ الاشتراك (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'montant_paye' => 'required|numeric',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $totalToPay = 0;
                if ($request->type_abonnement_id) {
                    $p = TypeAbonnement::find($request->type_abonnement_id);
                    $totalToPay += $p->prix;
                }
                $count = count($request->sports_ids ?? []);
                if ($count > 0) $totalToPay += 200 + (($count - 1) * 50);

                $abonnement = Abonnement::create([
                    'user_id'            => $request->user_id,
                    'type_abonnement_id' => $request->type_abonnement_id,
                    'date_debut'         => $request->date_debut,
                    'date_fin'           => $request->date_fin,
                    'montant_paye'       => $totalToPay,
                ]);

                if ($request->has('sports_ids')) {
                    $abonnement->typeSeances()->sync($request->sports_ids);
                }

                $user = User::find($request->user_id);
                $user->update([
                    'abonnement_id' => $request->type_abonnement_id,
                    'expired_at' => $request->date_fin,
                    'is_approved' => true
                ]);
            });

            return redirect()->route('admin.abonnements.index')->with('success', 'CONTRAT DÉPLOYÉ.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * صفحة تعديل الاشتراك (هادي لي كانت ناقصاك ✅)
     */
    public function edit(Abonnement $abonnement)
    {
        $types = TypeAbonnement::all();
        $sports = TypeSeance::all();
        $users = User::where('role', 'client')->get();
        $selectedSports = $abonnement->typeSeances->pluck('id')->toArray();

        return view('admin_space.abonnements.edit', compact('abonnement', 'types', 'users', 'sports', 'selectedSports'));
    }

    /**
     * تحديث الاشتراك (هادي حتى هي ضرورية ✅)
     */
    public function update(Request $request, Abonnement $abonnement)
    {
        $request->validate(['user_id' => 'required', 'date_debut' => 'required', 'date_fin' => 'required']);

        try {
            DB::transaction(function () use ($request, $abonnement) {
                $abonnement->update($request->all());
                if ($request->has('sports_ids')) {
                    $abonnement->typeSeances()->sync($request->sports_ids);
                }
                $user = User::find($request->user_id);
                $user->update(['expired_at' => $request->date_fin]);
            });
            return redirect()->route('admin.abonnements.index')->with('success', 'CONTRAT MIS À JOUR.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * معالجة طلب الكليان (Custom Squad)
     */
    public function handleCustomCheckout(Request $request)
    {
        $request->validate(['sports' => 'required|array', 'months' => 'required|integer']);
        $sportsCount = count($request->sports);
        $monthlyPrice = 200 + (($sportsCount - 1) * 50);
        $totalPrice = $monthlyPrice * (int)$request->months;

        session(['custom_squad' => [
            'sports_ids' => $request->sports,
            'months' => (int)$request->months,
            'total_price' => $totalPrice,
            'is_custom' => true
        ]]);

        return redirect()->route('client.checkout.view'); 
    }

    /**
     * عرض صفحة الـ Checkout للكليان
     */
    public function showCheckout()
    {
        $customData = session('custom_squad');
        if (!$customData) return redirect()->route('client.plans');

        $selectedSports = TypeSeance::whereIn('id', $customData['sports_ids'])->get();
        $plan = (object)[
            'id' => null,
            'nom' => 'MISSION SQUAD CUSTOM',
            'prix' => $customData['total_price'],
            'duree_mois' => $customData['months']
        ];

        return view('client_space.checkout', compact('plan', 'customData', 'selectedSports'));
    }

    /**
     * حذف الاشتراك
     */
    public function destroy(Abonnement $abonnement)
    {
        $abonnement->delete();
        return back()->with('success', 'Supprimé.');
    }
}