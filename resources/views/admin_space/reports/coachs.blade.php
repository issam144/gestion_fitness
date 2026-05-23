@extends('layouts.app')

@section('page_title', 'COACH INTEL REPORT')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. En-tête (Header) -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3" style="background: #00ff88; box-shadow: 0 0 15px #00ff88;"></div>
                <h6 class="text-success tiny-caps m-0 letter-spacing-sm uppercase fw-700">RAPPORT D'ANALYSE OPÉRATIONNELLE : PERFORMANCES DES UNITÉS</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase letter-spacing-lg">COACH <span style="color: var(--accent);">INTEL REPORT</span></h1>
        </div>
        <div class="col-md-5 text-md-end">
            <a href="{{ route('admin.coachs.index') }}" class="elite-btn-back uppercase fw-900 letter-spacing-sm">
                <i class="fas fa-arrow-left me-2"></i> RETOUR AUX UNITÉS
            </a>
        </div>
    </div>

    <!-- 2. Metrics Overview -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card">
                <span class="stat-label uppercase fw-700">Missions Totales Accomplies</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-white m-0 letter-spacing-xs">{{ $coachs->sum('seances_count') }}</h2>
                    <div class="scan-icon-mini" style="background: rgba(0, 255, 136, 0.1); color: #00ff88">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scanner-info-bar py-3 px-4 shadow-neon">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="d-flex align-items-center">
                        <div class="status-light-green me-2"></div>
                        <span class="text-secondary tiny-caps uppercase">Période d'analyse : <span class="text-white fw-900">MOIS EN COURS</span></span>
                    </div>
                    <span class="status-label-live uppercase letter-spacing-xs fw-900" style="background: rgba(255, 237, 0, 0.1); color: var(--accent); padding: 5px 15px; border-radius: 50px; font-size: 9px;">
                        <i class="fas fa-sync-alt fa-spin me-1"></i> CALCUL EN TEMPS RÉEL
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Validation Terminal (Tableau) -->
    <div class="validation-terminal shadow-2xl">
        <!-- Grid beddelnah l 4 colonnes f blast 5 -->
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2fr 1.5fr 1.5fr 1.5fr;">
            <div>Identité de l'Unité</div>
            <div class="text-center">Spécialisation</div>
            <div class="text-center">Missions Validées</div>
            <div class="text-end pe-5">Estimation Payout</div>
        </div>

        @forelse($coachs as $coach)
        <div class="terminal-row" style="grid-template-columns: 2fr 1.5fr 1.5fr 1.5fr;">
            
            <!-- Identité -->
            <div class="d-flex align-items-center">
                <div class="user-avatar-hex" style="width: 40px; height: 40px;">
                    <span class="hex-text fw-900" style="font-size: 12px;">{{ strtoupper(substr($coach->user->name ?? 'U', 0, 1)) }}</span>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-3">
                    <div class="text-white fw-900 small uppercase">{{ $coach->user->name ?? 'Inconnu' }}</div>
                    <div class="text-secondary" style="font-size: 8px;">ID: #{{ 2000 + $coach->id }}</div>
                </div>
            </div>

            <!-- Discipline -->
            <div class="text-center">
                <span class="badge-status-elite active uppercase fw-900" style="font-size: 9px; color: #00d2ff; border-color: #00d2ff33; background: rgba(0, 210, 255, 0.05);">
                    {{ $coach->typeSeance->nom ?? 'Standard' }}
                </span>
            </div>

            <!-- Missions Count -->
            <div class="text-center">
                <div class="d-inline-flex align-items-center px-3 py-1 rounded" style="background: rgba(0, 255, 136, 0.05); border: 1px solid rgba(0, 255, 136, 0.1);">
                    <span class="text-success fw-900 h5 m-0">{{ $coach->seances_count }}</span>
                    <span class="text-secondary tiny-caps ms-2" style="font-size: 7px;">SÉANCES</span>
                </div>
            </div>

            <!-- Payout Estimation -->
            <div class="text-end pe-5">
                <div class="text-white fw-900 uppercase">
                    {{ number_format($coach->seances_count * 100, 0) }} <small class="text-accent">MAD</small>
                </div>
                <span class="text-secondary" style="font-size: 7px;">ESTIMATION BRUTE</span>
            </div>

        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUNE DONNÉE DÉTECTÉE</h5>
        </div>
        @endforelse
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    a { text-decoration: none !important; }
    .fw-900 { font-weight: 900; } .uppercase { text-transform: uppercase; } .letter-spacing-lg { letter-spacing: 4px; } .letter-spacing-sm { letter-spacing: 2px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }
    
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid #00ff88; padding: 20px; border-radius: 12px; }
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; }
    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; }
    .scan-icon-mini { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); font-size: 10px; }
    .terminal-row { display: grid; align-items: center; padding: 20px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.015); }

    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .hex-text { color: white; z-index: 2; }

    .badge-status-elite { padding: 4px 12px; border-radius: 4px; font-size: 10px; border: 1px solid; }
    .elite-btn-back { color: rgba(255,255,255,0.4); font-size: 11px; text-decoration: none; transition: 0.3s; }
    .elite-btn-back:hover { color: var(--accent); }

    .status-indicator-pulse { width: 12px; height: 12px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    @keyframes pGlow { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
</style>
@endsection