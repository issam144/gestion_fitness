@extends('layouts.app')

@section('page_title', 'UNITÉS DE COACHING')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Scanner Header Stats (نفس ستايل Séances) -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card">
                <div class="stat-label uppercase letter-spacing-xs">Unités de Coaching</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="fw-900 m-0 text-white counter letter-spacing-xs">{{ $coachs->total() }}</h2>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-user-shield"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scanner-info-bar">
                <div class="d-flex align-items-center justify-content-between h-100 px-4">
                    <div class="d-flex align-items-center">
                        <div class="status-light-green me-2"></div>
                        <span class="text-secondary tiny-caps uppercase">Statut : <span class="text-white">Unités Opérationnelles Actives</span></span>
                    </div>
                    <a href="{{ route('admin.coachs.create') }}" class="elite-btn-add uppercase fw-900 letter-spacing-xs">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span>DÉPLOYER UN NOUVEAU COACH</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Terminal Search & Filter Tabs -->
    <div class="row mb-4 g-3">
        <div class="col-lg-6">
            <form action="{{ route('admin.coachs.index') }}" method="GET">
                <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
                <div class="neo-search-bar">
                    <i class="fas fa-fingerprint"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="uppercase fw-700" placeholder="SCANNER PAR NOM OU IDENTIFIANT...">
                    <button type="submit" class="btn-scan-action fw-900 uppercase">SCANNER</button>
                </div>
            </form>
        </div>

        <div class="col-lg-6">
            <div class="neo-filter-tabs h-100 uppercase fw-900 letter-spacing-xs">
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" class="{{ request('filter') == 'all' || !request('filter') ? 'active' : '' }}">TOUS</a>
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'actif']) }}" class="{{ request('filter') == 'actif' ? 'active' : '' }}">ACTIFS</a>
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'inactif']) }}" class="{{ request('filter') == 'inactif' ? 'active' : '' }}">INACTIFS</a>
            </div>
        </div>
    </div>

    <!-- 3. Validation Terminal (Tableau des Coachs) -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr;">
            <div>Identité du Coach</div>
            <div class="text-center">Spécialisation</div>
            <div class="text-center">Statut Opérationnel</div>
            <div class="text-end pe-5">Actions</div>
        </div>

        @forelse($coachs as $coach)
        <div class="terminal-row" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr;">
            
            <!-- Profil with Hexagon -->
            <div class="col-applicant">
                <div class="user-avatar-hex">
                    @if($coach->image)
                        <div class="hex-image-wrap"><img src="{{ asset('storage/'.$coach->image) }}" class="hex-img"></div>
                    @else
                        <span class="hex-text fw-900">{{ strtoupper(substr($coach->user->name, 0, 1)) }}</span>
                    @endif
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-4 text-start">
                    <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ $coach->user->name }}</h6>
                    <span class="text-secondary tiny-caps uppercase" style="opacity: 0.5;">SIG-{{ 2000 + $coach->id }}</span>
                </div>
            </div>

            <!-- Specialization -->
            <div class="text-center">
                <span class="badge-status-elite active uppercase fw-900">
                    <i class="fas fa-bolt me-1"></i> {{ $coach->typeSeance->nom ?? $coach->specialite }}
                </span>
            </div>

            <!-- Operational Status Bar -->
            <div class="px-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="tiny-caps uppercase fw-900 {{ $coach->statut == 'actif' ? 'text-success' : 'text-danger' }}">
                        {{ $coach->statut == 'actif' ? 'En Ligne' : 'Hors Ligne' }}
                    </span>
                    <span class="tiny-caps text-secondary fw-900">{{ $coach->telephone }}</span>
                </div>
                <div class="terminal-progress">
                    <div class="bar" style="width: {{ $coach->statut == 'actif' ? '100%' : '15%' }}; background: {{ $coach->statut == 'actif' ? 'var(--accent)' : '#ff3e3e' }}; box-shadow: 0 0 10px {{ $coach->statut == 'actif' ? 'var(--accent)' : '#ff3e3e' }}66;"></div>
                </div>
            </div>

            <!-- Operations -->
            <div class="text-end pe-4">
                <div class="d-flex justify-content-end gap-2">
                    {{-- الأيقونة الجديدة اللي طلبتي زادت هنا ✅ --}}
                    <a href="{{ route('admin.coach.reports') }}?search={{ $coach->user->name }}" class="btn-op btn-op-approve" style="color: #00d2ff;" title="Rapport d'activité">
                        <i class="fas fa-chart-line"></i>
                    </a>

                    <a href="{{ route('admin.coachs.edit', $coach->id) }}" class="btn-op btn-op-approve" title="Modifier le profil">
                        <i class="fas fa-sliders-h"></i>
                    </a>

                    <form action="{{ route('admin.coachs.destroy', $coach->id) }}" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-op btn-op-reject" onclick="return confirm('DÉSACTIVER CETTE UNITÉ : Supprimer ce coach ?')" title="Supprimer">
                            <i class="fas fa-user-slash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUNE UNITÉ DE COACHING DÉPLOYÉE</h5>
        </div>
        @endforelse
    </div>

    <!-- 4. Pagination -->
    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $coachs->links() }}
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    
    body { background: #050505; font-family: 'Oswald', sans-serif; }
    
    a { text-decoration: none !important; }

    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Header & Stats */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); height: 100%; border-radius: 15px; padding: 15px 0; }
    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; animation: blink 1.5s infinite; }
    .stat-label { color: rgba(255,255,255,0.3); margin-bottom: 10px; }
    .scan-icon-mini { width: 40px; height: 40px; border-radius: 10px; background: rgba(255, 237, 0, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    /* Search & Filters */
    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 5px 5px 5px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 15px; font-weight: 700; }
    .neo-search-bar i { color: var(--accent); }
    .btn-scan-action { background: var(--accent); color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; }

    .neo-filter-tabs { background: var(--panel-bg); border-radius: 12px; padding: 5px; display: flex; border: 1px solid rgba(255,255,255,0.05); }
    .neo-filter-tabs a { flex: 1; text-align: center; color: rgba(255,255,255,0.4); text-decoration: none; font-size: 11px; padding: 12px; border-radius: 8px; transition: 0.3s; }
    .neo-filter-tabs a.active { background: var(--accent); color: #000; }

    /* Hexagon Avatar */
    .user-avatar-hex { position: relative; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; width: 100%; height: 100%; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-text { color: white; z-index: 2; font-size: 1.3rem; }
    .hex-image-wrap { position: absolute; width: 85%; height: 85%; clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%); z-index: 1; overflow: hidden; }
    .hex-img { width: 100%; height: 100%; object-fit: cover; }

    /* Terminal Table */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.01); }
    .col-applicant { display: flex; align-items: center; }

    .badge-status-elite { padding: 4px 12px; border-radius: 5px; font-size: 10px; border-left: 3px solid; background: rgba(0, 210, 255, 0.1); color: #00d2ff; border-color: #00d2ff; }
    .terminal-progress { height: 4px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; }
    .terminal-progress .bar { height: 100%; transition: 1s; }

    /* Action Buttons */
    .btn-op { width: 38px; height: 38px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; transition: 0.3s; cursor: pointer; }
    .btn-op-approve { background: rgba(255, 255, 255, 0.05); color: #fff; }
    .btn-op-approve:hover { background: #fff; color: #000; }
    .btn-op-reject { background: rgba(255, 62, 62, 0.1); color: #ff3e3e; }
    .btn-op-reject:hover { background: #ff3e3e; color: #fff; }

    .elite-btn-add { background: var(--accent); color: #000; text-decoration: none; padding: 10px 20px; border-radius: 8px; transition: 0.3s; display: inline-flex; align-items: center; border: none; cursor: pointer; }
    .elite-btn-add:hover { background: #fff; transform: translateY(-2px); }

    /* Custom Pagination */
    .custom-pagination .pagination { background: var(--panel-bg); padding: 10px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); }
    .custom-pagination .page-link { background: transparent; border: none; color: rgba(255,255,255,0.5); font-weight: 800; font-size: 11px; margin: 0 5px; border-radius: 8px; transition: 0.3s; }
    .custom-pagination .page-item.active .page-link { background: var(--accent); color: #000; }

    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
</style>
@endsection