@extends('layouts.app')

@section('page_title', 'ARCHIVES DES MISSIONS')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Scanner Header Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card">
                <div class="stat-label uppercase letter-spacing-xs">Missions Accomplies</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="fw-900 m-0 text-white counter letter-spacing-xs">{{ $sessions->total() }}</h2>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scanner-info-bar">
                <div class="d-flex align-items-center justify-content-between h-100 px-4">
                    <div class="d-flex align-items-center">
                        <div class="status-light-green me-2"></div>
                        <span class="text-secondary tiny-caps uppercase">Statut Archive : <span class="text-white">Synchronisé avec le Noyau</span></span>
                    </div>
                    <div class="text-white-50 tiny-caps uppercase letter-spacing-xs d-none d-lg-block">
                        <i class="fas fa-shield-alt me-2 text-accent"></i> Registre Sécurisé
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Integrated Command Bar -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="neo-search-bar">
                <i class="fas fa-search"></i>
                <input type="text" class="uppercase fw-700" placeholder="FILTRER PAR UNITÉ (DISCIPLINE)...">
                <button class="btn-scan-action fw-900 uppercase">SCANNER</button>
            </div>
        </div>
    </div>

    <!-- 3. Validation Terminal - History List -->
    <div class="validation-terminal shadow-2xl">
        <!-- تم تعديل الـ Grid هنا ليناسب 4 أعمدة -->
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 3fr 2fr 2fr 1.5fr;">
            <div class="ps-5">Chronologie Mission (Date)</div>
            <div>Unité / Discipline</div>
            <div class="text-center">Temps Opérationnel</div>
            <div class="text-center pe-5">Statut Pointage</div>
        </div>

        @forelse($sessions as $session)
        <!-- تم تعديل الـ Grid هنا ليناسب 4 أعمدة -->
        <div class="terminal-row" style="grid-template-columns: 3fr 2fr 2fr 1.5fr;">
            
            <!-- Mission Date with Hexagon -->
            <div class="col-applicant">
                <div class="user-avatar-hex">
                    <span class="hex-text fw-900" style="font-size: 14px;"><i class="fas fa-calendar-alt"></i></span>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-4 text-start">
                    <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ \Carbon\Carbon::parse($session->date_seance)->translatedFormat('d F Y') }}</h6>
                    <span class="text-secondary tiny-caps uppercase" style="opacity: 0.5;">Signal Enregistré</span>
                </div>
            </div>

            <!-- Discipline Unit -->
            <div class="d-flex align-items-center">
                <span class="badge-status-elite active uppercase fw-900">
                    <i class="fas fa-bolt me-1"></i> {{ $session->type_seance->nom ?? 'MISSION' }}
                </span>
            </div>

            <!-- Time / Progress Bar -->
            <div class="px-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="tiny-caps text-accent fw-900">{{ $session->heure_seance }}</span>
                    <span class="tiny-caps text-secondary">Terminé</span>
                </div>
                <div class="terminal-progress">
                    <div class="bar" style="width: 100%; background: var(--accent); box-shadow: 0 0 10px rgba(255,237,0,0.3);"></div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="text-center pe-5">
                <span class="badge-status-elite active uppercase fw-900" style="border-left: 2px solid #00ff88;">Validé</span>
            </div>

        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing-xs">Aucune signature de mission détectée dans l'historique</h5>
        </div>
        @endforelse
    </div>

    <!-- 4. Pagination -->
    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $sessions->links() }}
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background: #050505; font-family: 'Oswald', sans-serif; }
    
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Header & Stats */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); height: 100%; border-radius: 15px; padding: 15px 0; }
    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; animation: blink 1.5s infinite; }
    .scan-icon-mini { width: 40px; height: 40px; border-radius: 10px; background: rgba(255, 237, 0, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    /* Search Bar */
    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 5px 5px 5px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 15px; font-weight: 700; }
    .neo-search-bar i { color: var(--accent); }
    .btn-scan-action { background: var(--accent); color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; }

    /* Hexagon Avatar */
    .user-avatar-hex { position: relative; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; width: 100%; height: 100%; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-text { color: white; font-weight: 900; z-index: 2; }

    /* Terminal UI */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.01); }
    .col-applicant { display: flex; align-items: center; }

    /* Badges & Progress */
    .badge-status-elite { padding: 4px 12px; border-radius: 5px; font-size: 9px; border-left: 3px solid #00ff88; background: rgba(0, 255, 136, 0.1); color: #00ff88; }
    .terminal-progress { height: 4px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; }
    .terminal-progress .bar { height: 100%; }

    /* Pagination */
    .custom-pagination .pagination { background: var(--panel-bg); padding: 10px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); }
    .custom-pagination .page-link { background: transparent; border: none; color: rgba(255,255,255,0.5); font-weight: 800; font-size: 11px; margin: 0 5px; border-radius: 8px; transition: 0.3s; }
    .custom-pagination .page-item.active .page-link { background: var(--accent); color: #000; }

    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
</style>
@endsection