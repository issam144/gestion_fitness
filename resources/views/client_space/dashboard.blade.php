@extends('layouts.app')

@section('page_title', 'CLIENT COMMAND CENTER')

@section('content')
<div class="container-fluid py-4 px-lg-5 dashboard-main">
    
    <!-- 1. Operational HUD Header -->
    <div class="row mb-5 align-items-end">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-2">Adhérent Profile: {{ strtoupper($user->name) }} • Authorized Access</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase display-6">CLIENT <span style="color: var(--accent);">DASHBOARD</span></h1>
        </div>
        <div class="col-md-5 text-md-end">
            <div class="scanner-info-bar py-3 px-4 shadow-neon">
                <span class="text-secondary tiny-caps me-3">System Time: <span id="live-clock" class="text-white fw-bold h5 mb-0">--:--:--</span></span>
                <span class="status-label-live"><i class="fas fa-satellite me-1"></i> SECURE SESSION</span>
            </div>
        </div>
    </div>

    <!-- 2. Tactical Metrics -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid {{ $daysLeft <= 5 ? '#ff3e3e' : 'var(--accent)' }}">
                <span class="stat-label">SIGNAL VALIDITY (JOURS RESTANTS)</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-white m-0">{{ $daysLeft }} <span class="fs-6 opacity-50">JOURS</span></h2>
                    <div class="scan-icon-mini {{ $daysLeft <= 5 ? 'pulse-red' : 'pulse-yellow' }}">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
                <div class="mini-signal-bar mt-3">
                    @php $progress = min(100, ($daysLeft / 30) * 100); @endphp
                    <div class="fill" style="width: {{ $progress }}%; background: {{ $daysLeft <= 5 ? '#ff3e3e' : 'var(--accent)' }}"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid #00ff88">
                <span class="stat-label">ACTIVITY RECORD (PRÉSENCE TOTALE)</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-white m-0">{{ $totalPresence }} <span class="fs-6 opacity-50">SESSIONS</span></h2>
                    <div class="scan-icon-mini" style="background: rgba(0, 255, 136, 0.1); color: #00ff88">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                </div>
                <div class="mini-signal-bar mt-3"><div class="fill" style="width: 100%; background: #00ff88"></div></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="scanner-stat-card h-100" style="border-left: 4px solid #00d2ff">
                <span class="stat-label">MEMBERSHIP PROTOCOL (ABONNEMENT)</span>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <h2 class="fw-900 text-accent m-0 uppercase" style="font-size: 1.4rem;">{{ $abonnement->typeAbonnement->nom ?? 'ELITE PASS' }}</h2>
                    <div class="scan-icon-mini" style="background: rgba(0, 210, 255, 0.1); color: #00d2ff">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
                <span class="tiny-caps text-white-50 mt-2 d-block">ADHÉRENT STATUS ACTIVE</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- 3. Validation Terminal - Recent Activity -->
        <div class="col-lg-8">
            <div class="validation-terminal h-100">
                <div class="terminal-header d-flex justify-content-between align-items-center px-4 py-3">
                    <span class="tiny-caps text-white fw-bold"><i class="fas fa-history text-accent me-2"></i> RECENT ACTIVITY LOGS</span>
                    <a href="{{ route('client.history') }}" class="btn-scan-action" style="font-size: 8px; text-decoration:none; color:var(--accent);">FULL ARCHIVE</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark m-0">
                        <thead>
                            <tr class="tiny-caps text-secondary border-bottom border-white border-opacity-5" style="font-size: 10px;">
                                <th class="ps-4 py-3">DATE / TIMELINE</th>
                                <th>DISCIPLINE UNIT</th>
                                <th>COMMANDER</th>
                                <th class="text-end pe-5">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSessions as $session)
                            @php 
                                $isFuture = \Carbon\Carbon::parse($session->date_seance)->isFuture();
                                $isToday = \Carbon\Carbon::parse($session->date_seance)->isToday();
                            @endphp
                            <tr class="terminal-row-hover {{ $isFuture ? 'upcoming-glow' : '' }}">
                                <td class="ps-4 py-3">
                                    <div class="user-avatar-hex d-inline-flex" style="width: 30px; height: 30px; vertical-align: middle;">
                                        <span class="hex-text" style="font-size: 8px;">
                                            <i class="fas {{ $isFuture ? 'fa-clock text-accent' : 'fa-check-double' }}"></i>
                                        </span>
                                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                                    </div>
                                    <span class="text-white fw-bold ms-2">{{ \Carbon\Carbon::parse($session->date_seance)->format('d M Y') }}</span>
                                    @if($isToday)
                                        <span class="badge bg-danger ms-2 badge-blink">TODAY</span>
                                    @endif
                                </td>
                                <td><span class="badge-status-elite active">{{ $session->type_seance->nom ?? 'N/A' }}</span></td>
                                <td class="text-secondary small uppercase">{{ $session->coach->user->name ?? 'COMMANDER' }}</td>
                                <td class="text-end pe-5">
                                    @if($isFuture)
                                        <span class="text-info tiny-caps fw-900"><i class="fas fa-satellite-dish me-1"></i> SCHEDULED</span>
                                    @else
                                        @if($session->pivot && $session->pivot->is_present)
                                            <span class="text-success tiny-caps fw-900"><i class="fas fa-check-circle me-1"></i> PRÉSENT</span>
                                        @else
                                            <span class="text-danger tiny-caps fw-900"><i class="fas fa-times-circle me-1"></i> ABSENT</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 tiny-caps opacity-30">NO PROTOCOLS ASSIGNED BY ADMIN</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 4. Digital Access Pass (DYNAMIC QR VERSION) -->
        <div class="col-lg-4">
            <div class="validation-terminal p-4 h-100 text-center d-flex flex-column justify-content-center border-accent-dim shadow-neon">
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <span class="status-label-live" style="background: rgba(255,237,0,0.1); color: var(--accent);">SECURE PASS</span>
                    <span id="qr-timer" class="text-accent fw-900" style="font-size: 10px;">10s</span>
                </div>
                
                <h5 class="text-white fw-900 mb-3 uppercase letter-spacing-1">DYNAMIC ADHÉRENT PASS</h5>
                
                <div class="qr-box-tactical mb-3 mx-auto">
                    <!-- الحاوية الجديدة للـ QR Code -->
                    <div id="dynamic-qrcode" style="background: white; padding: 10px; border-radius: 10px; display: inline-block;"></div>
                    <div class="scan-line"></div>
                </div>

                <div class="px-4 mb-4">
                    <div class="mini-signal-bar" style="height: 3px; background: rgba(255,255,255,0.05);">
                        <div id="timer-progress" class="fill" style="width: 100%; background: var(--accent); transition: width 1s linear;"></div>
                    </div>
                </div>

                <p class="text-secondary tiny-caps mb-4" style="line-height: 1.5; font-size: 8px;">
                    <i class="fas fa-shield-alt me-1"></i> Code rafraîchi toutes les 10 secondes pour prévenir toute fraude.
                </p>

                <div class="d-grid gap-2">
                    <button class="btn-quick-ops py-2" onclick="updateAccessCode()">
                        <i class="fas fa-sync-alt me-2"></i> FORCE REFRESH
                    </button>
                    <a href="{{ route('client.download.receipt') }}" class="btn-quick-ops mt-2 text-center" style="text-decoration: none; border-color: #00d2ff; color: #00d2ff;">
                        <i class="fas fa-download me-2"></i> ACCESS CARD (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .tiny-caps { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }

    .upcoming-glow { background: rgba(0, 210, 255, 0.02) !important; }
    .badge-blink { font-size: 7px; animation: blinkStatus 1.5s infinite; border-radius: 2px; padding: 2px 5px; }
    
    @keyframes blinkStatus { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; display: inline-flex; align-items: center; }
    .status-label-live { background: rgba(0, 255, 136, 0.1); color: #00ff88; font-size: 8px; font-weight: 900; padding: 4px 12px; border-radius: 50px; }
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 15px; position: relative; }
    .stat-label { color: rgba(255,255,255,0.3); font-size: 8px; font-weight: 800; display: block; margin-bottom: 5px; }
    .scan-icon-mini { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }

    .qr-box-tactical { width: 200px; height: 200px; border: 2px solid var(--accent); border-radius: 15px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; background: #000; padding: 15px; }
    .scan-line { position: absolute; width: 100%; height: 2px; background: var(--accent); top: 0; box-shadow: 0 0 15px var(--accent); animation: scanQR 3s infinite linear; z-index: 5; }
    @keyframes scanQR { 0% { top: 0; } 100% { top: 100%; } }

    .validation-terminal { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.03); border-radius: 20px; overflow: hidden; }
    .terminal-header { background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05); }
    
    .table-dark { background: transparent !important; }
    .table-dark td { border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle; padding: 18px 15px; }

    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .hex-text { color: #fff; font-weight: 900; z-index: 2; }

    .btn-quick-ops { background: #000; border: 1px solid var(--accent); color: #fff; padding: 12px 25px; border-radius: 8px; font-size: 9px; font-weight: 900; transition: 0.3s; cursor: pointer; }
    .btn-quick-ops:hover { background: var(--accent); color: #000; transform: translateY(-2px); box-shadow: 0 5px 20px rgba(255, 237, 0, 0.2); }
    
    .status-indicator-pulse { width: 10px; height: 10px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    @keyframes pGlow { 0% { transform: scale(1); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
    
    .mini-signal-bar { height: 2px; background: rgba(255,255,255,0.05); border-radius: 2px; overflow: hidden; }
    .mini-signal-bar .fill { height: 100%; transition: 1s linear; }
    .shadow-neon { box-shadow: 0 0 20px rgba(255, 237, 0, 0.05); }
</style>

<!-- SCRIPTS FOR DYNAMIC QR -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // 1. Live Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('live-clock').textContent = now.toLocaleTimeString('fr-FR');
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Dynamic QR Logic
    var qrcode = new QRCode(document.getElementById("dynamic-qrcode"), {
        width: 160,
        height: 160,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.M
    });

    let timeLeft = 10;

    function updateAccessCode() {
        // توليد التوكن: UserID-TimeBlock
        let timestamp = Math.floor(Date.now() / 10000); 
        let token = "{{ auth()->id() }}-" + timestamp;
        
        qrcode.makeCode(token);
        timeLeft = 10;
        document.getElementById('timer-progress').style.width = "100%";
    }

    setInterval(function() {
        timeLeft--;
        if (timeLeft < 0) {
            updateAccessCode();
        } else {
            document.getElementById('qr-timer').innerText = timeLeft + "s";
            document.getElementById('timer-progress').style.width = (timeLeft * 10) + "%";
        }
    }, 1000);

    // Initial load
    updateAccessCode();
</script>
@endsection