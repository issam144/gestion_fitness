<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | PROTOCOLE D'INSCRIPTION</title>
    
    <!-- CSS Links -->
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
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100% 100%, 40px 40px, 40px 40px;
        }

        /* Carte Terminal Principale */
        .register-terminal {
            background: var(--panel-bg);
            border: 1px solid var(--border-tactical);
            border-radius: 20px;
            width: 100%; max-width: 520px;
            overflow: hidden;
            box-shadow: 0 50px 100px rgba(0,0,0,0.9);
            position: relative;
            backdrop-filter: blur(10px);
        }

        /* Lueur de Bordure Supérieure */
        .register-terminal::before {
            content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            box-shadow: 0 0 15px var(--accent);
        }

        .terminal-header {
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-tactical);
            padding: 25px;
            text-align: center;
            position: relative;
        }

        /* Indicateur de version */
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

        /* Étiquettes de Formulaire */
        .tiny-caps {
            font-size: 9px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1.5px; color: rgba(255,255,255,0.4);
            margin-bottom: 8px; display: block;
        }

        /* Style des Champs */
        .form-control-tactical {
            background: rgba(0,0,0,0.4) !important;
            border: 1px solid rgba(255,255,255,0.06) !important;
            border-radius: 10px !important;
            color: #fff !important;
            padding: 12px 16px !important;
            font-weight: 600 !important; font-size: 13px !important;
            transition: 0.3s ease;
        }
        .form-control-tactical:focus {
            border-color: var(--accent) !important;
            background: #000 !important;
            box-shadow: 0 0 20px rgba(255, 237, 0, 0.08) !important;
            outline: none;
        }

        /* Style du Bouton */
        .btn-enlist {
            background: var(--accent); color: #000;
            border: none; width: 100%; padding: 16px;
            border-radius: 12px; font-weight: 900; font-size: 13px;
            text-transform: uppercase; letter-spacing: 2px;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 15px;
        }
        .btn-enlist:hover {
            background: #fff; transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.1);
        }

        /* Style d'Alerte */
        .status-alert {
            background: rgba(255, 237, 0, 0.03);
            border: 1px solid rgba(255, 237, 0, 0.1);
            border-radius: 10px; padding: 12px;
            margin-bottom: 25px;
        }

        .footer-link {
            text-align: center; margin-top: 25px;
            font-size: 10px; color: rgba(255,255,255,0.3);
            text-transform: uppercase; letter-spacing: 1px;
        }
        .footer-link a {
            color: #fff; text-decoration: none; font-weight: 800;
            border-bottom: 1px solid var(--accent); padding-bottom: 1px;
            margin-left: 5px; transition: 0.3s;
        }
        .footer-link a:hover { color: var(--accent); }

        /* Animation du Point de Statut */
        .status-dot {
            width: 7px; height: 7px; background: var(--accent);
            border-radius: 50%; display: inline-block; margin-right: 6px;
            box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite;
        }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>

    <div class="register-terminal">
        <div class="sys-id">B-VERS: 4.0.2 // CANAL_SÉCURISÉ</div>
        
        <div class="terminal-header">
            <div class="brand-logo">FIT<span>PRO</span></div>
            <div class="protocol-status">
                <span class="status-dot"></span> PROTOCOLE_INSCRIPTION: NIVEAU 01
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <div class="text-center mb-5">
                <h5 class="fw-900 uppercase letter-spacing-1 m-0">CRÉER UN COMPTE</h5>
                <p class="text-secondary" style="font-size: 11px; opacity: 0.6;">IDENTIFIEZ-VOUS AUPRÈS DU CENTRE DE COMMANDE</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="tiny-caps">Nom Complet</label>
                    <input type="text" name="name" class="form-control-tactical w-100" placeholder="ex. PIERRE MARTIN" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="tiny-caps">Adresse E-mail</label>
                    <input type="email" name="email" class="form-control-tactical w-100" placeholder="contact@fitpro.com" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="tiny-caps">Mot de Passe</label>
                        <input type="password" name="password" class="form-control-tactical w-100" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="tiny-caps">Confirmer le Mot de Passe</label>
                        <input type="password" name="password_confirmation" class="form-control-tactical w-100" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="status-alert">
                    <div class="d-flex">
                        <i class="fas fa-shield-alt text-accent me-3 mt-1" style="font-size: 12px;"></i>
                        <p class="m-0 text-secondary" style="font-size: 9px; line-height: 1.4;">
                            AVIS SYSTÈME: Votre demande sera mise en attente de <span class="text-white fw-bold">VALIDATION</span>. 
                            L'accès sera activé après confirmation par l'administrateur.
                        </p>
                    </div>
                </div>

                <button type="submit" class="btn-enlist shadow-lg">
                    <i class="fas fa-bolt me-2"></i> CRÉER MON COMPTE
                </button>
                
                <div class="footer-link">
                    DÉJÀ INSCRIT ? <a href="{{ route('login') }}">SE CONNECTER</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>