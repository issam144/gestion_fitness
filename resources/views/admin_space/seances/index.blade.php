@extends('layouts.app')

@section('page_title', 'HUB DES SÉANCES')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Scanner Header Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card">
                <div class="stat-label uppercase letter-spacing-xs">Missions Programmées</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="fw-900 m-0 text-white counter letter-spacing-xs">{{ $seances->count() }}</h2>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-calendar-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scanner-info-bar">
                <div class="d-flex align-items-center justify-content-between h-100 px-4">
                    <div class="d-flex align-items-center">
                        <div class="status-light-green me-2"></div>
                        <span class="text-secondary tiny-caps uppercase">Statut Opérationnel : <span class="text-white">Timeline Synchronisée</span></span>
                    </div>
                    <!-- الزر هنا بدون سطر -->
                    <a href="{{ route('admin.seances.create') }}" class="elite-btn-add uppercase fw-900 letter-spacing-xs">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span>PROGRAMMER UNE SÉANCE</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Terminal Search Bar -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('admin.seances.index') }}" method="GET">
                <div class="neo-search-bar">
                    <i class="fas fa-search-location"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="uppercase fw-700" placeholder="RECHERCHER PAR DATE, COACH OU DISCIPLINE...">
                    <button type="submit" class="btn-scan-action fw-900 uppercase">SCANNER</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Validation Terminal - Sessions List -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2fr 2fr 1.5fr 1fr;">
            <div>Timeline (Date & Heure)</div>
            <div>Commandant Assigné (Coach)</div>
            <div class="text-center">Type d'Opération</div>
            <div class="text-end">Opérations</div>
        </div>

        @forelse($seances as $seance)
        <div class="terminal-row" style="grid-template-columns: 2fr 2fr 1.5fr 1fr;">
            
            <!-- Timeline with Hexagon Icon -->
            <div class="col-applicant">
                <div class="user-avatar-hex">
                    <span class="hex-text"><i class="fas fa-clock" style="font-size: 1rem;"></i></span>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-4 text-start">
                    <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">
                        {{ \Carbon\Carbon::parse($seance->date_seance)->translatedFormat('d M Y') }}
                    </h6>
                    <span class="text-warning fw-900 tiny-caps uppercase" style="text-shadow: 0 0 10px rgba(255,237,0,0.3);">
                        DÉBUT : {{ $seance->heure_seance }}
                    </span>
                </div>
            </div>

            <!-- Coach Info -->
            <div class="d-flex align-items-center">
                <div class="coach-mini-hex">
                    <i class="fas fa-user-shield text-info"></i>
                </div>
                <div class="ms-3">
                    <div class="fw-900 text-white small uppercase letter-spacing-xs">{{ $seance->coach->user->name ?? 'NON ASSIGNÉ' }}</div>
                    <div class="text-secondary tiny-caps uppercase" style="font-size: 8px;">{{ $seance->coach->typeSeance->nom ?? 'GÉNÉRALISTE' }}</div>
                </div>
            </div>

            <!-- Session Type Badge -->
            <div class="text-center">
                <span class="badge-status-elite active uppercase fw-900">
                    <i class="fas fa-bolt me-1"></i> {{ strtoupper($seance->type_seance->nom ?? 'Standard') }}
                </span>
            </div>

            <!-- Operations -->
            <div class="text-end">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.seances.edit', $seance->id) }}" class="btn-op btn-op-approve" title="Modifier la Séance">
                        <i class="fas fa-sliders-h"></i>
                    </a>
                    <form action="{{ route('admin.seances.destroy', $seance->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-op btn-op-reject" onclick="return confirm('INTERROMPRE LA MISSION : Supprimer cette séance ?')" title="Supprimer la Séance">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUNE SÉANCE DANS LA TIMELINE</h5>
        </div>
        @endforelse
    </div>

    <!-- 4. Pagination -->
    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $seances->links() }}
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    
    body { background: #050505; font-family: 'Oswald', sans-serif; }
    
    /* حيدت أي سطر تحت الروابط في الصفحة كاملة */
    a { text-decoration: none !important; }

    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Scanner UI Components */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); height: 100%; border-radius: 15px; padding: 15px 0; }
    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; animation: blink 1.5s infinite; }

    .stat-label { color: rgba(255,255,255,0.3); margin-bottom: 10px; }
    .scan-icon-mini { width: 40px; height: 40px; border-radius: 10px; background: rgba(255, 237, 0, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    /* Hexagon Avatar for Timeline */
    .user-avatar-hex { position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-text { color: white; font-weight: 900; z-index: 2; }

    /* Coach Mini Hex */
    .coach-mini-hex { width: 35px; height: 35px; background: rgba(0, 210, 255, 0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(0, 210, 255, 0.1); }

    /* Terminal Interface */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.01); }

    /* Controls */
    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 5px 5px 5px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 15px; font-weight: 700; }
    .neo-search-bar i { color: var(--accent); }
    .btn-scan-action { background: var(--accent); color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; text-decoration: none !important; }

    .elite-btn-add { background: var(--accent); color: #000; padding: 10px 20px; border-radius: 8px; transition: 0.3s; display: inline-flex; align-items: center; border: none; cursor: pointer; text-decoration: none !important; }
    .elite-btn-add:hover { background: #fff; transform: translateY(-2px); color: #000; text-decoration: none !important; }

    .badge-status-elite { padding: 4px 12px; border-radius: 5px; font-size: 10px; border-left: 3px solid; background: rgba(0, 210, 255, 0.1); color: #00d2ff; border-color: #00d2ff; }

    /* Action Buttons */
    .btn-op { width: 38px; height: 38px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; transition: 0.3s; text-decoration: none !important; }
    .btn-op-approve { background: rgba(255, 255, 255, 0.05); color: #fff; }
    .btn-op-approve:hover { background: #fff; color: #000; }
    .btn-op-reject { background: rgba(255, 62, 62, 0.1); color: #ff3e3e; }
    .btn-op-reject:hover { background: #ff3e3e; color: #fff; }

    /* Custom Pagination */
    .custom-pagination .pagination { background: var(--panel-bg); padding: 10px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); }
    .custom-pagination .page-link { background: transparent; border: none; color: rgba(255,255,255,0.5); font-weight: 800; font-size: 11px; margin: 0 5px; border-radius: 8px; text-decoration: none !important; }
    .custom-pagination .page-item.active .page-link { background: var(--accent); color: #000; }

    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
</style>
@endsection