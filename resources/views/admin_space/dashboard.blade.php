@extends('layouts.app')

@section('page_title', 'TABLEAU DE BORD')

@section('content')
<div class="container-fluid py-4 px-lg-5 dashboard-main">
    
    <!-- 1. En-tête HUD Opérationnel -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-5">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-sm fw-700">RENSEIGNEMENT OPÉRATIONNEL : NIVEAU 03 AUTORISÉ</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase letter-spacing-lg">CENTRE DE <span style="color: var(--accent);">COMMANDE</span></h1>
        </div>

        <div class="col-md-3">
            <div class="scanner-info-bar py-2 px-3 shadow-neon">
                <span class="text-secondary tiny-caps d-block uppercase fw-700" style="font-size: 8px; opacity: 0.6;">PROCHAINE SÉANCE :</span>
                <span class="text-accent fw-900 small uppercase letter-spacing-xs">
                    @if(isset($nextSession) && $nextSession)
                        {{ $nextSession->typeSeance->nom ?? 'Standard' }} @ {{ \Carbon\Carbon::parse($nextSession->heure_seance)->format('H:i') }}
                    @else
                        AUCUNE SÉANCE PRÉVUE
                    @endif
                </span>
            </div>
        </div>

        <div class="col-md-4 text-md-end">
            <div class="scanner-info-bar py-3 px-4 shadow-neon">
                <span class="text-secondary tiny-caps me-3 uppercase fw-700">HEURE SYSTÈME : <span id="live-clock" class="text-white fw-900 h5 mb-0">--:--:--</span></span>
                <span class="status-label-live uppercase fw-900 letter-spacing-xs"><i class="fas fa-satellite me-1"></i> EN DIRECT</span>
            </div>
        </div>
    </div>

    <!-- 2. Actions Rapides -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="validation-terminal p-4 border-accent-dim">
                <h6 class="tiny-caps text-accent mb-4 fw-900 letter-spacing-sm"><i class="fas fa-bolt me-2"></i> ACTIONS RAPIDES</h6>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('admin.members.create') }}" class="btn-quick-ops uppercase fw-900"><i class="fas fa-user-plus me-2"></i> NOUVEL ADHÉRENT</a>
                    <a href="{{ route('admin.abonnements.create') }}" class="btn-quick-ops uppercase fw-900"><i class="fas fa-credit-card me-2"></i> NOUVEAU PAIEMENT</a>
                    <a href="{{ route('admin.seances.create') }}" class="btn-quick-ops uppercase fw-900"><i class="fas fa-calendar-plus me-2"></i> PLANIFIER SÉANCE</a>
                    <a href="{{ route('admin.pending') }}" class="btn-quick-ops position-relative uppercase fw-900">
                        <i class="fas fa-user-check me-2"></i> DEMANDES EN ATTENTE
                        @if(auth()->user()->unreadNotifications->where('data.type', 'registration_request')->count() > 0)
                            <span class="badge-pending">NOUVEAU</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Statistiques Principales -->
    <div class="row g-4 mb-5">
        @php 
            $stats = [
                ['label' => 'TOTAL MEMBRES', 'val' => $totalMembers ?? 0, 'icon' => 'fa-users', 'color' => 'var(--accent)'],
                ['label' => 'MEMBRES ACTIFS', 'val' => $activeMembers ?? 0, 'icon' => 'fa-bolt', 'color' => '#00ff88'],
                ['label' => 'COACHS ÉLITE', 'val' => $totalCoachs ?? 0, 'icon' => 'fa-user-shield', 'color' => '#00d2ff'],
                ['label' => 'CHIFFRE D\'AFFAIRES', 'val' => number_format($totalRevenue ?? 0, 0) . ' MAD', 'icon' => 'fa-vault', 'color' => '#ffed00']
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="col-md-3">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid {{ $stat['color'] }}">
                <span class="stat-label uppercase fw-700 letter-spacing-xs">{{ $stat['label'] }}</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-white m-0 letter-spacing-xs">{{ $stat['val'] }}</h2>
                    <div class="scan-icon-mini" style="background: {{ $stat['color'] }}15; color: {{ $stat['color'] }}">
                        <i class="fas {{ $stat['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <!-- 4. Séances du Jour -->
        <div class="col-lg-8">
            <div class="validation-terminal h-100">
                <div class="terminal-header d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-white border-opacity-5">
                    <span class="tiny-caps text-white fw-900 letter-spacing-sm uppercase"><i class="fas fa-calendar-day text-accent me-2"></i> SÉANCES D'AUJOURD'HUI</span>
                    <span class="badge-status-elite active fw-900 uppercase" style="font-size: 8px;">{{ $todaySeancesCount ?? 0 }} MISSIONS ACTIVES</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark m-0">
                        <thead>
                            <tr class="tiny-caps text-secondary uppercase" style="font-size: 10px;">
                                <th class="ps-4">HORAIRE</th>
                                <th>DISCIPLINE</th>
                                <th>ENTRAÎNEUR</th>
                                <th class="text-end pe-4">STATUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todaySeances ?? [] as $seance)
                            <tr class="terminal-row-hover">
                                <td class="ps-4"><span class="text-accent fw-900">{{ \Carbon\Carbon::parse($seance->heure_seance)->format('H:i') }}</span></td>
                                <td><span class="badge-status-elite active uppercase fw-900">{{ $seance->typeSeance->nom ?? 'N/A' }}</span></td>
                                <td class="text-white fw-900 small uppercase">{{ $seance->coach->user->name ?? 'N/A' }}</td>
                                <td class="text-end pe-4">
                                    @if($seance->statut_coach == 'absent')
                                        <span class="text-danger fw-900 tiny-caps">ABSENT</span>
                                    @else
                                        <div class="status-light-green mx-auto"></div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 tiny-caps opacity-30 fw-700">AUCUNE SÉANCE AUJOURD'HUI.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 5. Alertes Expirations -->
        <div class="col-lg-4">
            <div class="validation-terminal border-danger-dim">
                <div class="terminal-header px-4 py-3 bg-danger-dim border-bottom border-danger border-opacity-10">
                    <span class="tiny-caps text-danger fw-900 uppercase letter-spacing-xs"><i class="fas fa-exclamation-triangle me-2"></i> EXPIRATIONS IMMINENTES (7J)</span>
                </div>
                <div class="p-3">
                    @forelse($expirations ?? [] as $expire)
                    <div class="alert-item-tactical mb-2 p-3" style="background: rgba(255,255,255,0.02); border-radius: 8px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-white small fw-900 uppercase">{{ $expire->user->name ?? 'CLIENT' }}</div>
                            <a href="{{ route('admin.abonnements.edit', $expire->id) }}" class="btn-renew-action fw-900 uppercase">RENOUVELER</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 tiny-caps opacity-30 fw-700">R.A.S — AUCUNE EXPIRATION</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; --danger-dim: rgba(255, 62, 62, 0.05); }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    a { text-decoration: none !important; }
    .fw-900 { font-weight: 900; } .uppercase { text-transform: uppercase; } .letter-spacing-lg { letter-spacing: 4px; } .letter-spacing-sm { letter-spacing: 2px; } .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; }
    .status-label-live { background: rgba(0, 255, 136, 0.1); color: #00ff88; font-size: 9px; padding: 4px 12px; border-radius: 50px; }
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 15px; }
    .btn-quick-ops { background: #000; border: 1px solid rgba(255,255,255,0.05); color: #fff; padding: 12px 20px; border-radius: 8px; font-size: 11px; transition: 0.3s; }
    .btn-quick-ops:hover { background: var(--accent); color: #000; transform: translateY(-2px); }
    .validation-terminal { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.03); border-radius: 20px; }
    
    .status-indicator-pulse { width: 12px; height: 12px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    .status-light-green { width: 10px; height: 10px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; }
    .btn-renew-action { background: #ff3e3e; color: #fff; border: none; font-size: 9px; padding: 6px 12px; border-radius: 4px; }
    .badge-pending { position: absolute; top: -5px; right: -5px; background: #ff3e3e; color: #fff; font-size: 8px; padding: 2px 6px; border-radius: 4px; }
    .table-dark { background: transparent; }
    .table-dark td, .table-dark th { border-color: rgba(255,255,255,0.04); vertical-align: middle; font-family: 'Oswald', sans-serif; }
    .terminal-row-hover:hover { background: rgba(255,237,0,0.03); }
    @keyframes pGlow { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
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