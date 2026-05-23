@extends('layouts.app')

@section('page_title', $mode == 'presence' ? 'CENTRE DE POINTAGE' : 'HISTORIQUE DES MISSIONS')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Operational HUD Header -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3" style="background: {{ $mode == 'presence' ? 'var(--accent)' : '#00d2ff' }};"></div>
                <h6 class="text-{{ $mode == 'presence' ? 'accent' : 'info' }} tiny-caps m-0 letter-spacing-sm uppercase fw-700">
                    {{ $mode == 'presence' ? 'HUB OPÉRATIONNEL : POINTAGE EN DIRECT' : 'ARCHIVES : HISTORIQUE DES UNITÉS' }}
                </h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase letter-spacing-lg">
                {{ $mode == 'presence' ? 'HUB' : 'MES' }} <span style="color: {{ $mode == 'presence' ? 'var(--accent)' : '#00d2ff' }};">{{ $mode == 'presence' ? 'PRÉSENCES' : 'SÉANCES' }}</span>
            </h1>
        </div>
        <div class="col-md-5 text-md-end">
            <div class="scanner-info-bar py-3 px-4 shadow-neon">
                <span class="text-secondary tiny-caps uppercase">STATUT TIMELINE : <span class="text-white fw-900">SYNCHRONISÉ AVEC LE QG</span></span>
            </div>
        </div>
    </div>

    <!-- 2. Metrics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card" style="border-left: 4px solid {{ $mode == 'presence' ? 'var(--accent)' : '#00d2ff' }}">
                <span class="stat-label uppercase fw-700">{{ $mode == 'presence' ? 'Missions du Jour' : 'Total des Missions' }}</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-white m-0 letter-spacing-xs">{{ $mode == 'presence' ? count($seances) : $seances->total() }}</h2>
                    <div class="scan-icon-mini" style="background: rgba(255,255,255,0.05);">
                        <i class="fas {{ $mode == 'presence' ? 'fa-bolt text-accent' : 'fa-database text-info' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. The Validation Terminal (Tableau) -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2fr 1.5fr 1.5fr 1fr;">
            <div class="ps-4">Timeline (Date & Heure)</div>
            <div class="text-center">Unité de Séance</div>
            <div class="text-center">Statut Mission</div>
            <div class="text-end pe-5">Déploiement</div>
        </div>

        @forelse($seances as $s)
        <div class="terminal-row" style="grid-template-columns: 2fr 1.5fr 1.5fr 1fr;">
            
            <!-- Date & Time -->
            <div class="ps-4">
                <div class="d-flex align-items-center">
                    <div class="user-avatar-hex me-3" style="width: 35px; height: 35px;">
                        <span class="hex-text" style="font-size: 10px;"><i class="fas fa-calendar-alt"></i></span>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <div>
                        <div class="text-white fw-900 small uppercase">{{ \Carbon\Carbon::parse($s->date_seance)->format('d M Y') }}</div>
                        <div class="text-accent fw-700" style="font-size: 9px;">HORAIRE : {{ \Carbon\Carbon::parse($s->heure_seance)->format('H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Discipline -->
            <div class="text-center">
                <span class="badge-status-elite active uppercase fw-900" style="font-size: 9px;">
                    <i class="fas fa-bolt me-1"></i> {{ $s->typeSeance->nom ?? 'SÉANCE' }}
                </span>
            </div>

            <!-- Status -->
            <div class="text-center">
                @if($s->statut_coach == 'present')
                    <span class="tiny-caps text-success fw-900 uppercase"><i class="fas fa-check-circle me-1"></i> COMPLÉTÉE</span>
                @elseif($s->statut_coach == 'absent')
                    <span class="tiny-caps text-danger fw-900 uppercase"><i class="fas fa-times-circle me-1"></i> ANNULÉE</span>
                @else
                    <span class="tiny-caps text-secondary fw-700 uppercase"><i class="fas fa-clock me-1"></i> EN ATTENTE</span>
                @endif
            </div>

            <!-- Actions -->
            <div class="text-end pe-4">
                @if($mode == 'presence')
                    {{-- زر النداء في حالة الـ Hub --}}
                    <a href="{{ route('coach.seance.members', $s->id) }}" class="btn-op-approve py-1 px-3 fw-900 uppercase" style="font-size: 9px; text-decoration: none;">
                        <i class="fas fa-id-badge me-1"></i> APPEL
                    </a>
                @else
                    {{-- زر التفاصيل في حالة الأرشيف --}}
                    <a href="{{ route('coach.seance.members', $s->id) }}" class="btn-op-blue py-1 px-3 fw-900 uppercase" style="font-size: 9px; text-decoration: none;">
                        <i class="fas fa-eye me-1"></i> VOIR
                    </a>
                @endif
            </div>

        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUNE MISSION DÉTECTÉE</h5>
        </div>
        @endforelse
    </div>

    <!-- 4. Tactical Pagination -->
    @if($mode == 'archive')
    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $seances->links() }}
    </div>
    @endif
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    
    .fw-900 { font-weight: 900; } .uppercase { text-transform: uppercase; } .letter-spacing-lg { letter-spacing: 4px; } .letter-spacing-sm { letter-spacing: 2px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* HUD Elements */
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; }
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 15px; }
    .scan-icon-mini { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    
    /* Terminal List */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); font-size: 10px; }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); }

    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .hex-text { color: white; z-index: 2; }

    /* Buttons */
    .btn-op-approve { background: var(--accent); color: #000; border: none; border-radius: 6px; transition: 0.3s; }
    .btn-op-approve:hover { background: #fff; transform: translateY(-2px); }
    .btn-op-blue { background: rgba(0, 210, 255, 0.1); color: #00d2ff; border: 1px solid #00d2ff33; border-radius: 6px; }

    .badge-status-elite { padding: 4px 12px; border-radius: 4px; font-size: 10px; background: rgba(0, 210, 255, 0.05); color: #00d2ff; border: 1px solid rgba(0, 210, 255, 0.1); }
    
    /* Tactical Pagination Style */
    .custom-pagination .pagination { gap: 8px; border: none; }
    .custom-pagination .page-item .page-link {
        background-color: var(--panel-bg); border: 1px solid rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.5); padding: 10px 18px; border-radius: 8px; font-weight: 900; transition: 0.3s;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: var(--accent) !important; border-color: var(--accent) !important; color: #000 !important; box-shadow: 0 0 15px rgba(255, 237, 0, 0.3);
    }

    .status-indicator-pulse { width: 12px; height: 12px; border-radius: 50%; animation: pGlow 2s infinite; }
    @keyframes pGlow { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
</style>
@endsection