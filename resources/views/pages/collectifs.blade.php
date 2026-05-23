<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | NOS DISCIPLINES</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@700&family=Space+Grotesk:wght@300;700&family=Anton&family=Oswald:wght@300;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-v2.png') }}?v={{ time() }}">

    <style>
        :root { --accent: #ffed00; --bg: #050505; --border-color: rgba(255, 255, 255, 0.2); }
        
        body { margin: 0; background: var(--bg); color: #fff; font-family: 'Space Grotesk', sans-serif; overflow-x: hidden; cursor: none; }

        /* Custom Cursor */
        #custom-cursor { position: fixed; width: 10px; height: 10px; background: var(--accent); border-radius: 50%; pointer-events: none; z-index: 10000; box-shadow: 0 0 15px var(--accent); }
        #cursor-aura { position: fixed; width: 45px; height: 45px; border: 1px solid var(--accent); border-radius: 50%; pointer-events: none; z-index: 9999; transform: translate(-50%, -50%); transition: 0.15s ease-out; }

        /* Navigation */
        nav { position: fixed; top: 0; width: 100%; padding: 30px 50px; z-index: 1000; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(to bottom, rgba(0,0,0,0.7), transparent); }
        .brand { font-family: 'Syncopate'; font-size: 1.2rem; letter-spacing: 8px; text-decoration: none; color: #fff; }
        .btn-back { font-size: 10px; font-weight: 900; color: var(--accent); text-decoration: none; border: 1px solid var(--accent); padding: 12px 25px; clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%); transition: 0.3s; }
        .btn-back:hover { background: var(--accent); color: #000; }

        /* Header Section */
        .section-header { padding-top: 140px; padding-bottom: 40px; text-align: center; }
        .hud-tag { font-size: 10px; font-weight: 900; letter-spacing: 5px; color: var(--accent); text-transform: uppercase; margin-bottom: 15px; }
        .page-title { font-family: 'Anton', sans-serif; font-style: italic; font-size: clamp(3rem, 10vw, 7rem); text-transform: uppercase; line-height: 0.8; margin: 0; }

        /* Bright Grid System */
        .activities-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
            padding: 40px 0;
        }

        .grid-item {
            position: relative;
            height: 50vh;
            overflow: hidden;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: flex-end;
            padding: 30px;
            text-decoration: none;
            clip-path: polygon(0 0, 95% 0, 100% 5%, 100% 100%, 5% 100%, 0 95%);
            transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .top-row { grid-column: span 3; }
        .bottom-row { grid-column: span 2; }

        .item-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            /* --- تعديل الوضوح هنا: حيدنا Grayscale ورجعنا Brightness لـ 1 --- */
            filter: brightness(1) grayscale(0); 
            transition: 0.8s ease;
            z-index: 1;
        }

        /* Hover Effect: كيزيد يضوي الصورة كتر */
        .grid-item:hover .item-bg {
            filter: brightness(1.1); 
            transform: scale(1.08);
        }

        .grid-item:hover {
            border-color: var(--accent);
            box-shadow: 0 0 30px rgba(255, 237, 0, 0.2);
        }

        /* التدرج التحتاني نقصت من السواد ديالو باش ميبانش المربع مظلم من التحت */
        .grid-item::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 40%);
            z-index: 2;
        }

        .item-content {
            position: relative;
            z-index: 5;
            width: 100%;
        }

        .item-title {
            font-family: 'Anton', sans-serif;
            font-size: clamp(2rem, 4vw, 3.5rem);
            line-height: 0.9;
            margin: 0;
            text-transform: uppercase;
            font-style: italic;
            /* ظل خفيف للعنوان باش يبان وخا الصورة مضوية */
            text-shadow: 2px 2px 15px rgba(0,0,0,0.7);
        }

        .yellow-part { color: var(--accent); }
        .white-part { color: #fff; }

        .item-desc {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #fff;
            text-transform: uppercase;
            margin-top: 12px;
            display: block;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.8);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .top-row, .bottom-row { grid-column: span 6; height: 40vh; }
        }
    </style>
</head>
<body>

    <div id="custom-cursor"></div>
    <div id="cursor-aura"></div>

    <nav>
        <a href="/" class="brand">FIT<span style="color: var(--accent);">PRO</span></a>
        <a href="/" class="btn-back"><i class="fas fa-chevron-left me-2"></i> RETOUR</a>
    </nav>

    <div class="container-fluid px-lg-5">
        <header class="section-header">
            <div class="hud-tag">[ SYSTÈME D'UNITÉS ACTIF ]</div>
            <h1 class="page-title text-white">CHOISISSEZ VOTRE <span class="text-yellow">FORCE</span></h1>
        </header>

        <div class="activities-grid">
            
            <a href="#" class="grid-item top-row">
                <div class="item-bg" style="background-image: url('https://images.unsplash.com/photo-1517838277536-f5f99be501cd?q=80&w=2070');"></div>
                <div class="item-content">
                    <h2 class="item-title"><span class="white-part">CROSS</span><span class="yellow-part">FIT</span></h2>
                    <span class="item-desc">Dépassement total & intensité brute</span>
                </div>
            </a>

            <a href="#" class="grid-item top-row">
                <div class="item-bg" style="background-image: url('https://images.unsplash.com/photo-1597452485669-2c7bb5fef90d?q=80&w=2070');"></div>
                <div class="item-content">
                    <h2 class="item-title"><span class="white-part">MUSCUL</span><span class="yellow-part">ATION</span></h2>
                    <span class="item-desc">Puissance athlétique & volume</span>
                </div>
            </a>

            <a href="#" class="grid-item bottom-row">
                <div class="item-bg" style="background-image: url('https://images.unsplash.com/photo-1519315901367-f34ff9154487?q=80&w=2070');"></div>
                <div class="item-content">
                    <h2 class="item-title"><span class="white-part">NATA</span><span class="yellow-part">TION</span></h2>
                    <span class="item-desc">Endurance aquatique & cardio</span>
                </div>
            </a>

            <a href="#" class="grid-item bottom-row">
                <div class="item-bg" style="background-image: url('https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=2020');"></div>
                <div class="item-content">
                    <h2 class="item-title"><span class="yellow-part">YO</span><span class="white-part">GA</span></h2>
                    <span class="item-desc">Mobilité & contrôle du corps</span>
                </div>
            </a>

            <a href="#" class="grid-item bottom-row">
                <div class="item-bg" style="background-image: url('https://images.unsplash.com/photo-1534258936925-c58bed479fcb?q=80&w=2070');"></div>
                <div class="item-content">
                    <h2 class="item-title"><span class="white-part">CAR</span><span class="yellow-part">DIO</span></h2>
                    <span class="item-desc">Vitesse & santé cardio-vasculaire</span>
                </div>
            </a>

        </div>
    </div>

    <footer class="py-5 text-center opacity-50">
        <p class="anton" style="letter-spacing: 5px;">FIT PRO ELITE NETWORK</p>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        const cursor = document.getElementById('custom-cursor');
        const aura = document.getElementById('cursor-aura');
        
        document.addEventListener('mousemove', (e) => {
            gsap.to(cursor, { x: e.clientX, y: e.clientY, duration: 0 });
            gsap.to(aura, { x: e.clientX, y: e.clientY, duration: 0.2 });
        });
    </script>
</body>
</html>