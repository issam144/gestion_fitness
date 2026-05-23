@extends('layouts.app')

@section('page_title', 'CENTRE DE COMMANDEMENT COACH')

@section('content')
<div class="container-fluid py-4 px-lg-5 dashboard-main">
    
    <!-- 1. En-tête HUD Opérationnel -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-sm fw-700">STATUT OPÉRATIONNEL : COACH NIVEAU 02 AUTORISÉ</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase letter-spacing-lg">TABLEAU DE <span style="color: var(--accent);">BORD COACH</span></h1>
        </div>
        <div class="col-md-5 text-md-end d-none d-md-block">
            <div class="scanner-info-bar py-3 px-4 shadow-neon">
                <span class="text-secondary tiny-caps me-3 uppercase fw-700">HEURE DE MISSION : <span id="live-clock" class="text-white fw-900 h5 mb-0">--:--:--</span></span>
                <span class="status-label-live uppercase fw-900 letter-spacing-xs"><i class="fas fa-satellite me-1"></i> EN LIGNE</span>
            </div>
        </div>
    </div>

    <!-- 2. Métriques Tactiques -->
    <div class="row g-4 mb-5">
        <!-- Missions du Jour -->
        <div class="col-md-4">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid #00ff88">
                <span class="stat-label uppercase fw-700 letter-spacing-xs">MISSIONS DU JOUR</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-white m-0 letter-spacing-xs">{{ $todaySessionsCount }}</h2>
                    <div class="scan-icon-mini" style="background: rgba(0, 255, 136, 0.1); color: #00ff88">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="mini-signal-bar mt-3"><div class="fill" style="width: 100%; background: #00ff88"></div></div>
            </div>
        </div>

        <!-- Adhérents Assignés -->
        <div class="col-md-4">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid #00d2ff">
                <span class="stat-label uppercase fw-700 letter-spacing-xs">ADHÉRENTS ASSIGNÉS</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-white m-0 letter-spacing-xs">{{ $totalMembers }}</h2>
                    <div class="scan-icon-mini" style="background: rgba(0, 210, 255, 0.1); color: #00d2ff">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
                <div class="mini-signal-bar mt-3"><div class="fill" style="width: 75%; background: #00d2ff"></div></div>
            </div>
        </div>

        <!-- Prochain Objectif -->
        <div class="col-md-4">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid var(--accent)">
                <span class="stat-label uppercase fw-700 letter-spacing-xs">PROCHAIN OBJECTIF</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-accent m-0 letter-spacing-xs">
                        {{ $nextSession ? \Carbon\Carbon::parse($nextSession->heure_seance)->format('H:i') : 'TERMINÉ' }}
                    </h2>
                    <div class="scan-icon-mini pulse-yellow">
                        <i class="fas fa-bolt"></i>
                    </div>
                </div>
                <span class="tiny-caps text-white-50 mt-2 d-block uppercase fw-700">
                    {{ $nextSession ? ($nextSession->type_seance->nom ?? 'SÉANCE STANDARD') : 'TOUTES LES TÂCHES COMPLÉTÉES' }}
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Terminal de Validation - Planning du Jour -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-white border-opacity-5">
            <span class="tiny-caps text-white fw-900 letter-spacing-sm uppercase"><i class="fas fa-terminal text-accent me-2"></i> CHRONOLOGIE DES ENTRAÎNEMENTS</span>
            <a href="{{ route('coach.seances') }}" class="btn-scan-action uppercase fw-900" style="font-size: 10px;">VOIR ARCHIVES</a>
        </div>
        <div class="table-responsive">
            <table class="table table-dark m-0">
                <thead>
                    <tr class="tiny-caps text-secondary uppercase" style="font-size: 10px;">
                        <th class="ps-4 py-3 letter-spacing-xs">HORAIRE DÉBUT</th>
                        <th class="letter-spacing-xs">UNITÉ DE DISCIPLINE</th>
                        <th class="text-center letter-spacing-xs">STATUT MISSION</th>
                        <th class="text-end pe-5 letter-spacing-xs">DÉPLOIEMENT</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todaySessions as $s)
                    <tr class="terminal-row-hover">
                        <td class="ps-4 py-3">
                            <div class="user-avatar-hex d-inline-flex" style="width: 35px; height: 35px; vertical-align: middle;">
                                <span class="hex-text" style="font-size: 10px;"><i class="fas fa-clock"></i></span>
                                <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                            </div>
                            <span class="text-accent fw-900 h6 ms-2 letter-spacing-xs">{{ \Carbon\Carbon::parse($s->heure_seance)->format('H:i') }}</span>
                        </td>
                        <td>
                            <span class="badge-status-elite active fw-900 uppercase">{{ $s->type_seance->nom ?? 'STANDARD' }}</span>
                        </td>
                        <td class="text-center">
                            @if($s->statut_coach == 'present')
                                <span class="tiny-caps text-success fw-700 uppercase"><i class="fas fa-check-double me-1"></i> EFFECTUÉE</span>
                            @elseif($s->statut_coach == 'absent')
                                <span class="tiny-caps text-danger fw-700 uppercase"><i class="fas fa-times-circle me-1"></i> ANNULÉE</span>
                            @else
                                <span class="tiny-caps text-success fw-700 uppercase"><i class="fas fa-circle-notch fa-spin me-1"></i> EN ATTENTE</span>
                            @endif
                        </td>
                        <td class="text-end pe-5">
                            @if($s->statut_coach == 'pending')
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('coach.seance.present', $s->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn-op-approve py-2 px-3 uppercase fw-900" style="font-size: 9px;">
                                            <i class="fas fa-id-badge me-1"></i> PRÉSENCE
                                        </button>
                                    </form>
                                    <form action="{{ route('coach.seance.absent', $s->id) }}" method="POST" class="m-0" onsubmit="return confirm('Signaler votre absence ?')">
                                        @csrf
                                        <button type="submit" style="background: rgba(255, 62, 62, 0.1); color: #ff3e3e; border: 1px solid #ff3e3e33; padding: 7px 12px; border-radius: 8px;" class="uppercase fw-900">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @elseif($s->statut_coach == 'present')
                                <a href="{{ route('coach.seance.members', $s->id) }}" class="btn-op-approve py-2 px-3 uppercase fw-900" style="font-size: 9px; background: rgba(0, 210, 255, 0.1); color: #00d2ff; border: 1px solid #00d2ff33;">
                                    <i class="fas fa-users me-1"></i> DÉTAILS SQUAD
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 tiny-caps opacity-30 text-white fw-700 uppercase">AUCUNE MISSION DÉTECTÉE POUR AUJOURD'HUI</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    a { text-decoration: none !important; }

    .fw-900 { font-weight: 900; }
    .fw-700 { font-weight: 700; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }

    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Stats & Cards */
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; display: inline-flex; flex-direction: column; justify-content: center; }
    .status-label-live { background: rgba(0, 255, 136, 0.1); color: #00ff88; font-size: 9px; padding: 4px 12px; border-radius: 50px; }
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 15px; position: relative; }
    .stat-label { color: rgba(255,255,255,0.3); display: block; margin-bottom: 5px; }
    .scan-icon-mini { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    
    /* Terminal UI */
    .validation-terminal { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.03); border-radius: 20px; overflow: hidden; }
    .terminal-header { background: rgba(255,255,255,0.02); }
    .terminal-row-hover:hover { background: rgba(255,255,255,0.01); transition: 0.2s; }
    .table-dark { background: transparent !important; }
    .table-dark td { border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle; }

    /* Hexagon Profile Elements */
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .hex-text { color: #fff; z-index: 2; }

    /* Interactive Buttons */
    .badge-status-elite { padding: 4px 12px; border-radius: 4px; font-size: 10px; background: rgba(0, 210, 255, 0.1); color: #00d2ff; border: 1px solid rgba(0, 210, 255, 0.2); }
    .btn-scan-action { background: var(--accent); color: #000; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; }
    .btn-op-approve { background: var(--accent); color: #000; border: none; border-radius: 8px; transition: 0.3s; display: inline-flex; align-items: center; cursor: pointer; font-size: 11px; }
    .btn-op-approve:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 237, 0, 0.3); background: #fff; }

    /* Animations */
    .status-indicator-pulse { width: 12px; height: 12px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    @keyframes pGlow { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.1); } 100% { opacity: 1; transform: scale(1); } }
    .pulse-yellow { animation: pGlow 2s infinite; }
    .mini-signal-bar { height: 2px; background: rgba(255,255,255,0.05); border-radius: 2px; overflow: hidden; margin-top: 10px; }
    .mini-signal-bar .fill { height: 100%; transition: 1.5s; }
</style>

<script>
    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').textContent = now.toLocaleTimeString('fr-FR');
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection