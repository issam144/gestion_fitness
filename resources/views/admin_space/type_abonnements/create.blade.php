@extends('layouts.app')

@section('page_title', 'CONFIGURATION PACK TARIF')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. En-tête (Header) -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-900 text-white mb-1 uppercase letter-spacing-lg">CRÉER UN <span style="color: var(--accent);">PACK</span></h1>
            <p class="text-secondary small uppercase letter-spacing-sm" style="font-size: 10px;">Définition d'une nouvelle offre tarifaire pour les adhérents</p>
        </div>
        <a href="{{ route('admin.type-abonnements.index') }}" class="elite-btn-back uppercase fw-900 letter-spacing-sm">
            <i class="fas fa-arrow-left me-2"></i> RETOUR AUX TARIFS
        </a>
    </div>

    <form action="{{ route('admin.type-abonnements.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Terminal Card System -->
                <div class="edit-terminal">

                    <!-- En-tête du Terminal -->
                    <div class="terminal-top-bar">
                        <div class="brand-logo fw-900 uppercase letter-spacing-xs">FIT<span>PRO</span></div>
                        <div class="protocol-status uppercase fw-900 letter-spacing-xs">
                            <span class="status-dot"></span> PRICING_LOGIC : CONFIGURATION
                        </div>
                        <div class="sys-id fw-900 letter-spacing-xs"></div>
                    </div>

                    <!-- Section Identity Bar -->
                    <div class="coach-identity-bar">
                        <div class="avatar-hex">
                            <i class="fas fa-tags text-white" style="z-index: 2; font-size: 1.5rem;"></i>
                            <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                        </div>
                        <div class="ms-4">
                            <h5 class="m-0 fw-900 text-white uppercase letter-spacing-sm">DÉFINITION DES PARAMÈTRES DU PACK</h5>
                            <span class="text-secondary uppercase letter-spacing-xs" style="font-size: 9px;">
                                STATUT : <span style="color: var(--accent);">INITIALISATION DES DONNÉES</span>
                            </span>
                        </div>
                    </div>

                    <!-- Formulaire Section -->
                    <div class="p-4 p-lg-5">
                        <div class="row g-5">
                            
                            <!-- الجزء الأيسر: تفاصيل الباك -->
                            <div class="col-lg-7 border-end border-white border-opacity-5">
                                <h6 class="tiny-caps text-accent mb-4 fw-900 letter-spacing-sm"><i class="fas fa-info-circle me-2"></i> INFORMATIONS GÉNÉRALES</h6>
                                
                                <div class="row g-4">
                                    <!-- اسم الباك -->
                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">DÉSIGNATION DU PACK (NOM)</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-id-badge"></i>
                                            <input type="text" name="nom" placeholder="EX: PACK GOLD, PACK TRIMESTRIEL..." required class="uppercase fw-900">
                                        </div>
                                    </div>

                                    <!-- الثمن -->
                                    <div class="col-md-6">
                                        <label class="tiny-caps letter-spacing-sm">TARIF DU PACK (MAD)</label>
                                        <div class="neo-input-group mt-2 glow-warning">
                                            <i class="fas fa-coins"></i>
                                            <input type="number" name="prix" placeholder="0.00" required class="fw-900">
                                        </div>
                                    </div>

                                    <!-- المدة بالشهور -->
                                    <div class="col-md-6">
                                        <label class="tiny-caps letter-spacing-sm">DURÉE DE VALIDITÉ (MOIS)</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-history"></i>
                                            <input type="number" name="duree_mois" placeholder="EX: 1, 6, 12..." required class="fw-900">
                                        </div>
                                    </div>

                                    <!-- الوصف -->
                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">DESCRIPTION DES AVANTAGES</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-align-left"></i>
                                            <textarea name="description" rows="3" class="bg-transparent border-0 text-white w-100 fw-900 uppercase" placeholder="DÉTAILS SUPPLÉMENTAIRES..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- الجزء الأيمن: الرياضات المتاحة في هذا الباك -->
                            <div class="col-lg-5">
                                <h6 class="tiny-caps text-accent mb-4 fw-900 letter-spacing-sm"><i class="fas fa-dumbbell me-2"></i> DISCIPLINES INCLUSES</h6>
                                
                                <p class="text-secondary small uppercase mb-4" style="font-size: 8px;">Sélectionnez les sports autorisés pour ce pack</p>

                                <div class="sports-selector-grid">
                                    @foreach($sports as $sport)
                                    <label class="sport-tactical-item d-flex align-items-center p-3 mb-2">
                                        <input type="checkbox" name="sports_ids[]" value="{{ $sport->id }}" class="sport-checkbox d-none">
                                        <div class="tactical-check me-3"></div>
                                        <span class="text-white fw-900 uppercase small">{{ $sport->nom }}</span>
                                    </label>
                                    @endforeach
                                </div>

                                <div class="mt-5">
                                    <button type="submit" class="btn-submit uppercase fw-900 letter-spacing-sm">
                                        <i class="fas fa-check-double me-2"></i> DÉPLOYER CE NOUVEAU TARIF
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; --input-bg: rgba(0,0,0,0.4); --border-tactical: rgba(255,255,255,0.07); }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    a { text-decoration: none !important; }

    /* Terminal UI Design */
    .edit-terminal { background: var(--panel-bg); border: 1px solid var(--border-tactical); border-radius: 20px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.9); position: relative; }
    .edit-terminal::before { content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); box-shadow: 0 0 15px var(--accent); }

    .terminal-top-bar { background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-tactical); padding: 20px 30px; display: flex; align-items: center; justify-content: space-between; }
    .brand-logo span { color: var(--accent); }
    .protocol-status { font-size: 9px; color: var(--accent); }
    .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; animation: pulse 2s infinite; }
    .sys-id { font-size: 8px; color: rgba(255,255,255,0.2); }

    .coach-identity-bar { background: rgba(255,255,255,0.01); border-bottom: 1px solid var(--border-tactical); padding: 25px 35px; display: flex; align-items: center; }
    .avatar-hex { position: relative; width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }

    /* Inputs Tactical Style */
    .neo-input-group { background: var(--input-bg); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 12px 18px; display: flex; align-items: center; transition: 0.3s; }
    .neo-input-group:focus-within { border-color: var(--accent); box-shadow: 0 0 20px rgba(255,237,0,0.08); }
    .neo-input-group i { color: var(--accent); margin-right: 15px; font-size: 14px; }
    .neo-input-group input, .neo-input-group textarea { background: transparent; border: none; color: #fff; width: 100%; outline: none; font-family: 'Oswald', sans-serif; font-size: 15px; }

    /* Sport selector styling */
    .sport-tactical-item { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; cursor: pointer; transition: 0.3s; }
    .sport-tactical-item:hover { background: rgba(255,237,0,0.05); }
    .tactical-check { width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.2); border-radius: 4px; transition: 0.3s; }
    .sport-checkbox:checked + .tactical-check { background: var(--accent); border-color: var(--accent); box-shadow: 0 0 10px var(--accent); }
    .sport-checkbox:checked ~ span { color: var(--accent) !important; }

    .btn-submit { background: var(--accent); color: #000; border: none; width: 100%; padding: 20px; border-radius: 12px; transition: 0.4s; cursor: pointer; }
    .btn-submit:hover { background: #fff; transform: translateY(-3px); }
    .elite-btn-back { color: rgba(255,255,255,0.4); font-size: 11px; }

    .uppercase { text-transform: uppercase; } .fw-900 { font-weight: 900; } .letter-spacing-lg { letter-spacing: 4px; } .letter-spacing-sm { letter-spacing: 2px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.4); display: block; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
</style>
@endsection