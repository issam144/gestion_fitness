@extends('layouts.app')

@section('page_title', 'CONTRÔLE DES DISCIPLINES')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Scanner Header Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card">
                <div class="stat-label uppercase letter-spacing-xs">Unités Actives</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="fw-900 m-0 text-white counter letter-spacing-xs">{{ $types->count() }}</h2>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-layer-group"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scanner-info-bar">
                <div class="d-flex align-items-center justify-content-between h-100 px-4">
                    <div class="d-flex align-items-center">
                        <div class="status-light-green me-2"></div>
                        <span class="text-secondary tiny-caps uppercase">Statut Système : <span class="text-white">Catégories Chargées</span></span>
                    </div>
                    <a href="{{ route('admin.type-seances.create') }}" class="elite-btn-add uppercase fw-900 letter-spacing-xs">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span>DÉPLOYER NOUVELLE DISCIPLINE</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Terminal Search Bar -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('admin.type-seances.index') }}" method="GET">
                <div class="neo-search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="uppercase fw-700" placeholder="SCANNER LA BASE POUR UNE DISCIPLINE...">
                    <button type="submit" class="btn-scan-action fw-900 uppercase">SCANNER</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Validation Terminal - Disciplines List -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 3fr 1.5fr 1fr;">
            <div>Identité de la Discipline</div>
            <div class="text-center">Date d'Initialisation</div>
            <div class="text-end pe-5">Opérations</div>
        </div>

        @forelse($types as $type)
        <div class="terminal-row" style="grid-template-columns: 3fr 1.5fr 1fr;">
            
            <!-- Discipline Identity with Hexagon -->
            <div class="col-applicant">
                <div class="user-avatar-hex">
                    <!-- HNA FIN KAN L-MOCKEL: Daba kandiro l-icon dynamique li f la base -->
                    <span class="hex-text"><i class="fas {{ $type->icon ?? 'fa-shield-alt' }}" style="font-size: 1.1rem;"></i></span>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-4 text-start">
                    <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ $type->nom }}</h6>
                    <span class="text-secondary tiny-caps uppercase" style="opacity: 0.5;">ID_TACTIQUE : #{{ 3000 + $type->id }}</span>
                </div>
            </div>

            <!-- Creation Date -->
            <div class="text-center">
                <div class="fw-900 text-white small uppercase">{{ $type->created_at->format('d M Y') }}</div>
                <span class="text-warning fw-900 uppercase" style="font-size: 9px; letter-spacing: 1px;">SÉQUENCE : {{ $type->created_at->format('H:i') }}</span>
            </div>

            <!-- Operations -->
            <div class="text-end pe-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.type-seances.edit', $type->id) }}" class="btn-op btn-op-approve" title="Modifier l'unité">
                        <i class="fas fa-sliders-h"></i>
                    </a>
                    <form action="{{ route('admin.type-seances.destroy', $type->id) }}" method="POST" class="d-inline m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-op btn-op-reject" onclick="return confirm('ATTENTION : Suppression permanente de cette discipline ?')" title="Supprimer l'unité">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing-xs">AUCUNE DISCIPLINE ARCHIVÉE DANS LE SECTEUR</h5>
        </div>
        @endforelse
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    
    body { background: #050505; font-family: 'Oswald', sans-serif; }

    a { text-decoration: none !important; }

    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Scanner UI Components */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); height: 100%; border-radius: 15px; padding: 15px 0; }
    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; animation: blink 1.5s infinite; }

    .stat-label { color: rgba(255,255,255,0.3); margin-bottom: 10px; }
    .scan-icon-mini { width: 40px; height: 40px; border-radius: 10px; background: rgba(255, 237, 0, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    /* Hexagon Avatar */
    .user-avatar-hex { position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; width: 100%; height: 100%; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-text { color: white; font-weight: 900; z-index: 2; }

    /* Terminal Interface */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.01); }

    .col-applicant { display: flex; align-items: center; }

    /* Controls */
    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 5px 5px 5px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 15px; font-weight: 700; }
    .neo-search-bar i { color: var(--accent); }
    .btn-scan-action { background: var(--accent); color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; }

    .elite-btn-add { background: var(--accent); color: #000; padding: 10px 20px; border-radius: 8px; transition: 0.3s; display: inline-flex; align-items: center; border: none; cursor: pointer; }
    .elite-btn-add:hover { background: #fff; transform: translateY(-2px); }

    /* Action Buttons */
    .btn-op { width: 38px; height: 38px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; transition: 0.3s; }
    .btn-op-approve { background: rgba(255, 255, 255, 0.05); color: #fff; }
    .btn-op-approve:hover { background: #fff; color: #000; }
    .btn-op-reject { background: rgba(255, 62, 62, 0.1); color: #ff3e3e; }
    .btn-op-reject:hover { background: #ff3e3e; color: #fff; }

    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
</style>
@endsection