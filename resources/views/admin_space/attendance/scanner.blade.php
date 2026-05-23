@extends('layouts.app')

@section('page_title', 'RADAR D\'IDENTIFICATION OPÉRATIONNEL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            
            <!-- Tactical Scanner Terminal -->
            <div class="scanner-terminal shadow-lg">
                
                <!-- Terminal Header -->
                <div class="terminal-top-bar d-flex justify-content-between align-items-center">
                    <div class="protocol-status uppercase fw-900">
                        <!-- Point jaune supprimé ici -->
                    </div>
                    <div class="sys-id fw-900"></div>
                </div>

                <!-- Scanner Core Frame -->
                <div class="scanner-frame-wrapper p-4 p-lg-5">
                    <div class="radar-crosshair">
                        <div class="corner top-left"></div>
                        <div class="corner top-right"></div>
                        <div class="corner bottom-left"></div>
                        <div class="corner bottom-right"></div>
                        
                        <!-- الصندوق لي فيه الكاميرا -->
                        <div id="reader" class="scanner-container"></div>
                    </div>

                    <!-- Result Panel -->
                    <div id="result-box" class="mt-4 d-none">
                        <div class="tactical-alert">
                            <div class="alert-icon"><i class="fas fa-id-card"></i></div>
                            <div class="alert-data">
                                <h6 id="res-name" class="m-0 fw-900 uppercase">IDENTIFICATION...</h6>
                                <p id="res-msg" class="m-0 tiny-caps">CHARGEMENT DES DONNÉES</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-secondary small uppercase letter-spacing-sm m-0" style="font-size: 9px;">
                            <i class="fas fa-info-circle me-1"></i> Positionnez le QR Code au centre du radar
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* --- SCANNER TERMINAL DESIGN --- */
    .scanner-terminal {
        background: #0d0d0d;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }
    .scanner-terminal::before {
        content: ''; position: absolute; top: 0; left: 20%; width: 60%; height: 2px;
        background: var(--accent); box-shadow: 0 0 15px var(--accent);
    }

    .scanner-frame-wrapper { background: radial-gradient(circle at center, rgba(255, 237, 0, 0.02) 0%, transparent 70%); }

    /* Radar Crosshair Effect */
    .radar-crosshair {
        position: relative; padding: 10px;
        background: rgba(0,0,0,0.4);
    }
    .corner {
        position: absolute; width: 30px; height: 30px;
        border: 2px solid var(--accent); z-index: 10;
    }
    .top-left { top: 0; left: 0; border-right: none; border-bottom: none; }
    .top-right { top: 0; right: 0; border-left: none; border-bottom: none; }
    .bottom-left { bottom: 0; left: 0; border-right: none; border-top: none; }
    .bottom-right { bottom: 0; right: 0; border-left: none; border-top: none; }

    /* Library Button Overrides (Making them look Elite) */
    #reader button {
        background: var(--accent) !important;
        color: #000 !important;
        border: none !important;
        padding: 12px 25px !important;
        font-family: 'Oswald', sans-serif !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
        border-radius: 5px !important;
        margin-top: 10px !important;
    }
    #reader a { color: var(--accent) !important; font-size: 11px !important; text-transform: uppercase !important; }

    .scanner-container {
        width: 100% !important; border: none !important; 
        background: #000 !important; min-height: 300px;
    }

    /* Tactical Alert Box */
    .tactical-alert {
        display: flex; align-items: center; gap: 20px;
        padding: 20px; background: rgba(0, 255, 136, 0.05);
        border: 1px solid rgba(0, 255, 136, 0.2);
    }
    .tactical-alert.error {
        background: rgba(255, 62, 62, 0.05); border-color: rgba(255, 62, 62, 0.2);
    }
    .alert-icon { font-size: 24px; color: #00ff88; }
    .error .alert-icon { color: #ff3e3e; }
    .alert-data h6 { letter-spacing: 1px; color: #fff; }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
</style>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText) {
        html5QrcodeScanner.pause(true);

        fetch("{{ route('admin.attendance.mark') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ user_id: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            let box = document.getElementById('result-box');
            let alertDiv = box.querySelector('.tactical-alert');
            box.classList.remove('d-none');
            
            if(data.success) {
                alertDiv.classList.remove('error');
                document.getElementById('res-name').innerText = data.member_name;
                document.getElementById('res-msg').innerText = data.message;
                new Audio('https://assets.mixkit.co/sfx/preview/mixkit-digital-quick-tone-2111.mp3').play();
            } else {
                alertDiv.classList.add('error');
                document.getElementById('res-name').innerText = "ACCÈS REFUSÉ";
                document.getElementById('res-msg').innerText = data.message;
            }

            setTimeout(() => {
                box.classList.add('d-none');
                html5QrcodeScanner.resume();
            }, 4000);
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
        fps: 15, 
        qrbox: {width: 250, height: 250},
        aspectRatio: 1.0
    });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection