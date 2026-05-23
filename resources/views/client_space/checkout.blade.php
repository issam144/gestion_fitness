@extends('layouts.app')

@section('page_title', 'PHASE ÉLITE // PAIEMENT SÉCURISÉ')

@section('content')
@php
    // كنشوفو واش الكليان صاوب Squad Custom ولا ختار Pack عادي
    $isCustomSession = session()->has('custom_squad');
    $customData = session('custom_squad');
    
    // تحديد الثمن والسمية (بناءً على واش جاي من كاستم ولا عرض عادي)
    if ($isCustomSession) {
        $finalPrice = $customData['total_price'];
        $finalName = 'SQUAD PERSONNALISÉ';
        $finalMonths = $customData['months'];
    } else {
        $finalPrice = $plan->prix;
        $finalName = $plan->nom;
        $finalMonths = $plan->duree_mois;
    }
@endphp

<div class="cyber-vault-interface">
    <!-- Background Elements -->
    <div class="vault-grid"></div>
    <div class="vault-scanner"></div>
    <div class="giant-bg-text anton-font">SECURE</div>

    <div class="container-fluid h-100 position-relative" style="z-index: 2;">
        <div class="row h-100 g-0">
            
            <!-- SECTION 1: SPÉCIFICATIONS (GAUCHE) -->
            <div class="col-lg-5 spec-column p-5 d-flex flex-column justify-content-center">
                <div class="spec-card-wrap">
                    <div class="status-badge mb-4 uppercase">
                        <span class="pulse-red"></span> SYSTÈME_PRÊT : VÉRIFICATION_COÛT
                    </div>
                    <h1 class="plan-hero anton-font italic-text m-0 text-white uppercase">
                        PROTOCOLE<br><span class="text-yellow">{{ strtoupper($finalName) }}</span>
                    </h1>
                    <div class="divider-long my-4"></div>
                    
                    <div class="stats-row d-flex gap-5">
                        <div class="stat-box">
                            <span class="s-label uppercase">DURÉE</span>
                            <span class="s-value">{{ $finalMonths }}M</span>
                        </div>
                        <div class="stat-box">
                            <span class="s-label uppercase">TYPE</span>
                            <span class="s-value uppercase">{{ $isCustomSession ? 'SQUAD' : 'PACK' }}</span>
                        </div>
                        <div class="stat-box">
                            <span class="s-label uppercase">SÉCURITÉ</span>
                            <span class="s-value uppercase">SSL</span>
                        </div>
                    </div>

                    <!-- إذا كان Custom، كنبينو ليه الرياضات لي ختار -->
                    @if($isCustomSession && isset($customData['sports_ids']))
                    <div class="mt-4">
                        <span class="tiny-caps opacity-50">UNITÉS OPÉRATIONNELLES :</span>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @php 
                                $sportsNames = \App\Models\TypeSeance::whereIn('id', $customData['sports_ids'])->pluck('nom'); 
                            @endphp
                            @foreach($sportsNames as $sName)
                                <span class="badge border border-yellow text-yellow uppercase small" style="font-size: 10px; padding: 5px 10px;">{{ $sName }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="price-nexus mt-5">
                        <div class="price-display">
                            <span class="p-currency">MAD</span>
                            <span class="p-amount anton-font">{{ number_format($finalPrice, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: TERMINAL DE PAIEMENT (DROITE) -->
            <div class="col-lg-7 terminal-column d-flex align-items-center justify-content-center">
                <div class="vault-terminal shadow-2xl">
                    <div class="terminal-frame">
                        <div class="t-corner c-tl"></div><div class="t-corner c-tr"></div>
                        <div class="t-corner c-bl"></div><div class="t-corner c-br"></div>

                        <form action="{{ route('client.process.payment') }}" method="POST" id="payment-form" class="p-4 p-lg-5">
                            @csrf
                            
                            @if($isCustomSession)
                                <input type="hidden" name="is_custom" value="1">
                            @else
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            @endif

                            <div class="form-header mb-5 d-flex align-items-end justify-content-between">
                                <div>
                                    <h2 class="anton-font text-white m-0 uppercase">COFFRE <span class="text-yellow">SÉCURISÉ</span></h2>
                                    <p class="text-secondary tiny-caps m-0 uppercase">Vérification de Signature Bancaire</p>
                                </div>
                                <i class="fas fa-shield-alt text-yellow fs-2"></i>
                            </div>

                            <div class="cyber-form-body">
                                <div class="cyber-group mb-4">
                                    <label class="cyber-tag uppercase">NOM_DU_TITULAIRE</label>
                                    <div class="cyber-input">
                                        <input type="text" name="card_name" id="card-name" placeholder="NOM COMPLET" required class="uppercase">
                                        <div class="input-glow"></div>
                                    </div>
                                </div>

                                <div class="cyber-group mb-4">
                                    <label class="cyber-tag uppercase">SIGNATURE_BANCAIRE (N° CARTE)</label>
                                    <div class="cyber-input stripe-box">
                                        <div id="card-number-element"></div>
                                        <div class="input-glow"></div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="cyber-group">
                                            <label class="cyber-tag uppercase">EXPIRATION</label>
                                            <div class="cyber-input stripe-box"><div id="card-expiry-element"></div></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="cyber-group">
                                            <label class="cyber-tag uppercase">CODE_CVC</label>
                                            <div class="cyber-input stripe-box"><div id="card-cvc-element"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="card-errors" class="error-msg uppercase" role="alert"></div>

                            <div class="mt-5">
                                <button type="submit" id="submit-button" class="vault-btn anton-font uppercase">
                                    <span id="button-text">DÉPLOYER L'ACCRÉDITATION</span>
                                    <div id="spinner" class="spinner-border spinner-border-sm d-none ms-2" role="status"></div>
                                    <div class="btn-flare"></div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@300;400;700;900&display=swap');
    :root { --yellow: #ffed00; --bg: #050505; --card-bg: rgba(15,15,15,0.95); }
    
    body, html { height: 100%; background: var(--bg); font-family: 'Oswald', sans-serif; color: #fff; overflow-x: hidden; }
    .anton-font { font-family: 'Anton', sans-serif; letter-spacing: 2px; }
    .text-yellow { color: var(--yellow); }
    .tiny-caps { font-size: 10px; font-weight: 900; letter-spacing: 2px; }
    .uppercase { text-transform: uppercase; }

    /* HUD Effects */
    .vault-grid { position: fixed; inset: 0; background-image: radial-gradient(circle at 2px 2px, rgba(255,237,0,0.05) 1px, transparent 0); background-size: 40px 40px; z-index: 0; }
    .giant-bg-text { position: fixed; bottom: -5%; right: -5%; font-size: 20vw; color: rgba(255,255,255,0.02); z-index: 0; pointer-events: none; }
    .vault-scanner { position: fixed; top: 0; width: 100%; height: 5px; background: linear-gradient(to right, transparent, var(--yellow), transparent); opacity: 0.1; animation: scan 4s infinite linear; z-index: 1; }

    /* Layout */
    .plan-hero { font-size: 4rem; line-height: 0.85; }
    .divider-long { width: 150px; height: 3px; background: var(--yellow); }
    .s-value { font-size: 1.8rem; font-weight: 700; }
    .p-amount { font-size: 5rem; line-height: 1; }

    /* Terminal */
    .vault-terminal { background: var(--card-bg); border: 1px solid rgba(255,255,255,0.05); position: relative; width: 100%; max-width: 600px; backdrop-filter: blur(10px); }
    .t-corner { position: absolute; width: 25px; height: 25px; border: 2px solid var(--yellow); z-index: 5; }
    .c-tl { top: -2px; left: -2px; border-right: 0; border-bottom: 0; }
    .c-tr { top: -2px; right: -2px; border-left: 0; border-bottom: 0; }
    .c-bl { bottom: -2px; left: -2px; border-right: 0; border-top: 0; }
    .c-br { bottom: -2px; right: -2px; border-left: 0; border-top: 0; }

    .cyber-input { background: #000; border: 1px solid #1a1a1a; padding: 12px 20px; position: relative; }
    .cyber-input input { background: transparent; border: none; color: #fff; width: 100%; outline: none; font-family: 'Oswald', sans-serif; font-weight: 700; }
    .stripe-box { min-height: 50px; }

    .vault-btn { background: var(--yellow); color: #000; border: none; padding: 22px; width: 100%; font-size: 1.2rem; font-weight: 900; cursor: pointer; position: relative; overflow: hidden; transition: 0.3s; }
    .vault-btn:hover { background: #fff; transform: translateY(-3px); }
    .btn-flare { position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent); animation: shine 3s infinite; }

    @keyframes scan { from { top: 0%; } to { top: 100%; } }
    @keyframes shine { to { left: 200%; } }
    .error-msg { color: #ff4d4d; font-size: 11px; font-weight: 900; margin-top: 15px; text-align: center; }

    .pulse-red { display: inline-block; width: 8px; height: 8px; background: #ff4d4d; border-radius: 50%; margin-right: 10px; box-shadow: 0 0 10px #ff4d4d; animation: pRed 1s infinite; }
    @keyframes pRed { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
</style>

<script src="https://js.stripe.com/v3/"></script>
<script>
    // جلب المفتاح من ملف .env
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    const elements = stripe.elements();

    // تنسيق الـ Input ديال Stripe باش يجي مع الـ Design التاكتيكي
    const elementStyle = {
        base: {
            color: '#ffffff',
            fontFamily: '"Oswald", sans-serif',
            fontSize: '16px',
            '::placeholder': { color: '#444' },
        },
        invalid: { color: '#ffed00', iconColor: '#ffed00' }
    };

    const cardNumber = elements.create('cardNumber', { style: elementStyle });
    cardNumber.mount('#card-number-element');

    const cardExpiry = elements.create('cardExpiry', { style: elementStyle });
    cardExpiry.mount('#card-expiry-element');

    const cardCvc = elements.create('cardCvc', { style: elementStyle });
    cardCvc.mount('#card-cvc-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        // منع الضغط المتكرر
        submitButton.disabled = true;
        document.getElementById('spinner').classList.remove('d-none');
        document.getElementById('button-text').textContent = 'VÉRIFICATION_EN_COURS...';

        const {token, error} = await stripe.createToken(cardNumber);

        if (error) {
            // إظهار الخطأ
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = `ERREUR_SYSTÈME: ${error.message}`;
            
            // إعادة تفعيل الزر
            submitButton.disabled = false;
            document.getElementById('spinner').classList.add('d-none');
            document.getElementById('button-text').textContent = 'DÉPLOYER L\'ACCRÉDITATION';
        } else {
            // إرسال التوكن للسيرفر
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
</script>
@endsection