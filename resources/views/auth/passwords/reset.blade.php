<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | NOUVELLE CLÉ D'ACCÈS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-v2.png') }}?v={{ time() }}">

    <style>
        :root { --accent: #ffed00; --bg-deep: #050505; --panel-bg: #0d0d0d; }
        body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-deep); color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow-x: hidden; padding: 20px; }
        .mesh-bg { position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(at 0% 0%, rgba(255, 237, 0, 0.08) 0px, transparent 50%), linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px); background-size: 100% 100%, 40px 40px, 40px 40px; }
        .login-terminal { background: var(--panel-bg); border: 1px solid rgba(255, 255, 255, 0.07); border-radius: 25px; width: 100%; max-width: 480px; overflow: hidden; position: relative; box-shadow: 0 50px 100px rgba(0,0,0,0.9); }
        .login-terminal::before { content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); box-shadow: 0 0 15px var(--accent); }
        .terminal-header { background: rgba(255, 255, 255, 0.02); border-bottom: 1px solid rgba(255, 255, 255, 0.07); padding: 25px; text-align: center; }
        .brand-logo { font-family: 'Oswald', sans-serif; font-size: 2.2rem; letter-spacing: 5px; font-weight: 800; }
        .brand-logo span { color: var(--accent); }
        .tiny-caps { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.4); margin-bottom: 8px; display: block; }
        .form-control-tactical { background: rgba(0,0,0,0.4) !important; border: 1px solid rgba(255,255,255,0.06) !important; border-radius: 10px !important; color: #fff !important; padding: 14px 18px !important; font-weight: 600 !important; font-size: 14px !important; transition: 0.3s; }
        .form-control-tactical:focus { border-color: var(--accent) !important; background: #000 !important; box-shadow: 0 0 20px rgba(255, 237, 0, 0.08) !important; outline: none; }
        .btn-authorize { background: var(--accent); color: #000; border: none; width: 100%; padding: 18px; border-radius: 12px; font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; transition: 0.4s; margin-top: 15px; }
        .btn-authorize:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }
        .user-avatar-hex { position: relative; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>
    <div class="login-terminal">
        <div class="terminal-header">
            <div class="brand-logo">FIT<span>PRO</span></div>
            <div style="font-size: 9px; color: var(--accent); letter-spacing: 2px; font-weight: 800; margin-top: 5px;">PORTAIL_RÉCUPÉRATION: ACTIF</div>
        </div>
        <div class="p-4 p-lg-5">
            <div class="text-center mb-5">
                <div class="user-avatar-hex">
                    <i class="fas fa-key text-accent" style="z-index: 2; font-size: 20px;"></i>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                </div>
                <h5 class="fw-900 uppercase m-0">Créer un Nouveau Mot de Passe</h5>
                <p class="text-secondary small mt-1">Définissez vos nouveaux identifiants de sécurité</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label class="tiny-caps">Adresse E-mail</label>
                    <input type="email" name="email" class="form-control-tactical w-100" value="{{ $email ?? old('email') }}" required readonly>
                </div>

                <div class="mb-4">
                    <label class="tiny-caps">Nouveau Mot de Passe</label>
                    <input type="password" name="password" class="form-control-tactical w-100 @error('password') is-invalid @enderror" required placeholder="••••••••">
                    @error('password') <div class="text-danger small mt-2 fw-bold">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="tiny-caps">Confirmer le Nouveau Mot de Passe</label>
                    <input type="password" name="password_confirmation" class="form-control-tactical w-100" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn-authorize shadow-lg">
                    <i class="fas fa-key me-2"></i> Enregistrer le Nouveau Mot de Passe
                </button>
            </form>
        </div>
    </div>
</body>
</html>