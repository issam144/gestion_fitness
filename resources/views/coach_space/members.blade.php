@extends('layouts.app')

@section('page_title', 'SQUAD OPERATIONAL ROSTER')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Squad HUD Header -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-2">Squad Intel: Authorized Members Under Your Command</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase display-6">MY <span style="color: var(--accent);">SQUAD</span> UNITS</h1>
        </div>
        <div class="col-md-5 text-md-end">
            <div class="scanner-info-bar py-3 px-4 shadow-neon">
                <span class="text-secondary tiny-caps me-3">Total Warriors: <span class="text-white fw-bold h5 mb-0">{{ $members->count() }}</span></span>
                <span class="status-label-live"><i class="fas fa-users-check me-1"></i> VERIFIED</span>
            </div>
        </div>
    </div>

    <!-- 2. Search Command Bar -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <div class="neo-search-bar">
                <i class="fas fa-fingerprint"></i>
                <input type="text" id="memberSearch" placeholder="Scan database for member signature (name)...">
            </div>
        </div>
    </div>

    <!-- 3. Members Tactical Grid -->
    <div class="row g-4" id="membersGrid">
        @forelse($members as $member)
        <div class="col-lg-3 col-md-6 member-card">
            <div class="validation-terminal p-4 text-center h-100 border-accent-dim hover-glow pb-5">
                
                <!-- Hexagon Avatar -->
                <div class="user-avatar-hex mx-auto mb-4" style="width: 90px; height: 90px;">
                    @if($member->image)
                        <div class="hex-image-wrap"><img src="{{ asset('storage/'.$member->image) }}" class="hex-img"></div>
                    @else
                        <span class="hex-text" style="font-size: 20px;">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    @endif
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    <div class="scan-line-mini"></div>
                </div>

                <h5 class="text-white fw-900 mb-1 uppercase member-name">{{ $member->name }}</h5>
                <p class="text-secondary tiny-caps mb-3" style="font-size: 8px; opacity: 0.7;">{{ $member->email }}</p>

                <div class="tactical-divider mx-auto mb-4"></div>

                <!-- Consistency Level (Attendance Logic) -->
                <div class="text-start mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="tiny-caps text-secondary" style="font-size: 7px;">Consistency Level</span>
                        <span class="tiny-caps text-success fw-bold" style="font-size: 7px;">HIGH</span>
                    </div>
                    <div class="terminal-progress" style="height: 4px;">
                        <div class="bar" style="width: 85%; background: #00ff88; box-shadow: 0 0 10px rgba(0, 255, 136, 0.4);"></div>
                    </div>
                </div>
                
                <!-- Hna 7yedna l-boutons kima bghiti ✅ -->

            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 opacity-30">
            <i class="fas fa-user-slash fa-3x mb-3 text-accent"></i>
            <h5 class="tiny-caps">No units detected in your squad history.</h5>
        </div>
        @endforelse
    </div>
</div>

<style>
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background: #080808; font-family: 'Oswald', sans-serif; color: #fff; }
    
    .fw-900 { font-weight: 900; }
    .tiny-caps { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }

    /* HUD Elements */
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; display: inline-flex; align-items: center; }
    .status-label-live { background: rgba(0, 255, 136, 0.1); color: #00ff88; font-size: 8px; font-weight: 900; padding: 4px 12px; border-radius: 50px; }

    /* Tactical Cards */
    .validation-terminal { background: var(--panel-bg); border-radius: 20px; border: 1px solid rgba(255,255,255,0.03); transition: 0.4s; position: relative; overflow: hidden; }
    .hover-glow:hover { border-color: var(--accent); box-shadow: 0 0 30px rgba(255, 237, 0, 0.1); transform: translateY(-5px); }
    
    /* Search Bar */
    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 15px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 15px; font-weight: 600; text-transform: uppercase; }
    .neo-search-bar i { color: var(--accent); }

    /* Hexagon UI */
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-text { color: white; font-weight: 900; z-index: 2; }
    .hex-image-wrap { position: absolute; width: 88%; height: 88%; clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%); z-index: 2; overflow: hidden; }
    .hex-img { width: 100%; height: 100%; object-fit: cover; }

    .tactical-divider { width: 40px; height: 3px; background: var(--accent); border-radius: 2px; }
    .terminal-progress { background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden; }
    .terminal-progress .bar { height: 100%; transition: 1s; }

    .status-indicator-pulse { width: 10px; height: 10px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    @keyframes pGlow { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
    .shadow-neon { box-shadow: 0 0 20px rgba(255, 237, 0, 0.05); }
    
    .scan-line-mini { position: absolute; width: 100%; height: 2px; background: var(--accent); top: 0; z-index: 4; opacity: 0.3; animation: scanHex 3s infinite linear; }
    @keyframes scanHex { 0% { top: 10%; } 50% { top: 90%; } 100% { top: 10%; } }
</style>

<script>
    document.getElementById('memberSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let cards = document.getElementsByClassName('member-card');
        for (let i = 0; i < cards.length; i++) {
            let name = cards[i].getElementsByClassName('member-name')[0].innerText;
            cards[i].style.display = name.toUpperCase().includes(filter) ? "" : "none";
        }
    });
</script>
@endsection