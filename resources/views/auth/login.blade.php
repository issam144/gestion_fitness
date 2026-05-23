<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | AUTORISATION D'ACCÈS</title>
    
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
                radial-gradient(at 100% 100%, rgba(255, 237, 0, 0.08) 0px, transparent 50%),
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 100% 100%, 40px 40px, 40px 40px;
        }

        /* Carte Terminal Principale */
        .login-terminal {
            background: var(--panel-bg);
            border: 1px solid var(--border-tactical);
            border-radius: 20px;
            width: 100%; max-width: 480px;
            overflow: hidden;
            box-shadow: 0 50px 100px rgba(0,0,0,0.9);
            position: relative;
            backdrop-filter: blur(10px);
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* Lueur de Bordure Supérieure */
        .login-terminal::before {
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

        .sys-id {
            position: absolute; top: 10px; right: 15px;
            font-size: 7px; color: rgba(255,255,255,0.2);
            font-family: monospace; letter-spacing: 1px;
        }

        .brand-logo {
            font-family: 'Oswald', sans-serif;
            font-size: 2.2rem; letter-spacing: 5px; font-weight: 800;
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

        /* Style du Formulaire */
        .tiny-caps {
            font-size: 9px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 1.5px; color: rgba(255,255,255,0.4);
            margin-bottom: 8px; display: block;
        }

        .form-control-tactical {
            background: rgba(0,0,0,0.4) !important;
            border: 1px solid rgba(255,255,255,0.06) !important;
            border-radius: 10px !important;
            color: #fff !important;
            padding: 14px 18px !important;
            font-weight: 600 !important; font-size: 14px !important;
            transition: 0.3s ease;
        }
        .form-control-tactical:focus {
            border-color: var(--accent) !important;
            background: #000 !important;
            box-shadow: 0 0 20px rgba(255, 237, 0, 0.08) !important;
            outline: none;
        }

        .btn-authorize {
            background: var(--accent); color: #000;
            border: none; width: 100%; padding: 18px;
            border-radius: 12px; font-weight: 900; font-size: 14px;
            text-transform: uppercase; letter-spacing: 2px;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 15px;
        }
        .btn-authorize:hover {
            background: #fff; transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.1);
        }

        /* Lien Mot de Passe Oublié */
        .forgot-link {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            color: rgba(255,255,255,0.4); text-decoration: none;
            transition: 0.3s;
        }
        .forgot-link:hover { color: var(--accent); }

        .footer-link {
            text-align: center; margin-top: 30px;
            font-size: 11px; color: rgba(255,255,255,0.3);
            text-transform: uppercase; letter-spacing: 1px;
        }
        .footer-link a {
            color: #fff; text-decoration: none; font-weight: 800;
            border-bottom: 1px solid var(--accent); padding-bottom: 1px;
            margin-left: 5px;
        }

        .error-tactical { color: #ff3e3e; font-size: 11px; font-weight: 700; margin-top: 8px; }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>

    <div class="login-terminal">
        <div class="sys-id">A-LOG: 2.1.0 // PORTAIL_AUTH</div>
        
        <div class="terminal-header">
            <div class="brand-logo">FIT<span>PRO</span></div>
            <div class="protocol-status">
                <span class="status-dot"></span> AUTORISATION_ACCÈS: NIVEAU_3
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <div class="text-center mb-5">
                <h5 class="fw-900 uppercase letter-spacing-1 m-0">Connexion</h5>
                <p class="text-secondary" style="font-size: 11px; opacity: 0.6;">IDENTIFICATION REQUISE POUR ACCÉDER</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Adresse E-mail -->
                <div class="mb-4">
                    <label class="tiny-caps">Adresse E-mail</label>
                    <input type="email" name="email" class="form-control-tactical w-100" placeholder="contact@fitpro.com" value="{{ old('email') }}" required autofocus>
                    @error('email') <div class="error-tactical"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</div> @enderror
                </div>

                <!-- Mot de Passe -->
                <div class="mb-2">
                    <label class="tiny-caps">Mot de Passe</label>
                    <input type="password" name="password" class="form-control-tactical w-100" placeholder="••••••••" required>
                    @error('password') <div class="error-tactical"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</div> @enderror
                </div>

                <!-- Mot de Passe Oublié -->
                <div class="text-end mb-4">
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        <i class="fas fa-key me-1"></i> Mot de passe oublié ?
                    </a>
                </div>

                <button type="submit" class="btn-authorize shadow-lg">
                    <i class="fas fa-fingerprint me-2"></i> Se Connecter
                </button>
                
                <div class="footer-link">
                    PAS ENCORE INSCRIT ? <a href="{{ route('register') }}">CRÉER UN COMPTE</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>