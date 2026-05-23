@extends('layouts.app')

@section('page_title', 'SÉLECTION DES UNITÉS D\'ÉLITE')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Operational HUD Header -->
    <div class="row mb-5 align-items-end">
        <div class="col-12">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-xs uppercase">Déploiement : Sélectionnez votre Discipline Tactique</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase display-6">SÉLECTION <span style="color: var(--accent);">D'UNITÉS</span></h1>
        </div>
    </div>

    <!-- 2. Integrated Command Bar (Info Message) -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="validation-terminal p-3 border-accent-dim text-center">
                <span class="tiny-caps text-white-50 uppercase fw-900"><i class="fas fa-info-circle text-accent me-2"></i> Identifiez votre unité pour accéder aux protocoles d'entraînement</span>
            </div>
        </div>
    </div>

    <!-- 3. Grid of Tactical Units (Sports) -->
    <div class="row g-4">
        @foreach($sports as $sport)
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('client.sport.coachs', $sport->id) }}" class="text-decoration-none">
                <div class="validation-terminal p-5 text-center h-100 border-accent-hover tactical-unit-card">
                    
                    <!-- Hexagon Icon Wrapper -->
                    <div class="user-avatar-hex mx-auto mb-4" style="width: 100px; height: 100px;">
                        <!-- Hna dert lik l-icon dyal l-base de données -->
                        <i class="fas {{ $sport->icon ?? 'fa-crosshairs' }} text-accent" style="z-index: 2; font-size: 35px;"></i>
                        <svg class="hex-svg" viewBox="0 0 100 100">
                            <polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" />
                        </svg>
                        <div class="scan-line-mini"></div>
                    </div>

                    <!-- Unit Details -->
                    <h3 class="text-white fw-900 uppercase letter-spacing-xs mb-2">{{ $sport->nom }}</h3>
                    
                    <div class="tactical-divider mx-auto mb-3"></div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <span class="badge-status-elite active uppercase fw-900">
                            <i class="fas fa-user-shield me-1"></i> {{ $sport->coachs->count() }} Commandants
                        </span>
                    </div>

                    <p class="text-secondary tiny-caps mb-0 letter-spacing-xs uppercase fw-900">
                        Accéder au Secteur <i class="fas fa-chevron-right ms-2 text-accent"></i>
                    </p>
                </div>
            </a>
        </div>
        @endforeach
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

    /* Tactical Cards */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; border: 1px solid rgba(255,255,255,0.03); transition: 0.4s; }
    .border-accent-hover:hover { border-color: var(--accent); transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.5); }
    
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 1; }
    
    .scan-line-mini { position: absolute; width: 80%; height: 2px; background: var(--accent); top: 10%; z-index: 3; box-shadow: 0 0 10px var(--accent); animation: scanLine 3s infinite linear; opacity: 0.3; }
    @keyframes scanLine { 0% { top: 10%; } 100% { top: 90%; } }

    .tactical-divider { width: 40px; height: 3px; background: var(--accent); border-radius: 10px; }
    .badge-status-elite { padding: 6px 15px; border-radius: 8px; font-size: 10px; background: rgba(255, 237, 0, 0.05); color: var(--accent); border: 1px solid rgba(255, 237, 0, 0.1); }

    .status-indicator-pulse { width: 10px; height: 10px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    @keyframes pGlow { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.3); } }
    
    .border-accent-dim { border-color: rgba(255, 237, 0, 0.1) !important; }
</style>
@endsection