<?php

namespace App\Http\Controllers;

use App\Models\TypeAbonnement;
use App\Models\User;
use App\Models\TypeSeance;
use Illuminate\Http\Request;

class TypeAbonnementController extends Controller
{
    /**
     * [FIXED] عرض قائمة الباكات مع جلب الرياضات المرتبطة بها لتفادي خطأ foreach
     */
    public function index() 
    {
        // استعملنا مع (with) لجلب العلاقة ومنع ظهور null في الـ Blade ✅
        $types = TypeAbonnement::with('typeSeances')->latest()->get();
        return view('admin_space.type_abonnements.index', compact('types'));
    }

    /**
     * صفحة إنشاء باك جديد.
     */
    public function create()
    {
        $users = User::where('role', 'client')->get(); 
        $sports = TypeSeance::all(); 

        return view('admin_space.type_abonnements.create', compact('users', 'sports'));
    }

    /**
     * [FIXED] حفظ الباك مع ربط الرياضات المختارة ✅
     */
    public function store(Request $request) 
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'duree_mois' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'sports_ids' => 'nullable|array'
        ]);

        // 1. إنشاء الباك
        $type = TypeAbonnement::create($request->only(['nom', 'duree_mois', 'prix', 'description']));

        // 2. ربط الرياضات المختارة فجدول Pivot
        if ($request->has('sports_ids')) {
            $type->typeSeances()->sync($request->sports_ids);
        }

        return redirect()->route('admin.type-abonnements.index')->with('success', 'Le nouveau tarif a été ajouté avec succès !');
    }

    /**
     * صفحة التعديل.
     */
    public function edit($id)
    {
        $type = TypeAbonnement::with('typeSeances')->findOrFail($id);
        $users = User::where('role', 'client')->get();
        $sports = TypeSeance::all();
        
        // جلب IDs الرياضات المختارة مسبقاً
        $selectedSports = $type->typeSeances->pluck('id')->toArray();

        return view('admin_space.type_abonnements.edit', compact('type', 'users', 'sports', 'selectedSports'));
    }

    /**
     * [FIXED] تحديث الباك وتحديث الرياضات المرتبطة به ✅
     */
    public function update(Request $request, $id) 
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'duree_mois' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'sports_ids' => 'nullable|array'
        ]);
        
        $type = TypeAbonnement::findOrFail($id);
        $type->update($request->only(['nom', 'duree_mois', 'prix', 'description']));
        
        // تحديث الرياضات (sync تقوم بحذف القديم وإضافة الجديد)
        if ($request->has('sports_ids')) {
            $type->typeSeances()->sync($request->sports_ids);
        } else {
            $type->typeSeances()->detach(); // إذا لم يتم اختيار أي رياضة، نقوم بمسح الكل
        }
        
        return redirect()->route('admin.type-abonnements.index')->with('success', 'Le tarif a été mis à jour  .');
    }

    /**
     * حذف الباك.
     */
    public function destroy($id) 
    {
        $type = TypeAbonnement::findOrFail($id);
        $type->typeSeances()->detach(); // حذف الروابط أولاً
        $type->delete();

        return redirect()->route('admin.type-abonnements.index')->with('success', 'Le tarif a été supprimé.');
    }
}