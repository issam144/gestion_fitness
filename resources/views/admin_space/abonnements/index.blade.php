@extends('layouts.app')

@section('page_title', 'CONTRÔLE DES ABONNEMENTS')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Scanner Header Stats (Global Statistics) -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card">
                <div class="stat-label uppercase letter-spacing-xs fw-700">Revenu Total Projeté</div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h2 class="fw-900 m-0 text-white counter letter-spacing-xs">{{ number_format($totalRevenue, 0) }} <small class="fs-6">MAD</small></h2>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-vault"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="scanner-stat-card border-success-custom">
                <div class="stat-label uppercase letter-spacing-xs fw-700">Permis d'Accès Actifs</div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <h2 class="fw-900 m-0 text-success counter letter-spacing-xs">{{ $activeAbonnementsCount }}</h2>
                    <div class="scan-icon-mini-green pulse-green">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="scanner-info-bar">
                <div class="d-flex align-items-center justify-content-between h-100 px-4">
                    <div class="d-flex align-items-center">
                        <div class="status-light-green me-2"></div>
                        <span class="text-secondary tiny-caps uppercase">Statut : <span class="text-white">Facturation Live</span></span>
                    </div>
                    <a href="{{ route('admin.abonnements.create') }}" class="elite-btn-add uppercase fw-900 letter-spacing-xs">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span>NOUVEAU CONTRAT</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Security Command Bar (Search & Filters) -->
    <div class="row mb-4 g-3">
        <div class="col-lg-6">
            <form action="{{ route('admin.abonnements.index') }}" method="GET">
                <div class="neo-search-bar">
                    <i class="fas fa-fingerprint"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="uppercase fw-700" placeholder="SCANNER PAR NOM OU PACK...">
                    <button type="submit" class="btn-scan-action fw-900 uppercase">SCANNER</button>
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            <div class="neo-filter-tabs h-100 uppercase fw-900 letter-spacing-xs">
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" class="{{ request('filter') == 'all' || !request('filter') ? 'active' : '' }}">TOUS</a>
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'active']) }}" class="{{ request('filter') == 'active' ? 'active' : '' }}">ACTIFS</a>
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'expired']) }}" class="{{ request('filter') == 'expired' ? 'active' : '' }}">EXPIRÉS</a>
            </div>
        </div>
    </div>

    <!-- 3. Validation Terminal - Subscriptions List -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr 1fr;">
            <div>Identité de l'Adhérent</div>
            <div class="text-center">Niveau du Pack</div>
            <div class="text-center">Progression Expiration</div>
            <div class="text-center">Statut Accès</div>
            <div class="text-end pe-5">Opérations</div>
        </div>

        @forelse($abonnements as $abonnement)
        <div class="terminal-row" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr 1fr;">
            <!-- Adherent -->
            <div class="col-applicant d-flex align-items-center">
                <div class="user-avatar-hex">
                    <span class="hex-text fw-900">{{ strtoupper(substr($abonnement->user->name ?? '?', 0, 1)) }}</span>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-4 text-start">
                    <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ $abonnement->user->name ?? 'Inconnu' }}</h6>
                    <span class="text-secondary tiny-caps uppercase" style="opacity: 0.5; font-size: 8px;">{{ $abonnement->user->email ?? '' }}</span>
                </div>
            </div>

            <!-- Pack -->
            <div class="text-center">
                <span class="badge-status-elite active uppercase fw-900">{{ $abonnement->typeAbonnement->nom ?? 'STANDARD' }}</span>
                <div class="text-white-50 mt-1 fw-900" style="font-size: 8px;">{{ number_format($abonnement->montant_paye, 0) }} MAD</div>
            </div>

            <!-- Expiration -->
            <div class="px-4">
                @php
                    $end = \Carbon\Carbon::parse($abonnement->date_fin);
                    $isExpired = $end->isPast();
                    $days = (int)now()->diffInDays($end, false);
                    $percent = $isExpired ? 0 : min(100, max(0, ($days / 30) * 100));
                @endphp
                <div class="d-flex justify-content-between mb-1">
                    <span class="tiny-caps uppercase fw-900 {{ $isExpired ? 'text-danger' : 'text-accent' }}">
                        {{ $isExpired ? 'EXPIRÉ' : $days . ' JOURS RESTANTS' }}
                    </span>
                </div>
                <div class="terminal-progress">
                    <div class="bar" style="width: {{ $percent }}%; background: {{ $isExpired ? '#ff3e3e' : 'var(--accent)' }}; shadow: 0 0 10px {{ $isExpired ? '#ff3e3e' : 'var(--accent)' }}"></div>
                </div>
                <div class="tiny-caps text-white-50 mt-1 fw-900" style="font-size: 7px;">VALIDE JUSQU'AU : {{ $end->format('d M Y') }}</div>
            </div>

            <!-- Status -->
            <div class="text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="{{ !$isExpired ? 'status-light-green' : 'status-light-red' }} me-2"></div>
                    <span class="tiny-caps fw-900 {{ !$isExpired ? 'text-success' : 'text-danger' }}">{{ !$isExpired ? 'AUTORISÉ' : 'REFUSÉ' }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="text-end pe-4">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.abonnements.edit', $abonnement->id) }}" class="btn-op btn-op-approve" title="Modifier"><i class="fas fa-file-signature"></i></a>
                    <form action="{{ route('admin.abonnements.destroy', $abonnement->id) }}" method="POST" class="m-0 d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-op btn-op-reject" onclick="return confirm('INTERROMPRE LE CONTRAT ?')" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5"><h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUN CONTRAT DÉTECTÉ</h5></div>
        @endforelse
    </div>

    <!-- 4. Tactical Pagination -->
    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $abonnements->appends(request()->query())->links() }}
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

    /* Stats Cards */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    .border-success-custom { border-left-color: #00ff88; }
    
    /* Changement de couleur pour correspondre à l'image (Grey) */
    .stat-label { color: rgba(255, 255, 255, 0.4); }

    .scan-icon-mini { width: 45px; height: 45px; border-radius: 12px; background: rgba(255, 237, 0, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .scan-icon-mini-green { width: 45px; height: 45px; border-radius: 12px; background: rgba(0, 255, 136, 0.15); color: #00ff88; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }

    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); height: 100%; border-radius: 15px; padding: 15px 0; }
    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; }
    .status-light-red { width: 8px; height: 8px; background: #ff3e3e; border-radius: 50%; }

    /* Search & Tabs */
    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 5px 5px 5px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 15px; font-weight: 700; }
    .neo-search-bar i { color: var(--accent); }
    .btn-scan-action { background: var(--accent); color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; }
    .elite-btn-add { background: var(--accent); color: #000; padding: 10px 20px; border-radius: 8px; transition: 0.3s; display: inline-flex; align-items: center; border: none; }

    .neo-filter-tabs { background: var(--panel-bg); border-radius: 12px; padding: 5px; display: flex; border: 1px solid rgba(255,255,255,0.05); }
    .neo-filter-tabs a { flex: 1; text-align: center; color: rgba(255,255,255,0.4); padding: 12px; border-radius: 8px; font-size: 11px; }
    .neo-filter-tabs a.active { background: var(--accent); color: #000; }

    /* Terminal List */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); font-size: 10px; }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); }
    
    .user-avatar-hex { position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .hex-text { color: white; z-index: 2; font-size: 1.2rem; }

    .terminal-progress { height: 4px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; }
    .terminal-progress .bar { height: 100%; transition: 1s; box-shadow: 0 0 10px currentColor; }

    .btn-op { width: 38px; height: 38px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; transition: 0.3s; }
    .btn-op-approve { background: rgba(255, 255, 255, 0.05); color: #fff; }
    .btn-op-reject { background: rgba(255, 62, 62, 0.1); color: #ff3e3e; }

    /* Pagination Styling */
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

    /* Animations */
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
    .pulse-green { animation: pGreen 2s infinite; }
    @keyframes pGreen { 0% { box-shadow: 0 0 0 0 rgba(0,255,136,0.4); } 70% { box-shadow: 0 0 0 10px rgba(0,255,136,0); } 100% { box-shadow: 0 0 0 0 rgba(0,255,136,0); } }
</style>
@endsection