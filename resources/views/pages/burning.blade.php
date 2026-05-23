<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | ZONE BURNING PARK</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@300;700&family=Space+Grotesk:wght@300;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-v2.png') }}?v={{ time() }}">

    <style>
        :root { --accent: #ffed00; --bg: #030303; --card-bg: #111; --danger: #ff0000; }
        
        body { background: var(--bg); color: #fff; font-family: 'Space Grotesk', sans-serif; overflow-x: hidden; }
        .anton { font-family: 'Anton', sans-serif; text-transform: uppercase; font-style: italic; }
        .text-yellow { color: var(--accent); }

        /* Hero Burning - Dark & Intense */
        .hero-burning { 
            height: 80vh; position: relative; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)), 
                        url('https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=2069');
            background-size: cover; background-position: center; border-bottom: 4px solid var(--danger);
        }
        .hero-title { font-size: clamp(3rem, 10vw, 8rem); line-height: 0.8; z-index: 2; text-shadow: 10px 10px 30px rgba(255,0,0,0.3); }

        .section-padding { padding: 100px 0; }
        .bg-black { background: #000; }
        .bg-dark-grey { background: #080808; }
        
        /* Elite Image Layout */
        .img-elite { 
            width: 100%; height: 450px; background-size: cover; background-position: center; 
            border: 1px solid rgba(255,255,255,0.1); position: relative;
            box-shadow: 20px 20px 0px var(--accent);
            transition: 0.5s ease-in-out;
        }
        .img-elite:hover { transform: translate(-5px, -5px); box-shadow: 30px 30px 0px var(--accent); }

        .hud-line { width: 70px; height: 5px; background: var(--accent); margin-bottom: 25px; }
        .answer-box { margin-top: 40px; border-left: 2px solid var(--accent); padding-left: 25px; }

        /* Advantages Grid */
        .adv-card { background: #0c0c0c; border: 1px solid rgba(255,255,255,0.05); padding: 30px; height: 100%; transition: 0.3s; clip-path: polygon(0 0, 90% 0, 100% 10%, 100% 100%, 10% 100%, 0 90%); }
        .adv-card:hover { border-color: var(--accent); background: #111; }
        .adv-icon { font-size: 30px; color: var(--accent); margin-bottom: 20px; }

        /* Pricing Badge */
        .price-badge { background: var(--accent); color: #000; padding: 20px 40px; display: inline-block; font-family: 'Anton'; font-size: 2rem; clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%); }

        .btn-back {
            background: var(--accent); color: #000; font-family: 'Anton';
            padding: 15px 45px; text-decoration: none; display: inline-block;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%); transition: 0.3s;
        }
        .btn-back:hover { background: #fff; transform: scale(1.05); color: #000; }
    </style>
</head>
<body>

    <!-- HERO SECTION -->
    <section class="hero-burning text-center">
        <div>
            <div class="mb-3 anton" style="letter-spacing: 5px; opacity: 0.7;">ZONE HAUTE INTENSITÉ</div>
            <h1 class="hero-title anton text-white">BURNING</h1>
            <h1 class="hero-title anton text-yellow">PARK</h1>
            <p class="mt-4 lead anton" style="letter-spacing: 2px;">La nouvelle expérience fitness en totale immersion</p>
        </div>
    </section>

    <!-- SECTION 1: LE CONCEPT -->
    <section class="section-padding bg-black">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hud-line"></div>
                    <h2 class="anton display-4 mb-0">DÉPASSE TES <span class="text-yellow">LIMITES</span></h2>
                </div>
                <div class="col-lg-5">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070');"></div>
                </div>
                <div class="col-12">
                    <div class="answer-box">
                        <p class="lead opacity-75">Le Burning Park est un espace fermé pensé pour te faire vivre une nouvelle expérience fitness. Grâce à des sessions basées sur l'entraînement fractionné (HIIT) et le contrôle de ta fréquence cardiaque, sculpte ton corps et brûle un maximum de calories. Tu seras équipé d'un cardio-fréquencemètre pour optimiser chaque effort à la pulsation près.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: LES AVANTAGES -->
    <section class="section-padding bg-dark-grey">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="anton display-5 text-yellow">LES AVANTAGES BURNING PARK</h2>
                <div class="hud-line mx-auto mt-3"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="adv-card">
                        <i class="fas fa-fire adv-icon"></i>
                        <h4 class="anton">EFFICACITÉ MAXIMALE</h4>
                        <p class="opacity-50">Un entraînement jusqu’à 4x plus efficace qu’un cardio traditionnel, avec des effets qui durent jusqu’à 36h.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="adv-card">
                        <i class="fas fa-users adv-icon"></i>
                        <h4 class="anton">COACHING ÉLITE</h4>
                        <p class="opacity-50">Un coaching en petit groupe mixant cardio et renforcement musculaire sur différents ateliers intensifs.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="adv-card">
                        <i class="fas fa-heartbeat adv-icon"></i>
                        <h4 class="anton">SUIVI BIOMÉTRIQUE</h4>
                        <p class="opacity-50">Contrôle ton effort en temps réel avec ton cardiofréquencemètre fourni par ton coach.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: ACCÈS & PRIX -->
    <section class="section-padding bg-black">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hud-line"></div>
                    <h2 class="anton display-4 mb-4">ACCÈDE À <span class="text-yellow">L'ESPACE</span></h2>
                    <p class="mb-4 opacity-75">En accès libre de 06h à 23h ou en cours collectif encadré (45 minutes de haute intensité). Réserve ta place sur ton espace adhérent (24 places max par session).</p>
                    
                    <div class="mt-4 mb-5">
                        <div class="anton mb-2" style="letter-spacing: 2px; opacity: 0.5;">PASS ILLIMITÉ</div>
                        <div class="price-badge">200 DHS / MOIS</div>
                        <p class="mt-2 text-yellow anton small">* Ton premier mois est offert !</p>
                    </div>

                    <a href="/" class="btn-back">RETOUR AU CENTRE</a>
                </div>
                <div class="col-lg-5">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1549476464-37392f717541?q=80&w=2070');"></div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 text-center opacity-25">
        <p class="anton">© 2024 FIT PRO ELITE NETWORK | BURNING PARK SYSTEM</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);
        gsap.from(".hero-title", { y: 100, opacity: 0, duration: 1.2, stagger: 0.3 });
        
        gsap.utils.toArray(".adv-card").forEach((card, i) => {
            gsap.from(card, {
                scrollTrigger: { trigger: card, start: "top 90%" },
                y: 50, opacity: 0, duration: 0.6, delay: i * 0.1
            });
        });

        gsap.from(".price-badge", {
            scrollTrigger: { trigger: ".price-badge", start: "top 90%" },
            scale: 0, rotation: -10, duration: 1, ease: "back.out(1.7)"
        });
    </script>
</body>
</html>