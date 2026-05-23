<?php

namespace App\Http\Controllers;

use App\Models\TypeSeance;
use Illuminate\Http\Request;

class TypeSeanceController extends Controller
{
    public function index()
    {
        // latest() كتحط ديما الجديد هو الأول
        $types = TypeSeance::latest()->get();
        return view('admin_space.type_seances.index', compact('types'));
    }

    public function create()
    {
        return view('admin_space.type_seances.create');
    }

    public function store(Request $request)
    {
        // زدنا icon ف الـ validation
        $request->validate([
            'nom' => 'required|string|max:100|unique:type_seances,nom',
            'icon' => 'required|string', 
        ]);

        TypeSeance::create($request->all());

        return redirect()->route('admin.type-seances.index')->with('success', 'Catégorie ajoutée avec succès !');
    }

    public function edit($id)
    {
        $type = TypeSeance::findOrFail($id);
        return view('admin_space.type_seances.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        // ضروري تزيد icon هنا باش لارايفل يعترف بيها
        $request->validate([
            'nom' => 'required|string|max:100',
            'icon' => 'required|string', 
        ]);

        $type = TypeSeance::findOrFail($id);
        $type->update($request->all());

        return redirect()->route('admin.type-seances.index')->with('success', 'Catégorie mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $type = TypeSeance::findOrFail($id);
        $type->delete();

        return redirect()->route('admin.type-seances.index')->with('success', 'Catégorie supprimée !');
    }
}