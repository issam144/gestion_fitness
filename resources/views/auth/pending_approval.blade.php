<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | CONFIRMATION</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-v2.png') }}?v={{ time() }}">

    <style>
        :root { 
            --accent: #ffed00; 
            --bg-deep: #050505;
            --panel-bg: #0d0d0d;
            --border-tactical: rgba(255, 255, 255, 0.07);
        }

        body { 
            margin: 0; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-deep); 
            color: #fff; 
            min-height: 100vh; 
            display: flex; align-items: center; justify-content: center;
            overflow-x: hidden; padding: 20px;
        }

        /* Fond Tactique avec Grille */
        .mesh-bg { 
            position: fixed; inset: 0; z-index: -2; 
            background-color: #050505;
            background-image: 
                radial-gradient(at 0% 0%, rgba(255, 237, 0, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(255, 237, 0, 0.05) 0px, transparent 50%),
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 40px 40px, 40px 40px;
        }

        /* Carte Terminal Principale */
        .confirm-terminal {
            background: var(--panel-bg);
            border: 1px solid var(--border-tactical);
            border-radius: 20px;
            width: 100%; max-width: 520px;
            overflow: hidden;
            box-shadow: 0 50px 100px rgba(0,0,0,0.9);
            position: relative;
            backdrop-filter: blur(10px);
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* Lueur de Bordure Supérieure */
        .confirm-terminal::before {
            content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            box-shadow: 0 0 15px var(--accent);
        }

        /* En-tête */
        .terminal-header {
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-tactical);
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .sys-id {
            position: absolute; top: 10px; right: 15px;
            font-size: 7px; color: rgba(255,255,255,0.2);
            font-family: monospace; letter-spacing: 1px;
        }

        .brand-logo {
            font-family: 'Oswald', sans-serif;
            font-size: 2rem; letter-spacing: 5px; font-weight: 800;
        }
        .brand-logo span { color: var(--accent); }

        .protocol-status {
            font-size: 9px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 2px; color: var(--accent); margin-top: 5px;
        }

        .status-dot {
            width: 7px; height: 7px; background: var(--accent);
            border-radius: 50%; display: inline-block; margin-right: 6px;
            box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite;
        }

        /* Icône de Succès */
        .icon-box {
            width: 80px; height: 80px;
            background: rgba(255, 237, 0, 0.05);
            border: 1px solid rgba(255, 237, 0, 0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 0 30px rgba(255, 237, 0, 0.1);
            animation: bounce 2s infinite;
        }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        .icon-box i { color: var(--accent); font-size: 35px; }

        /* Titre */
        .confirm-title {
            font-family: 'Oswald', sans-serif;
            font-size: 2rem; font-weight: 800;
            letter-spacing: 3px; text-transform: uppercase;
            margin-bottom: 15px;
        }
        .confirm-title span { color: var(--accent); }

        /* Message */
        .confirm-message {
            font-size: 13px; line-height: 1.8;
            color: rgba(255,255,255,0.6);
            margin-bottom: 35px;
        }

        /* Ligne de Séparation */
        .divider {
            border: none;
            border-top: 1px solid var(--border-tactical);
            margin: 25px 0;
        }

        /* Bouton */
        .btn-home {
            background: var(--accent); color: #000;
            border: none; width: 100%; padding: 16px;
            border-radius: 12px; font-weight: 900; font-size: 13px;
            text-transform: uppercase; letter-spacing: 2px;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none; display: block; text-align: center;
        }
        .btn-home:hover {
            background: #fff; color: #000; transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.1);
        }

        /* Pied de page */
        .footer-text {
            text-align: center; margin-top: 20px;
            font-size: 9px; color: rgba(255,255,255,0.2);
            text-transform: uppercase; letter-spacing: 2px;
            font-family: monospace;
        }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>

    <div class="confirm-terminal">
        <div class="sys-id">STATUS: 200 // INSCRIPTION_VALIDÉE</div>

        <div class="terminal-header">
            <div class="brand-logo">FIT<span>PRO</span></div>
            <div class="protocol-status">
                <span class="status-dot"></span> STATUT_INSCRIPTION: CONFIRMÉ
            </div>
        </div>

        <div class="p-4 p-lg-5 text-center">

            <div class="icon-box">
                <i class="fas fa-check"></i>
            </div>

            <div class="confirm-title">FÉLICITATIONS <span>!</span></div>

            <div class="confirm-message">
                Ta préinscription a bien été prise en compte.<br>
                Tu seras contacté rapidement par ton club pour programmer<br>
                un rendez-vous afin de finaliser ton inscription.
            </div>

            <hr class="divider">

            <a href="{{ route('login') }}" class="btn-home">
                <i class="fas fa-fingerprint me-2"></i> Accéder à mon compte
            </a>

            <div class="footer-text mt-4">
                Préparez-vous à rejoindre l'élite // FITPRO_SYS
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>