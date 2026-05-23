<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | CENTRE DE COMMANDEMENT</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@700&family=Space+Grotesk:wght@300;700&family=Bebas+Neue&family=Anton&family=Oswald:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-v2.png') }}?v={{ time() }}">

    <style>
        :root { --accent: #ffed00; --bg: #030303; --dark-card: #0c0c0c; --border-color: rgba(255, 255, 255, 0.08); }
        
        body { margin: 0; background: var(--bg); color: #fff; font-family: 'Space Grotesk', sans-serif; overflow-x: hidden; cursor: none; }

        /* 1. Préchargeur */
        #loader {
            position: fixed; inset: 0; background: #000; z-index: 10000;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .boot-text { font-family: monospace; font-size: 10px; color: var(--accent); letter-spacing: 2px; }

        /* 2. Curseur */
        #custom-cursor { position: fixed; width: 20px; height: 20px; background: var(--accent); border-radius: 50%; pointer-events: none; z-index: 9999; mix-blend-mode: difference; transition: transform 0.1s ease; }
        #cursor-aura { position: fixed; width: 150px; height: 150px; border: 1px solid rgba(255,237,0,0.3); border-radius: 50%; pointer-events: none; z-index: 9998; transform: translate(-50%, -50%); transition: 0.15s ease-out; }

        /* 3. Welcome Section */
        .stage { height: 100vh; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .bg-layer { position: absolute; width: 110%; height: 110%; background-image: url('https://images.unsplash.com/photo-1593079831268-3381b0db4a77?q=80&w=2069'); background-size: cover; background-position: center; filter: brightness(0.3) grayscale(1); z-index: 1; }
        .glass-hud { position: absolute; background: rgba(255,255,255,0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); padding: 20px; z-index: 10; pointer-events: none; }
        .hud-1 { top: 10%; left: 5%; width: 200px; }
        .hud-2 { bottom: 10%; right: 5%; width: 250px; }
        .main-content { position: relative; z-index: 5; text-align: center; }
        .hero-title { font-family: 'Bebas Neue', cursive; font-size: clamp(6rem, 20vw, 15rem); margin: 0; line-height: 0.8; letter-spacing: -5px; }
        .accent-text { color: var(--accent); text-shadow: 0 0 50px rgba(255,237,0,0.3); }

        nav { position: fixed; top: 0; width: 100%; padding: 50px; z-index: 1000; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-family: 'Syncopate'; font-size: 1.2rem; letter-spacing: 8px; }
        .auth-cluster a { text-decoration: none; color: #fff; font-size: 10px; font-weight: 700; letter-spacing: 4px; text-transform: uppercase; margin-left: 50px; padding: 12px 30px; border-radius: 2px; transition: 0.3s; }
        .btn-outline { border: 1px solid rgba(255,255,255,0.2); }
        .btn-outline:hover { background: #fff; color: #000; }
        .btn-fill { background: var(--accent); color: #000 !important; }
        .scanline { position: fixed; inset: 0; background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.2) 50%); background-size: 100% 4px; pointer-events: none; z-index: 100; }
        .scroll-hint { position: absolute; bottom: 50px; left: 50%; transform: translateX(-50%); font-size: 9px; letter-spacing: 10px; opacity: 0.4; text-align: center; }

        /* 4. SECTION STATS */
        .club-stats-section { background: #fff; position: relative; overflow: hidden; color: #111; }
        .stats-flex { display: flex; min-height: 600px; }
        .stats-left { 
            flex: 1.2; background: #fff; padding: 80px 8%; z-index: 5; 
            display: flex; flex-direction: column; justify-content: center;
            clip-path: polygon(0 0, 100% 0, 88% 100%, 0% 100%);
        }
        .stats-header-box { display: flex; align-items: center; gap: 25px; margin-bottom: 30px; }
        .stats-number { font-family: 'Anton', sans-serif; font-size: 140px; line-height: 1; color: var(--accent); font-style: italic; }
        .stats-title { font-family: 'Oswald', sans-serif; font-weight: 900; font-size: 55px; line-height: 0.9; text-transform: uppercase; font-style: italic; color: #1a1a1a; }
        .stats-text { max-width: 550px; font-size: 19px; line-height: 1.6; color: #444; }
        .stats-text strong { color: #000; font-weight: 800; }
        .stats-link { display: inline-block; margin-top: 30px; color: #111; font-weight: 700; text-decoration: none; border-bottom: 2px solid #111; padding-bottom: 5px; transition: 0.3s; }
        .stats-link:hover { color: var(--accent); border-color: var(--accent); }
        .stats-right-img { 
            flex: 1; background-image: url(' https://images.unsplash.com/photo-1593079831268-3381b0db4a77?q=80&w=2069'); 
            background-size: cover; background-position: center; margin-left: -10%; 
        }

        /* 5. Section PLANS */
        .tactical-armor-interface { background: #050505; position: relative; z-index: 10; font-family: 'Oswald', sans-serif; padding-top: 100px; padding-bottom: 100px; }
        .anton-font { font-family: 'Anton', sans-serif; }
        .italic-text { font-style: italic; }
        .text-yellow { color: var(--accent); }
        .uppercase { text-transform: uppercase; }
        .hud-tag { font-size: 10px; font-weight: 900; letter-spacing: 3px; color: rgba(255,255,255,0.4); }
        .status-blink { display: inline-block; width: 8px; height: 8px; background: var(--accent); border-radius: 50%; margin-right: 10px; animation: blink 1.5s infinite; }
        .hud-line-container { display: flex; align-items: center; justify-content: center; margin-top: 15px; }
        .line-left, .line-right { height: 1px; background: rgba(255,255,255,0.1); width: 100px; }
        
        .armor-card { background: var(--dark-card); border: 1px solid var(--border-color); position: relative; overflow: hidden; transition: 0.4s; height: 100%; display: flex; flex-direction: column; clip-path: polygon(0 0, 92% 0, 100% 8%, 100% 100%, 8% 100%, 0 92%); }
        .armor-card:hover { transform: translateY(-10px); border-color: var(--accent); box-shadow: 0 30px 60px rgba(0,0,0,0.8); }
        .armor-featured { border: 1px solid var(--accent); }
        .armor-header { padding: 15px 20px; font-size: 10px; font-weight: 900; color: rgba(255,255,255,0.3); }
        .promo-glitch { color: #000; background: var(--accent); padding: 2px 10px; }
        .plan-name { font-size: 45px; line-height: 1; margin: 0; letter-spacing: 1px; }
        .armor-divider { width: 50px; height: 5px; background: var(--accent); margin-top: 10px; }
        .armor-body { padding: 30px; flex-grow: 1; display: flex; flex-direction: column; }
        .armor-price-section { background: rgba(255,255,255,0.02); padding: 25px; border: 1px solid rgba(255,255,255,0.03); }
        .price-val { font-size: 70px; line-height: 0.8; margin: 0; }
        .btn-armor-deploy { background: var(--accent); color: #000; text-decoration: none; display: block; padding: 20px; text-align: center; font-size: 16px; font-weight: 900; clip-path: polygon(5% 0%, 100% 0%, 95% 100%, 0% 100%); transition: 0.3s; }
        .btn-armor-deploy:hover { background: #fff; transform: scale(1.02); }

        /* 6. GRID SECTION */
        .fitness-command-grid { position: relative; background: #000; overflow: hidden; border-top: 1px solid var(--border-color); }
        .grid-wrap { display: flex; flex-wrap: wrap; min-height: 100vh; }
        .grid-cell { 
            flex: 0 0 50%; height: 50vh; position: relative; overflow: hidden; 
            display: flex; align-items: center; justify-content: center; text-decoration: none;
            border: 0.5px solid rgba(255,255,255,0.05);
        }
        .cell-bg { 
            position: absolute; inset: 0; background-size: cover; background-position: center; 
            filter: grayscale(1) brightness(0.3); transition: 0.8s cubic-bezier(0.16, 1, 0.3, 1); 
        }
        .grid-cell:hover .cell-bg { filter: grayscale(0) brightness(0.5); transform: scale(1.08); }
        .cell-txt { position: relative; z-index: 2; text-align: center; color: #fff; font-family: 'Anton', sans-serif; font-style: italic; text-transform: uppercase; line-height: 0.85; }
        .cell-txt h2 { font-size: clamp(2rem, 5vw, 5rem); margin: 0; letter-spacing: -2px; }
        .yellow-part { color: var(--accent); }
        .burning-park-border { border: 4px solid #fff; padding: 15px 40px; display: inline-block; }

        .middle-cross { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 20; pointer-events: none; }
        .cross-h { width: 100px; height: 1px; background: var(--accent); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .cross-v { height: 100px; width: 1px; background: var(--accent); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .cross-c { width: 140px; height: 140px; border: 1px solid rgba(255, 237, 0, 0.2); border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .cross-d { width: 12px; height: 12px; background: var(--accent); border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 0 0 20px var(--accent); }

        /* 7. CONTACT SECTION */
        .tactical-contact { background: #030303; padding: 100px 0; position: relative; z-index: 5; }
        .contact-box { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid var(--border-color); padding: 50px; clip-path: polygon(0 0, 95% 0, 100% 5%, 100% 100%, 5% 100%, 0 95%); }
        .contact-title { font-family: 'Anton', sans-serif; font-style: italic; font-size: 60px; line-height: 1; letter-spacing: -2px; }
        .form-label-custom { font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; margin-bottom: 5px; }
        .form-control-custom { background: rgba(255,255,255,0.02) !important; border: 1px solid var(--border-color) !important; color: #fff !important; border-radius: 0 !important; padding: 15px !important; font-size: 14px; transition: 0.3s; }
        .form-control-custom:focus { border-color: var(--accent) !important; outline: none; box-shadow: 0 0 15px rgba(255,237,0,0.1); }
        .btn-submit-tactical { background: var(--accent); border: none; color: #000; font-family: 'Anton', sans-serif; font-size: 18px; text-transform: uppercase; padding: 15px 40px; clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%); transition: 0.3s; width: 100%; max-width: 200px; }
        .btn-submit-tactical:hover { background: #fff; transform: scale(1.05); }
        .btn-club-tactical { background: #222; border: 1px solid var(--border-color); color: var(--accent); font-family: 'Anton', sans-serif; padding: 15px 40px; text-decoration: none; display: inline-block; clip-path: polygon(0 0, 90% 0, 100% 100%, 10% 100%); transition: 0.3s; }
        .btn-club-tactical:hover { background: var(--accent); color: #000; }

        /* 8. FOOTER SECTION (BHAL TSOWIRA) */
        .tactical-footer { background: #0c0c0c; border-top: 1px solid var(--border-color); padding: 80px 0 40px 0; position: relative; z-index: 5; }
        .footer-header { font-size: 14px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; }
        .footer-sep { color: var(--accent); font-weight: 100; font-size: 20px; }
        .footer-list { list-style: none; padding: 0; margin: 0; }
        .footer-list li { margin-bottom: 12px; }
        .footer-list a { text-decoration: none; color: rgba(255,255,255,0.5); font-size: 13px; transition: 0.3s; }
        .footer-list a:hover { color: var(--accent); padding-left: 5px; }
        
        .footer-brand-side { text-align: right; }
        .footer-logo { font-family: 'Syncopate'; font-size: 1.8rem; letter-spacing: 5px; margin-bottom: 10px; }
        .footer-tagline { font-size: 9px; letter-spacing: 4px; color: rgba(255,255,255,0.3); text-transform: uppercase; margin-bottom: 30px; }
        .footer-socials { display: flex; gap: 20px; justify-content: flex-end; }
        .social-icon { width: 45px; height: 45px; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: 0.3s; clip-path: polygon(15% 0%, 100% 0%, 85% 100%, 0% 100%); }
        .social-icon:hover { background: var(--accent); color: #000; border-color: var(--accent); transform: translateY(-5px); }
        
        .back-to-top { position: absolute; bottom: 40px; right: 50px; width: 50px; height: 50px; background: #1a1a1a; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: 0.3s; clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%); cursor: pointer; }
        .back-to-top:hover { background: var(--accent); color: #000; }

        @media (max-width: 991px) {
            .stats-flex { flex-direction: column; }
            .stats-left { clip-path: none; padding: 60px 20px; text-align: center; }
            .grid-cell { flex: 0 0 100%; height: 40vh; }
            .middle-cross { display: none; }
            .contact-title { font-size: 40px; }
            .footer-brand-side { text-align: center; margin-top: 50px; }
            .footer-socials { justify-content: center; }
            .back-to-top { position: relative; bottom: 0; right: 0; margin: 40px auto; }
        }
    </style>
</head>
<body>

    <!-- Loader -->
    <div id="loader">
        <div class="boot-text mb-2" id="boot-status">INITIALISATION_EN_COURS...</div>
        <div style="width: 200px; height: 1px; background: rgba(255,255,255,0.1);">
            <div id="boot-progress" style="width: 0%; height: 100%; background: var(--accent);"></div>
        </div>
        <div class="mt-4" style="font-family: 'Syncopate'; font-size: 1.5rem; letter-spacing: 10px;">FIT <span style="opacity: 0.3;">PRO</span></div>
    </div>

    <div id="custom-cursor"></div>
    <div id="cursor-aura"></div>
    <div class="scanline"></div>

    <nav>
        <div class="brand">FIT<span style="color: var(--accent);">PRO</span></div>
        <div class="auth-cluster">
            <a href="{{ route('login') }}" class="btn-outline">Connexion</a>
            <a href="{{ route('register') }}" class="btn-fill">Rejoindre </a>
        </div>
    </nav>

    <!-- SECTION 1: WELCOME -->
    <main class="stage" id="parallax-scene">
        <div class="bg-layer" id="bg"></div>
        <div class="glass-hud hud-1" id="h1">
            <div style="font-size: 8px; opacity: 0.5;">[ LATENCE_SYSTÈME: 0.02ms ]</div>
            <div class="mt-2 fw-bold" style="font-size: 10px;">ÉTAT_NOYAU: OPTIMAL</div>
        </div>
        <div class="main-content">
            <h1 class="hero-title" id="title-1">PASSEZ AU</h1>
            <h1 class="hero-title accent-text" id="title-2">NIVEAU SUPÉRIEUR</h1>
        </div>
        <div class="glass-hud hud-2" id="h2">
            <div style="font-size: 8px; opacity: 0.5;">[ PROTOCOLE_INSCRIPTION ]</div>
            <p class="mt-2" style="font-size: 11px; line-height: 1.6;">Découvrez l'excellence de la performance.</p>
        </div>
        <div class="scroll-hint uppercase">Explorer la plateforme <br> <i class="fas fa-chevron-down mt-2"></i></div>
    </main>

    <!-- SECTION 2: CLUB STATS -->
    <section class="club-stats-section">
        <div class="stats-flex">
            <div class="stats-left">
                <div class="stats-header-box">
                    <span class="stats-number">150+</span>
                    <div class="stats-title">CENTRES DE<br>HAUTE PERFORMANCE</div>
                </div>
                <div class="stats-text">
                    <p>Rejoignez un réseau d'élite de plus de <strong>500 000 membres FIT PRO</strong>. Nous ne sommes pas une simple salle de sport, nous sommes un écosystème conçu pour ceux qui exigent l'excellence.</p>
                </div>
                <a href="#" class="stats-link">Localiser votre centre de commandement</a>
            </div>
            <div class="stats-right-img"></div>
        </div>
    </section>

    <!-- SECTION 3: PLANS -->
    <section class="tactical-armor-interface">
        <div class="container-fluid px-lg-5">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <div class="hud-tag mb-2"><span class="status-blink"></span> SYSTÈME D'ACCRÉDITATION ACTIF</div>
                    <h1 class="display-4 fw-900 text-white m-0 anton-font italic-text uppercase">UNITÉS DE <span class="text-yellow">PUISSANCE</span></h1>
                    <div class="hud-line-container"><div class="line-left"></div><i class="fas fa-crosshairs mx-3 text-yellow"></i><div class="line-right"></div></div>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($plans as $plan)
                @php $isPromo = preg_match('/(promo|ramadan|offre|special)/i', $plan->nom); @endphp
                <div class="col-xl-4 col-lg-6">
                    <div class="armor-card {{ $isPromo ? 'armor-featured' : '' }}">
                        <div class="armor-header d-flex justify-content-between align-items-center">
                            <span>REF: PRT-{{ 100 + $plan->id }}</span>
                            @if($isPromo) <span class="promo-glitch">OFFRE ÉLITE</span> @endif
                        </div>
                        <div class="armor-title-block px-4">
                            <h2 class="plan-name anton-font italic-text text-white">{{ strtoupper($plan->nom) }}</h2>
                            <div class="armor-divider"></div>
                        </div>
                        <div class="armor-body">
                            <div class="armor-price-section text-center mb-4">
                                <div class="d-flex align-items-center justify-content-center">
                                    <h1 class="price-val anton-font italic-text text-white">{{ number_format($plan->prix, 0) }}</h1>
                                    <div class="ms-2 text-start">
                                        <span class="text-yellow d-block fw-bold">DHS</span>
                                        <span style="font-size: 10px; opacity: 0.4;">/TOT</span>
                                    </div>
                                </div>
                                <div class="mt-2 text-yellow fw-bold uppercase" style="font-size: 10px;">{{ $plan->duree_mois }} MOIS ACCÈS</div>
                            </div>
                            <div class="features mb-4" style="font-size: 13px; opacity: 0.7;">
                                @if($plan->description)
                                    @foreach(explode("\n", str_replace("\r", "", $plan->description)) as $line)
                                        @if(trim($line)) <div class="mb-2"><i class="fas fa-caret-right text-yellow me-2"></i>{{ strtoupper(trim($line)) }}</div> @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('client.checkout', $plan->id) }}" class="btn-armor-deploy anton-font">DÉPLOYER LE PROTOCOLE</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION 4: COMMAND GRID -->
    <section class="fitness-command-grid">
        <div class="middle-cross">
            <div class="cross-h"></div>
            <div class="cross-v"></div>
            <div class="cross-c"></div>
            <div class="cross-d"></div>
        </div>
       <div class="grid-wrap">
    <!-- رابط صفحة Concept -->
    <a href="{{ route('concept') }}" class="grid-cell">
        <div class="cell-bg" style="background-image: url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1?q=80&w=2070');"></div>
        <div class="cell-txt"><h2 class="yellow-part">CONCEPT</h2><h2 class="text-white">FIT PRO</h2></div>
    </a>

    <!-- رابط صفحة Cardio -->
    <a href="{{ route('cardio') }}" class="grid-cell">
        <div class="cell-bg" style="background-image: url('https://images.unsplash.com/photo-1534258936925-c58bed479fcb?q=80&w=2070');"></div>
        <div class="cell-txt"><h2 class="yellow-part">CARDIO</h2><h2 class="text-white">ENTRAÎNEMENT </h2></div>
    </a>

    <!-- رابط صفحة Burning Park -->
    <a href="{{ route('burning') }}" class="grid-cell">
        <div class="cell-bg" style="background-image: url('https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=2069');"></div>
        <div class="cell-txt burning-park-border"><h2 class="text-white">ZONE HAUTE  </h2><h2 class="text-white">INTENSITÉ</h2></div>
    </a>

    <!-- رابط صفحة Cours Collectifs -->
    <a href="{{ route('collectifs') }}" class="grid-cell">
        <div class="cell-bg" style="background-image: url('https://images.unsplash.com/photo-1571388208497-71bedc66e932?q=80&w=2072');"></div>
        <div class="cell-txt"><h2 class="yellow-part">COURS</h2><h2 class="text-white">COLLECTIFS</h2></div>
    </a>
</div>
    </section>

    <!-- SECTION 5: CONTACT -->
    <section class="tactical-contact">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="contact-title text-white uppercase">FIT <span class="text-yellow">PRO</span><br>CENTRES D'ÉLITE</h2>
                    <p class="mt-4" style="opacity: 0.6; line-height: 1.8; font-size: 18px;">
                        Avec votre accès <strong>FIT PRO</strong>, vous débloquez l'entrée à l'ensemble de nos centres de performance, ouverts de <strong>06h00 à 23h00 non-stop</strong>, 7j/7, 365 jours par an.
                    </p>
                    <div class="mt-5">
                        <a href="#" class="btn-club-tactical">TROUVER UN CENTRE <i class="fas fa-crosshairs ms-2"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-box">
                        <div class="text-center mb-5">
                            <h4 class="anton-font italic-text m-0" style="letter-spacing: 2px;">BESOIN D'UN RENSEIGNEMENT ?</h4>
                            <div class="hud-tag" style="font-size: 9px; opacity: 0.4;">[ ENVOYEZ VOTRE REQUÊTE AU CENTRE ]</div>
                        </div>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label-custom">Nom *</label><input type="text" class="form-control form-control-custom" required></div>
                                <div class="col-md-6"><label class="form-label-custom">Prénom *</label><input type="text" class="form-control form-control-custom" required></div>
                                <div class="col-12"><label class="form-label-custom">E-mail *</label><input type="email" class="form-control form-control-custom" required></div>
                                <div class="col-12"><label class="form-label-custom">Téléphone *</label><input type="tel" class="form-control form-control-custom" required></div>
                                <div class="col-12"><label class="form-label-custom">Message *</label><textarea class="form-control form-control-custom" rows="4" required></textarea></div>
                                <div class="col-12 mt-4 text-center"><button type="submit" class="btn-submit-tactical">ENVOYER</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER SECTION (BHAL TSOWIRA) -->
    <footer class="tactical-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-header">Rejoindre Fit Pro Group <span class="footer-sep">|</span></div>
                    <ul class="footer-list">
                        <li><a href="#">Proposer un local</a></li>
                        <li><a href="#">Recrutement</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-header">Aide & Infos <span class="footer-sep">|</span></div>
                    <ul class="footer-list">
                        <li><a href="#">Abonnements</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-header">Centres <span class="footer-sep">|</span></div>
                    <ul class="footer-list">
                        <li><a href="#">Ain Sebaa</a></li>
                        <li><a href="#">Casablanca Racine</a></li>
                        <li><a href="#">Mohammedia Plaza</a></li>
                        <li><a href="#">Rabat Arribat Center</a></li>
                        <li><a href="#">Rabat Carrousel</a></li>
                        <li><a href="#">Roudani</a></li>
                        <li><a href="#">Salé</a></li>
                        <li><a href="#">Sidi CFC</a></li>
                        <li><a href="#">Sidi Maarouf</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-header">Activités <span class="footer-sep">|</span></div>
                    <ul class="footer-list">
                        <li><a href="#">Fight Park</a></li>
                        <li><a href="#">Burning Park</a></li>
                        <li><a href="#">Cycle Park</a></li>
                        <li><a href="#">Cross training</a></li>
                        <li><a href="#">Cardio training</a></li>
                        <li><a href="#">Cours collectifs</a></li>
                        <li><a href="#">Musculation</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 footer-brand-side">
                    <div class="footer-logo">FIT<span style="color: var(--accent);">PRO</span></div>
                    <p class="footer-tagline">SE DÉPASSER - SE SURPASSER</p>
                    <div class="footer-socials">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5 pt-4 border-top border-secondary opacity-25">
                <div class="col text-center">
                    <p style="font-size: 10px; color: #666;">&copy; 2024 FIT PRO ELITE NETWORK. TOUS DROITS RÉSERVÉS.</p>
                </div>
            </div>
        </div>

        <div class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
            <i class="fas fa-chevron-up"></i>
        </div>
    </footer>

    <!-- JS: GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        const statusArr = ["CONNEXION...", "DÉCHIFFREMENT...", "PRÊT."];
        let statusIdx = 0;
        setInterval(() => {
            if(statusIdx < statusArr.length) document.getElementById('boot-status').innerText = statusArr[statusIdx++];
        }, 500);

        window.addEventListener('load', () => {
            const tl = gsap.timeline();
            tl.to("#boot-progress", { width: "100%", duration: 1.2 })
              .to("#loader", { y: "-100%", duration: 1, ease: "expo.inOut" })
              .from("#bg", { scale: 1.3, duration: 2 }, "-=0.5");
        });

        const cursor = document.getElementById('custom-cursor');
        const aura = document.getElementById('cursor-aura');
        document.addEventListener('mousemove', (e) => {
            gsap.to(cursor, { x: e.clientX, y: e.clientY, duration: 0.1 });
            gsap.to(aura, { x: e.clientX, y: e.clientY, duration: 0.3 });
            
            if(window.scrollY < window.innerHeight) {
                const x = (e.clientX - window.innerWidth / 2) / 50;
                const y = (e.clientY - window.innerHeight / 2) / 50;
                gsap.to("#bg", { x: x, y: y, duration: 2 });
            }
        });
    </script>
</body>
</html>