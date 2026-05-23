<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use App\Models\Coach;
use App\Models\TypeSeance;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewSeanceNotification;

class SeanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Seance::with(['coach.user', 'type_seance']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('coach.user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                })
                ->orWhereHas('type_seance', function($ts) use ($search) {
                    $ts->where('nom', 'like', "%$search%");
                });
            });
        }

        $seances = $query->latest('date_seance')
                         ->latest('heure_seance')
                         ->paginate(10)
                         ->withQueryString();

        return view('admin_space.seances.index', compact('seances'));
    }

    // ✅ هنا غير هاد السطر
    public function create()
    {
        $members = User::where('role', 'client')
            ->with(['abonnements.typeAbonnement.typeSeances'])
            ->get();
        $types = TypeSeance::all();
        $coachs = Coach::with('user')->get();

        return view('admin_space.seances.create', compact('members', 'types', 'coachs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_seance' => 'required|date',
            'heure_seance' => 'required',
            'coach_id' => 'required|exists:coachs,id',
            'type_seance_id' => 'required|exists:type_seances,id',
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:users,id',
        ]);

        $exists = Seance::where('coach_id', $request->coach_id)
            ->where('date_seance', $request->date_seance)
            ->where('heure_seance', $request->heure_seance)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Ce coach a déjà une séance à cette heure !'])->withInput();
        }

        $seance = Seance::create([
            'date_seance' => $request->date_seance,
            'heure_seance' => $request->heure_seance,
            'coach_id' => $request->coach_id,
            'type_seance_id' => $request->type_seance_id,
        ]);

        $seance->members()->attach($request->member_ids);

        $selectedMembers = User::whereIn('id', $request->member_ids)->get();
        if ($selectedMembers->count() > 0) {
            Notification::send($selectedMembers, new NewSeanceNotification($seance));
        }

        $coach = Coach::with('user')->find($request->coach_id);
        if ($coach && $coach->user) {
            $coach->user->notify(new NewSeanceNotification($seance));
        }

        return redirect()->route('admin.seances.index')->with('success', 'Séance créée et notifications envoyées !');
    }

    public function updatePresence(Request $request, $id)
    {
        $seance = Seance::with('members')->findOrFail($id);
        $presences = $request->input('presence', []);

        foreach ($seance->members as $member) {
            $isPresent = isset($presences[$member->id]);
            $seance->members()->updateExistingPivot($member->id, [
                'is_present' => $isPresent
            ]);
        }

        return back()->with('success', 'DONNÉES SYNCHRONISÉES AVEC LE QG');
    }

    public function edit(Seance $seance)
    {
        $coachs = Coach::with('user')->get();
        $types = TypeSeance::all();
        return view('admin_space.seances.edit', compact('seance', 'coachs', 'types'));
    }

    public function update(Request $request, Seance $seance)
    {
        $request->validate([
            'date_seance' => 'required|date',
            'heure_seance' => 'required',
            'coach_id' => 'required|exists:coachs,id',
            'type_seance_id' => 'required|exists:type_seances,id',
        ]);

        $exists = Seance::where('coach_id', $request->coach_id)
            ->where('date_seance', $request->date_seance)
            ->where('heure_seance', $request->heure_seance)
            ->where('id', '!=', $seance->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'Ce coach a déjà une séance à cette heure !'])->withInput();
        }

        $seance->update($request->all());

        return redirect()->route('admin.seances.index')->with('success', 'Séance mise à jour !');
    }

    public function destroy(Seance $seance)
    {
        $seance->delete();
        return redirect()->route('admin.seances.index')->with('success', 'Séance supprimée !');
    }
}