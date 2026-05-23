@extends('layouts.app')

@section('page_title', 'OUTREPASSEMENT DE SÉCURITÉ')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="col-md-5">
        
        <!-- Affichage des Erreurs Système -->
        @if ($errors->any())
            <div class="alert-terminal-danger mb-4 uppercase fw-900 letter-spacing-xs">
                <i class="fas fa-exclamation-triangle me-2"></i> ÉCHEC D'AUTHENTIFICATION
                <ul class="mt-2 mb-0 px-3 small opacity-80">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="console-terminal shadow-lg border-accent-dim">
            <div class="card-header-line"></div>
            
            <div class="p-5">
                <div class="text-center mb-5">
                    <!-- Hexagon Security Icon -->
                    <div class="user-avatar-hex mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt text-accent" style="z-index: 5; font-size: 30px;"></i>
                        <svg class="hex-svg" viewBox="0 0 100 100">
                            <polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" />
                        </svg>
                        <div class="scan-line-mini"></div>
                    </div>
                    
                    <h3 class="fw-900 text-white uppercase letter-spacing-sm">MISE À JOUR DE SÉCURITÉ</h3>
                    <p class="tiny-caps text-accent fw-700">CONNEXION INITIALE DÉTECTÉE. MODIFIEZ VOTRE CODE D'ACCÈS.</p>
                </div>

                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="label-tactical uppercase fw-700 letter-spacing-xs">Nouveau Code de Sécurité</label>
                        <div class="input-group-tactical">
                            <i class="fas fa-key icon-prefix"></i>
                            <input type="password" name="password" class="form-control input-tactical" placeholder="MIN. 8 CARACTÈRES" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="label-tactical uppercase fw-700 letter-spacing-xs">Confirmer le Code</label>
                        <div class="input-group-tactical">
                            <i class="fas fa-lock icon-prefix"></i>
                            <input type="password" name="password_confirmation" class="form-control input-tactical" placeholder="RÉPÉTER LE CODE..." required>
                        </div>
                    </div>

                    <button type="submit" class="btn-authorize w-100 uppercase fw-900 letter-spacing-sm py-3">
                        <span>ACTIVER LE NOUVEAU CODE</span>
                        <i class="fas fa-bolt ms-2"></i>
                    </button>
                </form>
            </div>
            
            <div class="console-footer px-4 py-2 text-center">
                <span class="tiny-caps opacity-40 fw-700">SYSTÈME DE CRYPTAGE MILITAIRE ACTIF</span>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { 
        --accent: #ffed00; 
        --panel-bg: #0d0d0d; 
        --body-bg: #050505;
        --border-tactical: rgba(255, 255, 255, 0.05);
    }
    
    body { background: var(--body-bg); font-family: 'Oswald', sans-serif; }

    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 800; letter-spacing: 2px; }
    .text-accent { color: var(--accent); }

    /* Console Style */
    .console-terminal { background: var(--panel-bg); border-radius: 15px; border: 1px solid var(--border-tactical); overflow: hidden; position: relative; }
    .card-header-line { height: 4px; background: var(--accent); width: 100%; box-shadow: 0 0 15px var(--accent); }
    .console-footer { background: rgba(0,0,0,0.4); border-top: 1px solid var(--border-tactical); }

    /* Hexagon */
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 8px rgba(255,237,0,0.4)); }
    
    /* Inputs */
    .label-tactical { color: rgba(255,255,255,0.4); margin-bottom: 8px; display: block; }
    .input-group-tactical { position: relative; }
    .icon-prefix { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--accent); opacity: 0.7; z-index: 5; }
    .input-tactical { 
        background: #000 !important; border: 1px solid #222 !important; color: #fff !important; 
        border-radius: 8px !important; padding: 15px 15px 15px 45px !important; transition: 0.3s;
        font-family: 'Oswald', sans-serif; width: 100%;
    }
    .input-tactical:focus { border-color: var(--accent) !important; box-shadow: 0 0 20px rgba(255,237,0,0.1) !important; outline: none; }

    /* Button */
    .btn-authorize { 
        background: var(--accent); color: #000; border: none; border-radius: 8px; transition: 0.4s; cursor: pointer;
    }
    .btn-authorize:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }

    /* Alerts */
    .alert-terminal-danger { background: rgba(255, 0, 0, 0.05); color: #ff4d4d; border: 1px solid rgba(255, 0, 0, 0.2); padding: 15px; border-radius: 8px; }

    /* Animation Scan */
    .scan-line-mini { position: absolute; width: 100%; height: 2px; background: rgba(255,237,0,0.5); top: 0; animation: scanMove 2s infinite linear; z-index: 4; }
    @keyframes scanMove { 0% { top: 20%; } 100% { top: 80%; } }
</style>
@endsection