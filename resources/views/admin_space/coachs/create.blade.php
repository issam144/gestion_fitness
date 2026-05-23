@extends('layouts.app')

@section('page_title', 'AJOUTER UN COACH')

@section('content')
<div class="container-fluid py-4 px-lg-5">

    <!-- 1. En-tête (Header) -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-900 text-white mb-1 uppercase letter-spacing-lg">NOUVEAU <span style="color: var(--accent);">COACH</span></h1>
            <p class="text-secondary small uppercase letter-spacing-sm" style="font-size: 10px;">Initialisation des informations et identifiants du coach</p>
        </div>
        <a href="{{ route('admin.coachs.index') }}" class="elite-btn-back uppercase fw-900 letter-spacing-sm">
            <i class="fas fa-arrow-left me-2"></i> RETOUR À LA LISTE
        </a>
    </div>

    <div class="row">
        <div class="col-12">

            <!-- Terminal Card -->
            <div class="edit-terminal">

                <!-- En-tête du Terminal -->
                <div class="terminal-top-bar">
                    <div class="brand-logo fw-900 uppercase letter-spacing-xs">FIT<span>PRO</span></div>
                    <div class="protocol-status uppercase fw-700 letter-spacing-xs">
                        <span class="status-dot"></span> NOUVEAU_COACH : CONFIGURATION_PROTOCOLE
                    </div>
                    <div class="sys-id fw-900 letter-spacing-xs"></div>
                </div>

                <!-- Section Titre Identity -->
                <div class="coach-identity-bar">
                    <div class="icon-hex">
                        <i class="fas fa-user-plus"></i>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <div class="ms-4">
                        <h5 class="m-0 fw-900 text-white uppercase letter-spacing-sm">CRÉATION DU PROFIL</h5>
                        <span class="text-secondary uppercase letter-spacing-xs" style="font-size: 9px;">REMPLISSEZ LES INFORMATIONS CI-DESSOUS</span>
                    </div>
                </div>

                <!-- Formulaire Section -->
                <div class="p-4 p-lg-5">
                    <form action="{{ route('admin.coachs.store') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="row g-4">

                            <!-- Nom Complet -->
                            <div class="col-md-4">
                                <label class="tiny-caps letter-spacing-sm">NOM COMPLET DU COACH</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-id-card"></i>
                                    <input type="text" name="name" placeholder="NOM DU COACH..." required class="uppercase fw-900 letter-spacing-xs">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <label class="tiny-caps letter-spacing-sm">ADRESSE E-MAIL OPÉRATIONNELLE</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" name="email" placeholder="COACH@FITPRO.COM..." required class="uppercase fw-900 letter-spacing-xs">
                                </div>
                            </div>

                            <!-- Téléphone -->
                            <div class="col-md-4">
                                <label class="tiny-caps letter-spacing-sm">CONTACT TÉLÉPHONIQUE</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-phone-alt"></i>
                                    <input type="text" name="telephone" placeholder="06XXXXXXXX..." required class="uppercase fw-900 letter-spacing-xs">
                                </div>
                            </div>

                            <!-- Spécialisation Selection -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">DISCIPLINE / SPÉCIALISATION</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-dumbbell"></i>
                                    <select name="type_seance_id" required class="uppercase fw-900 letter-spacing-xs">
                                        <option value="" disabled selected style="background:#111; color:#fff;">SÉLECTIONNER UNE CATÉGORIE...</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" style="background:#111; color:#fff;">{{ strtoupper($cat->nom) }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-caret-down caret-icon"></i>
                                </div>
                            </div>

                            <!-- Mot de Passe -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">MOT DE PASSE TEMPORAIRE</label>
                                <div class="neo-input-group mt-2 glow-warning">
                                    <i class="fas fa-key"></i>
                                    <input type="password" name="password" placeholder="••••••••" required class="fw-900 letter-spacing-lg">
                                </div>
                            </div>

                            <!-- Bouton Submit -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn-submit uppercase fw-900 letter-spacing-sm">
                                    <i class="fas fa-check-double me-2"></i> CONFIRMER LA CRÉATION DU PROFIL
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
    /* استيراد الخط التكتيكي Oswald */
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { 
        --accent: #ffed00; 
        --panel-bg: #0d0d0d; 
        --input-bg: rgba(0,0,0,0.4); 
        --border-tactical: rgba(255,255,255,0.07); 
    }

    body { 
        background: #050505; 
        font-family: 'Oswald', sans-serif; /* تطبيق الخط على الصفحة كاملة */
    }

    /* ستايلات الخطوط الموحدة */
    .fw-900 { font-weight: 900; }
    .fw-700 { font-weight: 700; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }

    .tiny-caps { 
        font-size: 11px; 
        font-weight: 700; 
        text-transform: uppercase; 
        color: rgba(255,255,255,0.4); 
        display: block; 
    }

    /* Terminal Card Design */
    .edit-terminal {
        background: var(--panel-bg);
        border: 1px solid var(--border-tactical);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 50px 100px rgba(0,0,0,0.9);
        position: relative;
    }
    .edit-terminal::before {
        content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
        box-shadow: 0 0 15px var(--accent);
    }

    .terminal-top-bar {
        background: rgba(255,255,255,0.02);
        border-bottom: 1px solid var(--border-tactical);
        padding: 20px 30px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .brand-logo { font-size: 1.4rem; color: #fff; }
    .brand-logo span { color: var(--accent); }
    .protocol-status { font-size: 9px; color: var(--accent); }
    .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite; }
    .sys-id { font-size: 8px; color: rgba(255,255,255,0.2); font-family: monospace; }

    /* Identity Section Icon */
    .coach-identity-bar {
        background: rgba(255,255,255,0.01);
        border-bottom: 1px solid var(--border-tactical);
        padding: 25px 35px;
        display: flex; align-items: center;
    }
    .icon-hex { position: relative; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .icon-hex i { color: var(--accent); font-size: 1.4rem; z-index: 2; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }

    /* Input Field Styling */
    .neo-input-group {
        background: var(--input-bg);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 15px 18px;
        display: flex; align-items: center;
        transition: 0.3s;
    }
    .neo-input-group:focus-within { border-color: var(--accent); box-shadow: 0 0 20px rgba(255,237,0,0.08); }
    .neo-input-group i { color: var(--accent); margin-right: 15px; font-size: 14px; }
    
    .neo-input-group input,
    .neo-input-group select {
        background: transparent !important; border: none; color: #fff !important;
        width: 100%; outline: none; appearance: none; font-size: 15px; font-family: 'Oswald', sans-serif;
    }
    .caret-icon { color: var(--accent); font-size: 10px; margin-left: 8px; pointer-events: none; }
    .glow-warning:focus-within { border-color: var(--accent); box-shadow: 0 0 20px rgba(255,237,0,0.15); }

    /* Submit Button */
    .btn-submit {
        background: var(--accent); color: #000; border: none; width: 100%;
        padding: 20px; border-radius: 12px;
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
    }
    .btn-submit:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }

    .elite-btn-back { text-decoration: none; color: rgba(255,255,255,0.4); font-size: 11px; transition: 0.3s; }
    .elite-btn-back:hover { color: var(--accent); }

    /* Autofill fix */
    input:-webkit-autofill {
        -webkit-text-fill-color: #fff !important;
        transition: background-color 5000000s ease-in-out 0s !important;
        -webkit-box-shadow: 0 0 0px 1000px rgba(0,0,0,0.4) inset !important;
    }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
</style>
@endsection