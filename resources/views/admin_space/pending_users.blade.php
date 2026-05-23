@extends('layouts.app')

@section('page_title', 'CONTRÔLE D\'ACCÈS')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Scanner Header Stats (نفس تصميم Séances) -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card">
                <div class="stat-label uppercase letter-spacing-xs">Vérifications en Attente</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="fw-900 m-0 text-white counter letter-spacing-xs">{{ $users->total() }}</h2>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-fingerprint"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scanner-info-bar">
                <div class="d-flex align-items-center justify-content-between h-100 px-4">
                    <div class="d-flex align-items-center">
                        <div class="status-light-green me-2"></div>
                        <span class="text-secondary tiny-caps uppercase">Statut Système : <span class="text-white">Surveillance Active</span></span>
                    </div>
                    <div class="text-white-50 tiny-caps uppercase letter-spacing-xs d-none d-lg-block">
                        <i class="fas fa-shield-alt me-2 text-accent"></i> Protocole Sécurité 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Terminal Search Bar (نفس تصميم Séances) -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('admin.pending') }}" method="GET">
                <div class="neo-search-bar">
                    <i class="fas fa-id-card-alt"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="uppercase fw-700" placeholder="RECHERCHER UN DEMANDEUR PAR NOM...">
                    <button type="submit" class="btn-scan-action fw-900 uppercase">SCANNER</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert bg-accent bg-opacity-10 text-accent border-accent border-opacity-25 rounded-4 text-center mb-4 small fw-900 uppercase letter-spacing-xs">
            <i class="fas fa-check-double me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 3. Validation Terminal (نفس تصميم الجدول في Séances) -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr;">
            <div>Demandeur (Identité)</div>
            <div class="text-center">Date d'Inscription</div>
            <div class="text-center">Niveau de Sécurité (Rôle)</div>
            <div class="text-end pe-5">Décision</div>
        </div>

        @forelse($users as $user)
        <div class="terminal-row" style="grid-template-columns: 2.5fr 1.5fr 2fr 1fr;">
            
            <!-- Applicant with Hexagon Icon -->
            <div class="col-applicant">
                <div class="user-avatar-hex">
                    <span class="hex-text fw-900">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <div class="ms-4 text-start">
                    <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ $user->name }}</h6>
                    <span class="text-secondary tiny-caps uppercase" style="opacity: 0.5; font-size: 8px;">{{ $user->email }}</span>
                </div>
            </div>

            <!-- Date Registration -->
            <div class="text-center">
                <div class="fw-900 text-white small uppercase">{{ $user->created_at->format('d M Y') }}</div>
                <span class="text-warning fw-900 tiny-caps uppercase" style="text-shadow: 0 0 10px rgba(255,237,0,0.3);">
                    HEURE : {{ $user->created_at->format('H:i') }}
                </span>
            </div>

            <!-- Role Selection Module -->
            <div class="px-3">
                <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="d-flex align-items-center gap-2">
                    @csrf
                    <div class="neo-dropdown-wrapper">
                        <select name="role" required class="uppercase fw-900">
                            <option value="client">NIVEAU 01 : ATHLÈTE</option>
                            <option value="coach">NIVEAU 02 : COACH PRO</option>
                            <option value="admin">NIVEAU 03 : ADMIN</option>
                        </select>
                        <i class="fas fa-caret-down"></i>
                    </div>
            </div>

            <!-- Actions -->
            <div class="text-end pe-4">
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn-op btn-op-approve" title="Accorder l'Accès">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                
                <form action="{{ route('admin.reject', $user->id) }}" method="POST" class="m-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-op btn-op-reject" title="Refuser la Demande" onclick="return confirm('REFUSER CETTE DEMANDE D\'ACCÈS ?')">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUNE DEMANDE D'ACCÈS DANS LE SYSTÈME</h5>
        </div>
        @endforelse
    </div>

    <!-- 4. Pagination -->
    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $users->links() }}
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    
    body { background: #050505; font-family: 'Oswald', sans-serif; }
    
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Scanner Header */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    
    /* Couleur grise tactique pour le label */
    .stat-label { color: rgba(255, 255, 255, 0.4); }

    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); height: 100%; border-radius: 15px; padding: 15px 0; }
    .status-light-green { width: 8px; height: 8px; background: #00ff88; border-radius: 50%; box-shadow: 0 0 10px #00ff88; animation: blink 1.5s infinite; }
    .scan-icon-mini { width: 40px; height: 40px; border-radius: 10px; background: rgba(255, 237, 0, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 5px 5px 5px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 15px; font-weight: 700; }
    .neo-search-bar i { color: var(--accent); }
    .btn-scan-action { background: var(--accent); color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; }

    .user-avatar-hex { position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; width: 100%; height: 100%; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-text { color: white; font-weight: 900; z-index: 2; font-size: 1.1rem; }

    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.01); }
    .col-applicant { display: flex; align-items: center; }

    .neo-dropdown-wrapper { position: relative; background: #000; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 8px 15px; width: 100%; display: flex; align-items: center; }
    .neo-dropdown-wrapper select { background: transparent !important; border: none; color: white !important; width: 100%; font-size: 11px; outline: none; appearance: none; cursor: pointer; }
    .neo-dropdown-wrapper i { color: var(--accent); font-size: 10px; margin-left: 10px; pointer-events: none; }
    select option { background: #111; color: #fff; }

    .btn-op { width: 38px; height: 38px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; transition: 0.3s; cursor: pointer; }
    .btn-op-approve { background: rgba(0, 255, 136, 0.1); color: #00ff88; }
    .btn-op-approve:hover { background: #00ff88; color: #000; box-shadow: 0 0 15px rgba(0,255,136,0.3); }
    .btn-op-reject { background: rgba(255, 62, 62, 0.1); color: #ff3e3e; }
    .btn-op-reject:hover { background: #ff3e3e; color: #fff; box-shadow: 0 0 15px rgba(255,62,62,0.3); }

    .custom-pagination .pagination { background: var(--panel-bg); padding: 10px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); }
    .custom-pagination .page-link { background: transparent; border: none; color: rgba(255,255,255,0.5); font-weight: 800; font-size: 11px; margin: 0 5px; border-radius: 8px; transition: 0.3s; }
    .custom-pagination .page-item.active .page-link { background: var(--accent); color: #000; }

    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
</style>
@endsection