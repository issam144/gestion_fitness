<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | CARDIO TRAINING UNIT</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oswald:wght@300;700&family=Space+Grotesk:wght@300;700&display=swap" rel="stylesheet">

    <style>
        :root { --accent: #ffed00; --bg: #030303; --card-bg: #111; }
        
        body { background: var(--bg); color: #fff; font-family: 'Space Grotesk', sans-serif; overflow-x: hidden; }
        .anton { font-family: 'Anton', sans-serif; text-transform: uppercase; font-style: italic; }
        .text-yellow { color: var(--accent); }

        /* Hero Cardio */
        .hero-cardio { 
            height: 70vh; position: relative; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)), 
                        url('https://images.unsplash.com/photo-1534258936925-c58bed479fcb?q=80&w=2070');
            background-size: cover; background-position: center; border-bottom: 4px solid var(--accent);
        }
        .hero-title { font-size: clamp(3rem, 10vw, 8rem); line-height: 0.8; z-index: 2; text-shadow: 10px 10px 20px rgba(0,0,0,0.5); }

        .section-padding { padding: 100px 0; }
        .bg-black { background: #000; }
        .bg-white-custom { background: #ffffff; color: #000; }
        
        /* Image Style - Elite */
        .img-elite { 
            width: 100%; height: 400px; background-size: cover; background-position: center; 
            border: 1px solid rgba(255,255,255,0.1); position: relative;
            box-shadow: 20px 20px 0px var(--accent);
            transition: 0.5s ease-in-out;
        }
        .bg-white-custom .img-elite { box-shadow: 20px 20px 0px #000; }
        .img-elite:hover { transform: translate(-5px, -5px); box-shadow: 30px 30px 0px var(--accent); }

        .hud-line { width: 70px; height: 5px; background: var(--accent); margin-bottom: 25px; }
        .bg-white-custom .hud-line { background: #000; }

        .btn-back {
            background: var(--accent); color: #000; font-family: 'Anton';
            padding: 15px 45px; text-decoration: none; display: inline-block;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%); transition: 0.3s;
        }
        .btn-back:hover { background: #fff; transform: scale(1.05); color: #000; }

        /* Description below image layout */
        .answer-box { margin-top: 40px; border-left: 2px solid var(--accent); padding-left: 25px; }
        .bg-white-custom .answer-box { border-left: 2px solid #000; }
    </style>
</head>
<body>

    <!-- HERO SECTION -->
    <section class="hero-cardio">
        <div class="text-center">
            <h1 class="hero-title anton text-white">CARDIO</h1>
            <h1 class="hero-title anton text-yellow">TRAINING</h1>
        </div>
    </section>

    <!-- SECTION 1: RENFORCEZ-VOUS -->
    <section class="section-padding bg-black">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hud-line"></div>
                    <h2 class="anton display-4 mb-0">RENFORCEZ-<span class="text-yellow">VOUS</span></h2>
                </div>
                <div class="col-lg-5">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1594911772125-07fc7a2d8d9f?q=80&w=2070');"></div>
                </div>
                <div class="col-12">
                    <div class="answer-box">
                        <p class="lead opacity-75">L'entraînement cardio chez FIT PRO n'est pas seulement une question de transpiration. C'est une reprogrammation complète de votre système cardiovasculaire. En augmentant votre capacité pulmonaire et la force de votre cœur, vous bâtissez une base indestructible pour toutes vos autres activités physiques.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: POUR QUI ? -->
    <section class="section-padding bg-white-custom">
        <div class="container">
            <div class="row align-items-center flex-row-reverse g-5">
                <div class="col-lg-7 text-lg-end">
                    <div class="hud-line ms-auto"></div>
                    <h2 class="anton display-4 mb-0">POUR <span style="color: #000;">QUI ?</span></h2>
                </div>
                <div class="col-lg-5">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1594882645126-14020914d58d?q=80&w=2085');"></div>
                </div>
                <div class="col-12">
                    <div class="answer-box">
                        <p class="lead"><strong>Absolument tout le monde.</strong> Que vous soyez un athlète cherchant à optimiser son endurance, un débutant souhaitant perdre du poids, ou un senior voulant protéger sa santé cardiaque. Nos équipements Technogym s'adaptent à tous les niveaux avec une précision millimétrée.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: POUR QUOI ? -->
    <section class="section-padding bg-black">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hud-line"></div>
                    <h2 class="anton display-4 mb-0">POUR <span class="text-yellow">QUOI ?</span></h2>
                </div>
                <div class="col-lg-5">
                    <div class="img-elite" style="background-image: url('https://images.unsplash.com/photo-1538805060514-97d9cc17730c?q=80&w=2070');"></div>
                </div>
                <div class="col-12">
                    <div class="answer-box">
                        <p class="lead opacity-75">Pour maximiser le brûlage calorique (jusqu'à 800 kcal/heure), améliorer la récupération musculaire, réduire le stress et booster votre énergie quotidienne. Le cardio est le carburant de votre performance globale. C'est le moteur qui permet à la machine de fonctionner plus longtemps et plus fort.</p>
                        <div class="mt-5">
                            <a href="/" class="btn-back">RETOUR AU CENTRE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 text-center opacity-25">
        <p class="anton">© 2024 FIT PRO ELITE NETWORK | UNITÉ CARDIO</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);
        
        // Hero Animation
        gsap.from(".hero-title", { y: 100, opacity: 0, duration: 1.2, stagger: 0.3 });
        
        // Scroll Animations for images and answer boxes
        gsap.utils.toArray(".img-elite").forEach(img => {
            gsap.from(img, {
                scrollTrigger: {
                    trigger: img,
                    start: "top 85%",
                },
                x: -50,
                opacity: 0,
                duration: 1,
                ease: "power2.out"
            });
        });

        gsap.utils.toArray(".answer-box").forEach(box => {
            gsap.from(box, {
                scrollTrigger: {
                    trigger: box,
                    start: "top 90%",
                },
                y: 30,
                opacity: 0,
                duration: 0.8,
                ease: "power2.out"
            });
        });
    </script>
</body>
</html>