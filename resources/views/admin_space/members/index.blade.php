@extends('layouts.app')

@section('page_title', 'BASE DE DONNÉES DES MEMBRES')

@section('content')
<div class="container-fluid py-4 px-lg-5 dashboard-main">
    
    <!-- 1. Operational HUD Header (بقي كما هو ✅) -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-sm uppercase fw-700">ACCÈS RÉPERTOIRE : BASE GLOBALE DES ADHÉRENTS</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase letter-spacing-lg">REGISTRE <span style="color: var(--accent);">DES MEMBRES</span></h1>
        </div>
        <div class="col-md-5 text-md-end d-none d-md-block">
            <div class="scanner-info-bar py-3 px-4 shadow-neon">
                <span class="text-secondary tiny-caps me-3 uppercase letter-spacing-xs">HEURE SYSTÈME : <span id="live-clock" class="text-white fw-900 h5 mb-0">--:--:--</span></span>
                <span class="status-label-live uppercase letter-spacing-xs fw-900"><i class="fas fa-database me-1"></i> DONNÉES CHIFFRÉES</span>
            </div>
        </div>
    </div>

    <!-- 2. Search & Filter Terminal (بقي كما هو ✅) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="validation-terminal p-4 border-accent-dim">
                <form action="{{ route('admin.members.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="tiny-caps text-secondary mb-2 d-block letter-spacing-sm uppercase fw-700">SIGNAL DE RECHERCHE</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control-tactical uppercase fw-600" placeholder="NOM OU EMAIL...">
                    </div>
                    <div class="col-md-3">
                        <label class="tiny-caps text-secondary mb-2 d-block letter-spacing-sm uppercase fw-700">FILTRE DE STATUT</label>
                        <select name="filter" class="form-control-tactical uppercase fw-600">
                            <option value="">TOUS LES STATUTS</option>
                            <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>SIGNAUX ACTIFS</option>
                            <option value="expired" {{ request('filter') == 'expired' ? 'selected' : '' }}>SIGNAL PERDU (EXPIRÉ)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn-quick-ops w-100 uppercase fw-900 letter-spacing-xs">
                            <i class="fas fa-search me-2"></i> EXÉCUTER LE SCAN
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.members.create') }}" class="btn-new-member w-100 uppercase fw-900 letter-spacing-xs">
                            <i class="fas fa-plus-circle me-2"></i> NOUVEAU MEMBRE
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Metrics Overview (بقي كما هو ✅) -->
    <div class="row g-4 mb-5">
        @php 
            $stats = [
                ['label' => 'TOTAL MEMBRES', 'val' => $totalMembers, 'icon' => 'fa-users', 'color' => 'var(--accent)'],
                ['label' => 'SIGNAUX ACTIFS', 'val' => $activeMembers, 'icon' => 'fa-bolt', 'color' => '#00ff88'],
                ['label' => 'EXPIRÉS (SIGNAL PERDU)', 'val' => $expiredMembers, 'icon' => 'fa-exclamation-triangle', 'color' => '#ff3e3e']
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="col-md-4">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid {{ $stat['color'] }}">
                <span class="stat-label uppercase letter-spacing-sm fw-700">{{ $stat['label'] }}</span>
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

    <!-- 4. Main Members Terminal (تم تغيير هذا الجزء ليشبه تصميم الاشتراكات 🆕) -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr 1fr;">
            <div>Identité du Membre</div>
            <div class="text-center">Type d'Abonnement</div>
            <div class="text-center">Date d'Expiration</div>
            <div class="text-center">Statut Signal</div>
            <div class="text-end pe-5">Opérations</div>
        </div>

        @forelse($members as $member)
        <div class="terminal-row" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr 1fr;">
            <!-- Member Identity -->
            <div class="col-applicant d-flex align-items-center">
                <div class="user-avatar-hex">
                    <span class="hex-text fw-900">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-4 text-start">
                    <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ $member->name }}</h6>
                    <span class="text-secondary tiny-caps uppercase" style="opacity: 0.5; font-size: 8px;">{{ $member->email }}</span>
                </div>
            </div>

            <!-- Plan Type -->
            <div class="text-center">
                <span class="badge-status-elite active uppercase fw-900">{{ $member->typeAbonnement->nom ?? 'SANS PLAN' }}</span>
                @if($member->phone)
                <div class="text-white-50 mt-1 fw-900" style="font-size: 8px;"><i class="fas fa-phone-alt me-1"></i> {{ $member->phone }}</div>
                @endif
            </div>

            <!-- Expiration Date -->
            <div class="text-center px-4">
                <div class="text-white fw-900 uppercase small">
                    {{ $member->expired_at ? \Carbon\Carbon::parse($member->expired_at)->format('d M Y') : 'N/A' }}
                </div>
                <div class="tiny-caps text-secondary mt-1 fw-700" style="font-size: 7px;">PROTOCOLE D'EXPIRATION</div>
            </div>

            <!-- Access Status -->
            <div class="text-center">
                @php $isActive = $member->expired_at && \Carbon\Carbon::parse($member->expired_at)->isFuture(); @endphp
                <div class="d-flex align-items-center justify-content-center">
                    <div class="{{ $isActive ? 'status-light-green' : 'status-light-red' }} me-2"></div>
                    <span class="tiny-caps fw-900 {{ $isActive ? 'text-success' : 'text-danger' }}">
                        {{ $isActive ? 'SIGNAL ACTIF' : 'SIGNAL PERDU' }}
                    </span>
                </div>
            </div>

            <!-- Operations -->
            <div class="text-end pe-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.members.edit', $member->id) }}" class="btn-op btn-op-approve" title="Modifier"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" class="m-0 d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-op btn-op-reject" onclick="return confirm('SUPPRIMER CE MEMBRE ?')" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5"><h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUN MEMBRE DÉTECTÉ DANS LA BASE</h5></div>
        @endforelse
    </div>

    <!-- 5. Tactical Pagination (مثل صفحة الاشتراكات 🆕) -->
    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $members->appends(request()->query())->links() }}
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background-color: #050505; font-family: 'Oswald', sans-serif; }
    a { text-decoration: none !important; }

    .fw-900 { font-weight: 900; } 
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; } 
    .letter-spacing-xs { letter-spacing: 1px; }

    /* HUD Header Elements */
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; display: inline-flex; align-items: center; }
    .status-label-live { background: rgba(0, 255, 136, 0.1); color: #00ff88; font-size: 8px; padding: 4px 12px; border-radius: 50px; }
    .status-indicator-pulse { width: 10px; height: 10px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    @keyframes pGlow { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

    /* Search Terminal */
    .form-control-tactical { background: #000; border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 10px 15px; border-radius: 8px; font-size: 12px; font-family: 'Oswald', sans-serif; width: 100%; }
    .btn-new-member { background: var(--accent) !important; color: #000 !important; border: none; padding: 10px; border-radius: 8px; font-size: 11px; display: flex; align-items: center; justify-content: center; }
    .btn-quick-ops { background: transparent; border: 1px solid var(--accent); color: var(--accent); padding: 10px; border-radius: 8px; font-size: 11px; cursor: pointer; }

    /* Metrics Cards */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 15px; }
    .scan-icon-mini { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }

    /* --- New Grid Style Terminal (Subscriptions Style) --- */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); font-size: 10px; }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.015); }

    .user-avatar-hex { position: relative; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .hex-text { color: white; z-index: 2; font-size: 1.1rem; }

    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; }
    .status-light-red { width: 8px; height: 8px; background: #ff3e3e; border-radius: 50%; box-shadow: 0 0 10px #ff3e3e; }

    /* Operations Buttons */
    .btn-op { width: 38px; height: 38px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
    .btn-op-approve { background: rgba(255, 255, 255, 0.05); color: #fff; }
    .btn-op-reject { background: rgba(255, 62, 62, 0.1); color: #ff3e3e; }
    .btn-op:hover { transform: scale(1.1); }

    /* --- Tactical Pagination Style --- */
    .custom-pagination .pagination { gap: 8px; border: none; }
    .custom-pagination .page-item .page-link {
        background-color: var(--panel-bg);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.5);
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 900;
        transition: 0.3s;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: var(--accent) !important;
        border-color: var(--accent) !important;
        color: #000 !important;
        box-shadow: 0 0 15px rgba(255, 237, 0, 0.3);
    }
    .custom-pagination .page-item .page-link:hover { border-color: var(--accent); color: var(--accent); }
</style>

<script>
    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').textContent = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0') + ":" + now.getSeconds().toString().padStart(2, '0');
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection