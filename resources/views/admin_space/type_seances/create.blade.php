@extends('layouts.app')

@section('page_title', 'DÉPLOIEMENT DE DISCIPLINE')

@section('content')
<div class="container-fluid py-4 px-lg-5">

    <!-- 1. En-tête (Header) -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-900 text-white mb-1 uppercase letter-spacing-lg">NOUVELLE <span style="color: var(--accent);">DISCIPLINE</span></h1>
            <p class="text-secondary small uppercase letter-spacing-sm" style="font-size: 10px;">Initialisation d'une nouvelle catégorie dans la base Elite</p>
        </div>
        <a href="{{ route('admin.type-seances.index') }}" class="elite-btn-back uppercase fw-900 letter-spacing-sm">
            <i class="fas fa-arrow-left me-2"></i> RETOUR À L'ARCHIVE
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">

            <!-- Terminal Card System -->
            <div class="edit-terminal">

                <!-- En-tête du Terminal -->
                <div class="terminal-top-bar">
                    <div class="brand-logo fw-900 uppercase letter-spacing-xs">FIT<span>PRO</span></div>
                    <div class="protocol-status uppercase fw-900 letter-spacing-xs">
                        <span class="status-dot"></span> PROTOCOLE_DISCIPLINE : CONFIGURATION
                    </div>
                    <div class="sys-id fw-900 letter-spacing-xs"></div>
                </div>

                <!-- Section Identity Bar -->
                <div class="coach-identity-bar">
                    <div class="avatar-hex">
                        <i id="icon-preview" class="fas fa-plus-circle text-white" style="z-index: 2; font-size: 1.5rem;"></i>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <div class="ms-4">
                        <h5 class="m-0 fw-900 text-white uppercase letter-spacing-sm">ENREGISTREMENT UNITÉ</h5>
                        <span class="text-secondary uppercase letter-spacing-xs" style="font-size: 9px;">
                            STATUT : <span style="color: var(--accent);">SÉLECTION DES PARAMÈTRES VISUELS</span>
                        </span>
                    </div>
                </div>

                <!-- Formulaire Section -->
                <div class="p-4 p-lg-5">
                    @if ($errors->any())
                        <div class="alert bg-danger bg-opacity-10 text-danger border-0 rounded-3 mb-5 small fw-900 uppercase letter-spacing-xs">
                            <i class="fas fa-exclamation-triangle me-2"></i> Erreur Système : Paramètres invalides
                        </div>
                    @endif

                    <form action="{{ route('admin.type-seances.store') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="row g-4">

                            <!-- Désignation de la Discipline -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">NOM DE LA DISCIPLINE</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-tag"></i>
                                    <input type="text" name="nom" placeholder="EX: MUSCULATION, ZUMBA..." required autofocus class="uppercase fw-900">
                                </div>
                            </div>

                            <!-- Sélection de l'Icône -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">CHOIX DE L'ICÔNE TACTIQUE</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-icons"></i>
                                    <select name="icon" id="icon-select" required class="uppercase fw-900">
                                        <option value="" disabled selected>CHOISIR UNE ICÔNE...</option>
                                        <option value="fa-dumbbell">🏋️ MUSCULATION (DUMBBELL)</option>
                                        <option value="fa-bolt">⚡ ZUMBA / ÉNERGIE (BOLT)</option>
                                        <option value="fa-heartbeat">❤️ CARDIO (HEARTBEAT)</option>
                                        <option value="fa-fire">🔥 CROSSFIT (FIRE)</option>
                                        <option value="fa-fist-raised">🥊 BOXE / COMBAT (FIST)</option>
                                        <option value="fa-leaf">🧘 YOGA / BIEN-ÊTRE (LEAF)</option>
                                        <option value="fa-running">🏃 ATHLÉTISME (RUNNING)</option>
                                        <option value="fa-bicycle">🚴 SPINNING (BICYCLE)</option>
                                        <option value="fa-stopwatch">⏱️ HIIT / CHRONO (STOPWATCH)</option>
                                        <option value="fa-medal">🏆 COMPÉTITION (MEDAL)</option>
                                        <option value="fa-user-shield">🛡️ DÉFENSE (SHIELD)</option>
                                        <option value="fa-swimmer">🏊 NATATION (SWIMMER)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bouton Submit -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn-submit uppercase fw-900 letter-spacing-sm">
                                    <i class="fas fa-check-double me-2"></i> CONFIRMER LE DÉPLOIEMENT
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; --input-bg: rgba(0,0,0,0.4); --border-tactical: rgba(255,255,255,0.07); }

    body { background: #050505; font-family: 'Oswald', sans-serif; }
    a { text-decoration: none !important; }

    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    
    .tiny-caps { font-size: 11px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.4); display: block; }

    .edit-terminal { background: var(--panel-bg); border: 1px solid var(--border-tactical); border-radius: 20px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.9); position: relative; }
    .edit-terminal::before { content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); box-shadow: 0 0 15px var(--accent); }

    .terminal-top-bar { background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-tactical); padding: 20px 30px; display: flex; align-items: center; justify-content: space-between; }
    .brand-logo { font-size: 1.4rem; color: #fff; }
    .brand-logo span { color: var(--accent); }
    .protocol-status { font-size: 9px; color: var(--accent); }
    .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite; }
    .sys-id { font-size: 8px; color: rgba(255,255,255,0.2); font-family: monospace; }

    .coach-identity-bar { background: rgba(255,255,255,0.01); border-bottom: 1px solid var(--border-tactical); padding: 25px 35px; display: flex; align-items: center; }
    .avatar-hex { position: relative; width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }

    .neo-input-group { background: var(--input-bg); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 12px 18px; display: flex; align-items: center; transition: 0.3s; }
    .neo-input-group:focus-within { border-color: var(--accent); box-shadow: 0 0 20px rgba(255,237,0,0.08); }
    .neo-input-group i { color: var(--accent); margin-right: 15px; font-size: 14px; width: 20px; text-align: center; }
    
    .neo-input-group input, .neo-input-group select { background: transparent !important; border: none; color: #fff !important; width: 100%; outline: none; font-size: 14px; font-family: 'Oswald', sans-serif; cursor: pointer; }
    .neo-input-group select option { background: #111; color: #fff; }

    .btn-submit { background: var(--accent); color: #000; border: none; width: 100%; padding: 20px; border-radius: 12px; transition: 0.4s; cursor: pointer; }
    .btn-submit:hover { background: #fff; transform: translateY(-3px); }

    .elite-btn-back { color: rgba(255,255,255,0.4); font-size: 11px; transition: 0.3s; }
    .elite-btn-back:hover { color: var(--accent); }

    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
</style>

<script>
    // Animation de preview de l'icône
    const select = document.getElementById('icon-select');
    const preview = document.getElementById('icon-preview');

    select.addEventListener('change', function() {
        const iconClass = this.value;
        preview.className = `fas ${iconClass} text-white`;
        preview.style.animation = 'none';
        preview.offsetHeight; // trigger reflow
        preview.style.animation = 'pulse 0.5s';
    });
</script>
@endsection