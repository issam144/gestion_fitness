@extends('layouts.app')

@section('page_title', 'SÉLECTION DES PROTOCOLES - ÉDITION ARMOR')

@section('content')
<div class="tactical-armor-interface py-5">
    
    <div class="container-fluid px-lg-5">
        
        <!-- Header Section: Military HUD Style -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div class="hud-tag mb-2">
                    <span class="status-blink"></span> SYSTÈME D'ACCRÉDITATION ACTIF
                </div>
                <h1 class="display-3 fw-900 text-white m-0 anton-font italic-text uppercase">
                    UNITÉS DE <span class="text-yellow">PUISSANCE</span>
                </h1>
                <div class="hud-line-container">
                    <div class="line-left"></div>
                    <i class="fas fa-crosshairs mx-3 text-yellow"></i>
                    <div class="line-right"></div>
                </div>
            </div>
        </div>

        <!-- 1. Section: Packs Standards (Existing Design) -->
        <div class="row g-4 justify-content-center mb-5">
            @foreach($plans as $plan)
            @php 
                $isPromo = preg_match('/(promo|ramadan|offre|special)/i', $plan->nom);
            @endphp
            <div class="col-xl-4 col-lg-6">
                <div class="armor-card {{ $isPromo ? 'armor-featured' : '' }}">
                    <div class="armor-header d-flex justify-content-between align-items-center">
                        <span class="serial-no">REF: PRT-{{ 100 + $plan->id }}</span>
                        @if($isPromo) <span class="promo-glitch">OFFRE ÉLITE</span> @endif
                    </div>
                    <div class="armor-title-block">
                        <h2 class="plan-name anton-font italic-text">{{ strtoupper($plan->nom) }}</h2>
                        <div class="armor-divider"></div>
                    </div>
                    <div class="armor-body">
                        <div class="armor-price-section text-center mb-4">
                            <div class="price-wrap">
                                <h1 class="price-val anton-font italic-text">{{ number_format($plan->prix, 0) }}</h1>
                                <div class="price-meta">
                                    <span class="curr anton-font text-yellow">DHS</span>
                                    <span class="period">/TOT</span>
                                </div>
                            </div>
                            <div class="validity-label uppercase">{{ $plan->duree_mois }} MOIS D'ACCÈS ILLIMITÉ</div>
                        </div>
                        <div class="armor-features mb-4">
                            @if($plan->description)
                                @php $lines = explode("\n", str_replace("\r", "", $plan->description)); @endphp
                                @foreach($lines as $line)
                                    @if(trim($line) != "")
                                        <div class="armor-feat-item">
                                            <i class="fas fa-caret-right text-yellow me-3"></i>
                                            <span>{{ strtoupper(trim($line)) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="armor-action">
                            <a href="{{ route('client.checkout', $plan->id) }}" class="btn-armor-deploy anton-font">
                                <span class="btn-skew-fix">DÉPLOYER LE PROTOCOLE</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- 2. Section: Custom Squad Builder (The New Custom Part) -->
        <div class="row mt-5 pt-5">
            <div class="col-12 text-center mb-4">
                <h2 class="anton-font italic-text text-white uppercase"><i class="fas fa-tools text-yellow me-3"></i>CONFIGURER VOTRE <span class="text-yellow">SQUAD PERSONNALISÉ</span></h2>
            </div>
            
            <div class="col-12">
                <div class="armor-card p-0" style="clip-path: none; border-radius: 0;">
                    <form action="{{ route('client.checkout.custom') }}" method="POST">
                        @csrf
                        <div class="row g-0">
                            <!-- Sports Selection -->
                            <div class="col-lg-7 p-4 border-end border-white border-opacity-5">
                                <div class="tiny-caps mb-4 text-yellow"><i class="fas fa-list-ul me-2"></i> 01. SÉLECTION DES UNITÉS</div>
                                <div class="row g-3">
                                    @foreach($sports as $sport)
                                    <div class="col-md-6 col-xl-4">
                                        <label class="custom-sport-item" for="sport_{{ $sport->id }}">
                                            <input type="checkbox" name="sports[]" value="{{ $sport->id }}" id="sport_{{ $sport->id }}" class="sport-check">
                                            <div class="sport-inner-box">
                                                <div class="check-indicator"></div>
                                                <span class="anton-font uppercase">{{ $sport->nom }}</span>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Duration & Checkout -->
                            <div class="col-lg-5 p-4 bg-black bg-opacity-50">
                                <div class="tiny-caps mb-4 text-yellow"><i class="fas fa-clock me-2"></i> 02. DURÉE DE LA MISSION</div>
                                
                                <div class="duration-control mb-5">
                                    <div class="d-flex justify-content-between anton-font text-yellow mb-2">
                                        <span>1 MOIS</span>
                                        <span id="months_label">1 MOIS</span>
                                        <span>12 MOIS</span>
                                    </div>
                                    <input type="range" name="months" min="1" max="12" value="1" class="armor-range" id="duration_slider">
                                </div>

                                <div class="summary-box p-4 border border-white border-opacity-10 mb-4 text-center">
                                    <div class="tiny-caps opacity-50 mb-1">COÛT DE DÉPLOIEMENT TOTAL</div>
                                    <h1 class="anton-font italic-text m-0" style="font-size: 60px;"><span id="total_price">0</span> <span class="text-yellow" style="font-size: 20px;">DHS</span></h1>
                                    <div class="validity-label mt-2" id="monthly_info">SÉLECTIONNEZ AU MOINS UNE UNITÉ</div>
                                </div>

                                <button type="submit" class="btn-armor-deploy anton-font w-100 border-0">
                                    CONFIRMER LE SQUAD CUSTOM <i class="fas fa-shield-alt ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checks = document.querySelectorAll('.sport-check');
    const slider = document.getElementById('duration_slider');
    const totalDisplay = document.getElementById('total_price');
    const monthsLabel = document.getElementById('months_label');
    const monthlyInfo = document.getElementById('monthly_info');

    function updateCalculations() {
        const count = document.querySelectorAll('.sport-check:checked').length;
        const months = parseInt(slider.value);
        let monthlyPrice = 0;

        if (count > 0) {
            // الأولى بـ 200 و أي واحدة تالية بـ 50
            monthlyPrice = 200 + ((count - 1) * 50);
        }

        const grandTotal = monthlyPrice * months;

        totalDisplay.innerText = grandTotal.toLocaleString();
        monthsLabel.innerText = months + ' MOIS';
        
        if(count > 0) {
            monthlyInfo.innerText = monthlyPrice + ' DHS / MOIS × ' + months + ' MOIS';
        } else {
            monthlyInfo.innerText = 'SÉLECTIONNEZ AU MOINS UNE UNITÉ';
        }

        // Visual feedback
        checks.forEach(c => {
            if(c.checked) c.closest('.custom-sport-item').classList.add('active');
            else c.closest('.custom-sport-item').classList.remove('active');
        });
    }

    checks.forEach(c => c.addEventListener('change', updateCalculations));
    slider.addEventListener('input', updateCalculations);
    updateCalculations();
});
</script>

<style>
    /* كود الـ CSS لي كان عندك بالضبط */
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@300;400;700;900&display=swap');
    :root { --yellow: #ffed00; --dark-card: #0c0c0c; --border-color: rgba(255, 255, 255, 0.08); }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; overflow-x: hidden; }
    .anton-font { font-family: 'Anton', sans-serif; }
    .italic-text { font-style: italic; }
    .fw-900 { font-weight: 900; }
    .text-yellow { color: var(--yellow); }
    .uppercase { text-transform: uppercase; }
    .tiny-caps { font-size: 11px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }

    /* HUD Elements */
    .hud-tag { font-size: 10px; font-weight: 900; letter-spacing: 3px; color: rgba(255,255,255,0.4); }
    .status-blink { display: inline-block; width: 8px; height: 8px; background: var(--yellow); border-radius: 50%; margin-right: 10px; animation: blink 1.5s infinite; }
    .hud-line-container { display: flex; align-items: center; justify-content: center; margin-top: 15px; }
    .line-left, .line-right { height: 1px; background: rgba(255,255,255,0.1); width: 100px; }

    /* Armor Card */
    .armor-card { background: var(--dark-card); border: 1px solid var(--border-color); position: relative; overflow: hidden; transition: 0.4s; height: 100%; display: flex; flex-direction: column; clip-path: polygon(0 0, 92% 0, 100% 8%, 100% 100%, 8% 100%, 0 92%); }
    .armor-card:hover { transform: translateY(-10px); border-color: var(--yellow); }
    .armor-featured { border: 1px solid var(--yellow); }
    .armor-header { padding: 15px 20px; font-size: 10px; font-weight: 900; color: rgba(255,255,255,0.3); }
    .promo-glitch { color: #000; background: var(--yellow); padding: 2px 10px; }
    .armor-title-block { padding: 0 30px; }
    .plan-name { font-size: 45px; line-height: 1; margin: 0; }
    .armor-divider { width: 50px; height: 5px; background: var(--yellow); margin-top: 10px; }
    .armor-body { padding: 30px; flex-grow: 1; display: flex; flex-direction: column; }
    .armor-price-section { background: rgba(255,255,255,0.02); padding: 25px; border: 1px solid rgba(255,255,255,0.03); }
    .price-val { font-size: 80px; line-height: 0.8; }
    .validity-label { font-size: 10px; font-weight: 900; color: var(--yellow); letter-spacing: 1px; }

    /* Custom Parts Styling (Matching the Armor Theme) */
    .custom-sport-item { display: block; cursor: pointer; }
    .custom-sport-item input { display: none; }
    .sport-inner-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 15px; display: flex; align-items: center; transition: 0.3s; }
    .check-indicator { width: 10px; height: 10px; border: 1px solid rgba(255,255,255,0.2); margin-right: 15px; }
    .custom-sport-item.active .sport-inner-box { border-color: var(--yellow); background: rgba(255,237,0,0.1); }
    .custom-sport-item.active .check-indicator { background: var(--yellow); box-shadow: 0 0 10px var(--yellow); border-color: var(--yellow); }

    .armor-range { -webkit-appearance: none; width: 100%; height: 4px; background: rgba(255,255,255,0.1); outline: none; }
    .armor-range::-webkit-slider-thumb { -webkit-appearance: none; width: 20px; height: 20px; background: var(--yellow); cursor: pointer; border-radius: 0; clip-path: polygon(20% 0%, 100% 0%, 80% 100%, 0% 100%); }

    .btn-armor-deploy { background: var(--yellow); color: #000; text-decoration: none; display: block; padding: 20px; text-align: center; font-size: 16px; font-weight: 900; transition: 0.3s; clip-path: polygon(5% 0%, 100% 0%, 95% 100%, 0% 100%); }
    .btn-armor-deploy:hover { background: #fff; transform: scale(1.02); }

    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.2; } }
</style>
@endsection