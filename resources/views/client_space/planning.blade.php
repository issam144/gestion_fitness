@extends('layouts.app')

@section('page_title', 'PLANIFICATION GLOBALE')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <span class="text-accent tiny-caps letter-spacing-2">Flux en Direct • Programme Complet de la Salle</span>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase">PLANNING <span style="color: var(--accent);">GLOBAL</span></h1>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="text-secondary small uppercase">Total Séances Détectées</span>
            <h3 class="text-white fw-900 m-0">{{ $allSeances->flatten()->count() }} UNITÉS</h3>
        </div>
    </div>

    <!-- GRILLE PLANNING HEBDOMADAIRE -->
    <div class="validation-terminal overflow-hidden mb-5">
        <div class="terminal-header d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-white border-opacity-5">
            <span class="tiny-caps text-white fw-900 letter-spacing-sm uppercase">
                <i class="fas fa-calendar-alt text-accent me-2"></i> PLANIFICATION OPÉRATIONNELLE HEBDOMADAIRE
            </span>
        </div>
        
        <div class="planning-grid-wrapper p-2">
            <div class="planning-grid">
                @if(isset($weeklyPlanning))
                    @foreach($weeklyPlanning as $date => $data)
                    <div class="day-column {{ \Carbon\Carbon::parse($date)->isToday() ? 'is-today' : '' }}">
                        <div class="day-header uppercase fw-900 letter-spacing-xs">
                            {{ $data['day_name'] }} <br>
                            <span class="small text-secondary">{{ $data['date'] }}</span>
                        </div>

                        <div class="day-content">
                            @forelse($data['sessions'] as $session)
                            <div class="session-card-mini">
                                <div class="time fw-900">{{ \Carbon\Carbon::parse($session->heure_seance)->format('H:i') }}</div>
                                <div class="activity uppercase fw-700 text-accent">{{ $session->typeSeance->nom ?? 'SÉANCE' }}</div>
                                <div class="coach-name tiny-caps">{{ $session->coach->user->name ?? 'N/A' }}</div>
                                <div class="deco-line"></div>
                            </div>
                            @empty
                            <div class="no-session text-center tiny-caps">AUCUNE MISSION</div>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="text-center py-5 opacity-50 col-span-7">
                    <i class="fas fa-satellite-dish fa-3x mb-3 text-accent"></i>
                    <h5 class="text-white uppercase">Aucune séance planifiée pour le moment</h5>
                    <p class="text-secondary tiny-caps mt-2">Revenez plus tard pour consulter le programme</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }

    .status-indicator-pulse { width: 12px; height: 12px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }

    .validation-terminal { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.03); border-radius: 20px; }
    .terminal-header { background: rgba(255,255,255,0.02); }
    .planning-grid-wrapper { overflow-x: auto; }
    .planning-grid-wrapper::-webkit-scrollbar { height: 4px; }
    .planning-grid-wrapper::-webkit-scrollbar-thumb { background: var(--accent); border-radius: 10px; }
    .planning-grid { display: grid; grid-template-columns: repeat(7, minmax(150px, 1fr)); gap: 8px; }
    .day-column { background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); min-height: 350px; border-radius: 10px; transition: 0.3s; }
    .day-column.is-today { border-color: var(--accent); background: rgba(255,237,0,0.02); }
    .day-header { background: rgba(255,255,255,0.02); text-align: center; padding: 12px 5px; font-size: 11px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .day-column.is-today .day-header { background: var(--accent); color: #000; }
    .day-content { padding: 8px; }
    .session-card-mini { background: #111; border: 1px solid rgba(255,255,255,0.05); border-radius: 6px; padding: 10px; margin-bottom: 8px; position: relative; }
    .session-card-mini .time { font-size: 13px; color: #fff; }
    .session-card-mini .activity { font-size: 10px; line-height: 1.2; margin: 3px 0; }
    .session-card-mini .coach-name { font-size: 8px; color: rgba(255,255,255,0.3); }
    .session-card-mini .deco-line { position: absolute; top: 0; right: 0; bottom: 0; width: 3px; background: var(--accent); border-radius: 0 6px 6px 0; opacity: 0.5; }
    .no-session { color: rgba(255,255,255,0.1); padding: 20px 0; font-size: 8px; }

    @keyframes pGlow { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
</style>
@endsection