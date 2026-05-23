<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | CONCEPT COMMAND</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@300;700&family=Space+Grotesk:wght@300;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-v2.png') }}?v={{ time() }}">

    <style>
        :root { --accent: #ffed00; --bg: #030303; --card-bg: #111; }
        
        body { background: var(--bg); color: #fff; font-family: 'Space Grotesk', sans-serif; overflow-x: hidden; }
        .anton { font-family: 'Anton', sans-serif; text-transform: uppercase; font-style: italic; }
        .text-yellow { color: var(--accent); }

        .hero-concept { 
            height: 70vh; position: relative; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), 
                        url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1?q=80&w=2070');
            background-size: cover; background-position: center; border-bottom: 4px solid var(--accent);
        }
        .hero-title { font-size: clamp(3rem, 10vw, 8rem); line-height: 0.8; z-index: 2; text-shadow: 10px 10px 20px rgba(0,0,0,0.5); }

        .section-padding { padding: 100px 0; }
        .bg-black { background: #000; }
        .bg-white-custom { background: #ffffff; color: #000; }
        
        .img-elite { 
            width: 100%; height: 500px; background-size: cover; background-position: center; 
            border: 1px solid rgba(255,255,255,0.1); position: relative;
            box-shadow: 20px 20px 0px var(--accent);
            transition: 0.5s ease-in-out;
        }
        .bg-white-custom .img-elite { box-shadow: 20px 20px 0px #000; }
        .img-elite:hover { transform: translate(-10px, -10px); box-shadow: 35px 35px 0px var(--accent); }

        .hud-line { width: 70px; height: 5px; background: var(--accent); margin-bottom: 25px; }
        .bg-white-custom .hud-line { background: #000; }

        .btn-back {
            background: var(--accent); color: #000; font-family: 'Anton';
            padding: 15px 45px; text-decoration: none; display: inline-block;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%); transition: 0.3s;
        }
        .btn-back:hover { background: #fff; transform: scale(1.05); color: #000; }

        .faq-card { background: #0a0a0a; border: 1px solid #222; padding: 25px; margin-bottom: 15px; }
        .faq-card h4 { font-family: 'Anton'; color: var(--accent); font-size: 1.2rem; }
    </style>
</head>
<body>

    <!-- HERO SECTION -->
    <section class="hero-concept">
        <div class="text-center">
            <h1 class="hero-title anton text-white">LE <span class="text-yellow">CONCEPT</span></h1>
            <h1 class="hero-title anton text-white">COMMAND</h1>
        </div>
    </section>

    <!-- SECTION 1: ESPACES (MUSCULATION) -->
    <section class="section-padding bg-black">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hud-line"></div>
                    <h2 class="anton display-4 mb-4">ESPACES <span class="text-yellow">PREMIUM</span></h2>
                    <p class="lead opacity-75 mb-4">Plus de 1000m² dédiés à la performance pure. Chaque zone est optimisée pour dépasser vos limites.</p>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-check-circle text-yellow me-2"></i> ZONE MUSCULATION POIDS LIBRES</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-yellow me-2"></i> CARDIO TRAINING DERNIÈRE GÉNÉRATION</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-yellow me-2"></i> CROSS-TRAINING TACTIQUE</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-yellow me-2"></i> COURS COLLECTIFS COACHÉS</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&q=80&w=1200');"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: MATERIEL (EQUIPEMENT) -->
    <section class="section-padding bg-white-custom">
        <div class="container">
            <div class="row align-items-center flex-row-reverse g-5">
                <div class="col-lg-6">
                    <div class="hud-line"></div>
                    <h2 class="anton display-4 mb-4">MATÉRIEL DE <span style="text-decoration: line-through;">QUALITÉ</span> ELITE</h2>
                    <p class="lead mb-4">Partenariat exclusif avec <strong>Technogym</strong> et <strong>Hammer Strength</strong>. Le meilleur de l'ingénierie sportive au service de votre corps.</p>
                    <p>Des machines pensées pour l'ergonomie et la puissance maximale.</p>
                </div>
                <div class="col-lg-6">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1593079831268-3381b0db4a77?auto=format&fit=crop&q=80&w=1200');"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: ACCES (NON-STOP) -->
    <section class="section-padding bg-black">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hud-line"></div>
                    <h2 class="anton display-4 mb-4">ACCÈS <span class="text-yellow">NON-STOP</span></h2>
                    <h3 class="anton h2 mb-4">06H00 - 23H00 | 7J/7</h3>
                    <p class="lead opacity-75 mb-5">Votre carte FIT PRO vous ouvre les portes de tous les clubs du réseau. Entraînez-vous 365 jours par an, sans aucune contrainte.</p>
                    <a href="/" class="btn-back">RETOUR AU CENTRE</a>
                </div>
                <div class="col-lg-6">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&q=80&w=1200');"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="section-padding">
        <div class="container">
            <h2 class="anton text-center display-5 mb-5 text-yellow">QUESTIONS & RÉPONSES</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="faq-card">
                        <h4>Quelles sont les activités ?</h4>
                        <p class="mb-0 opacity-50">Musculation, Cardio, Cross-training, HIIT, Boxing et Yoga.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="faq-card">
                        <h4>Accès à tous les clubs ?</h4>
                        <p class="mb-0 opacity-50">Oui, votre abonnement FIT PRO est national.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 text-center opacity-25">
        <p class="anton">© 2024 FIT PRO ELITE NETWORK</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);
        gsap.from(".hero-title", { y: 100, opacity: 0, duration: 1.2, stagger: 0.3 });
        
        gsap.utils.toArray(".img-elite").forEach(img => {
            gsap.from(img, {
                scrollTrigger: {
                    trigger: img,
                    start: "top 90%",
                },
                scale: 0.8,
                opacity: 0,
                duration: 1,
                ease: "power2.out"
            });
        });
    </script>
</body>
</html>