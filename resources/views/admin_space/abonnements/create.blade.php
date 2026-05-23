@extends('layouts.app')

@section('page_title', 'INITIALISATION DU CONTRAT')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-900 text-white mb-1 uppercase letter-spacing-lg">INITIALISATION <span style="color: var(--accent);">CONTRAT</span></h1>
            <p class="text-secondary small uppercase letter-spacing-xs">Déploiement des protocoles d'accès pour l'adhérent</p>
        </div>
        <a href="{{ route('admin.abonnements.index') }}" class="elite-btn-back uppercase fw-900 letter-spacing-sm">
            <i class="fas fa-arrow-left me-2"></i> RETOUR AU HUB
        </a>
    </div>

    @if ($errors->any())
        <div class="alert bg-danger bg-opacity-10 text-danger border-0 rounded-3 mb-5 small fw-900 uppercase letter-spacing-xs">
            <i class="fas fa-exclamation-triangle me-2"></i> Erreur de protocole : {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.abonnements.store') }}" method="POST" id="abonnementForm" autocomplete="off">
        @csrf
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="edit-terminal">

                    <div class="terminal-top-bar">
                        <div class="brand-logo fw-900 uppercase letter-spacing-xs">FIT<span>PRO</span></div>
                        <div class="protocol-status uppercase fw-900 letter-spacing-xs">
                            <span class="status-dot"></span> BILLING_PROTOCOLE : CONFIGURATION_UNITÉ
                        </div>
                        <div class="sys-id fw-900 letter-spacing-xs"></div>
                    </div>

                    <div class="coach-identity-bar">
                        <div class="avatar-hex">
                            <i class="fas fa-file-signature text-white" style="z-index: 2; font-size: 1.5rem;"></i>
                            <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                        </div>
                        <div class="ms-4">
                            <h5 class="m-0 fw-900 text-white uppercase letter-spacing-sm">CONFIGURATION DE L'ABONNEMENT</h5>
                            <span class="text-secondary uppercase letter-spacing-xs" style="font-size: 9px;">
                                STATUT : <span style="color: var(--accent);">EN ATTENTE DE DÉPLOIEMENT</span>
                            </span>
                        </div>
                    </div>

                    <div class="p-4 p-lg-5">
                        <div class="row g-5">
                            
                            <!-- 1. Informations Adhérent -->
                            <div class="col-lg-7 border-end border-white border-opacity-5">
                                <h6 class="tiny-caps text-accent mb-4 fw-900 letter-spacing-sm"><i class="fas fa-user-shield me-2"></i> INFORMATIONS ADHÉRENT</h6>
                                
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">IDENTITÉ DE L'ADHÉRENT (RECHERCHE...)</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-search"></i>
                                            <select name="user_id" id="user_id_select" required class="uppercase fw-900">
                                                <option value="" disabled selected>TAPER LE NOM DU MEMBRE...</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" style="background: #0d0d0d;">{{ strtoupper($user->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">NIVEAU D'ACCÈS (PACK)</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-crown"></i>
                                            <select name="type_abonnement_id" id="type_abonnement_id" class="uppercase fw-900 custom-select-dark">
                                                <option value="" data-months="0" data-price="0" selected style="background: #0d0d0d;">SANS PACK (À LA CARTE)</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}" data-months="{{ $type->duree_mois }}" data-price="{{ $type->prix }}" style="background: #0d0d0d;">
                                                        {{ strtoupper($type->nom) }} ({{ $type->duree_mois }} MOIS)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <i class="fas fa-caret-down caret-icon" style="margin-left: auto; color: var(--accent);"></i>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="tiny-caps letter-spacing-sm">DATE D'ACTIVATION</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-calendar-plus"></i>
                                            <input type="date" name="date_debut" id="date_debut" value="{{ date('Y-m-d') }}" required class="fw-900 uppercase">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="tiny-caps letter-spacing-sm">EXPIRATION (AUTO)</label>
                                        <div class="neo-input-group mt-2" style="background: rgba(255, 237, 0, 0.05); border: 1px dashed var(--accent);">
                                            <i class="fas fa-calendar-check text-accent"></i>
                                            <input type="date" name="date_fin" id="date_fin" class="text-accent fw-900 uppercase" required readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">MONTANT TOTAL À RÉGLER (MAD)</label>
                                        <div class="neo-input-group mt-2 glow-warning" style="border-color: var(--accent);">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <input type="number" name="montant_paye" id="montant_paye" placeholder="0.00" required step="0.01" class="fw-900 letter-spacing-xs text-accent">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Sports Selection (Tactical Hub style) -->
                            <div class="col-lg-5">
                                <h6 class="tiny-caps text-accent mb-4 fw-900 letter-spacing-sm"><i class="fas fa-dumbbell me-2"></i> DISCIPLINES INCLUSES</h6>
                                
                                <div class="tactical-selection-hub">
                                    <div class="members-scroll-zone" style="max-height: 350px;">
                                        <div class="row g-2">
                                            @foreach($sports as $sport)
                                            <div class="col-12">
                                                <label class="compact-member-card">
                                                    <input type="checkbox" name="sports_ids[]" value="{{ $sport->id }}" class="sport-checkbox d-none">
                                                    <div class="card-inner">
                                                        <div class="status-box"></div>
                                                        <div class="meta">
                                                            <span class="name">{{ strtoupper($sport->nom) }}</span>
                                                            <span class="sub">DISCIPLINE_ID:{{ str_pad($sport->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 border border-white border-opacity-5 rounded bg-black">
                                    <p class="text-secondary mb-0 fw-900 uppercase text-center" style="font-size: 10px;">
                                        TARIF SQUAD : <span class="text-accent">200 MAD</span> (1ère) + <span class="text-accent">50 MAD</span> (SUPP)
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn-submit uppercase fw-900 letter-spacing-sm">
                                        <i class="fas fa-cloud-upload-alt me-2"></i> DÉPLOYER LE CONTRAT
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#user_id_select').select2({
        width: '100%',
        dropdownParent: $('#abonnementForm')
    });

    const typeSelect = document.getElementById('type_abonnement_id');
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const montantInput = document.getElementById('montant_paye');
    const sportCheckboxes = document.querySelectorAll('.sport-checkbox');

    function updateLogic() {
        let total = 0;
        const selected = typeSelect.options[typeSelect.selectedIndex];
        if(selected && selected.value !== "") {
            total += parseFloat(selected.getAttribute('data-price') || 0);
        }
        const selectedSports = document.querySelectorAll('.sport-checkbox:checked');
        if (selectedSports.length > 0) {
            total += 200 + ((selectedSports.length - 1) * 50);
        }
        montantInput.value = total.toFixed(2);
        
        const months = parseInt(selected.getAttribute('data-months') || 0);
        if (dateDebut.value) {
            let start = new Date(dateDebut.value);
            let duration = (months > 0) ? months : 1; 
            start.setMonth(start.getMonth() + duration);
            dateFin.value = start.toISOString().split('T')[0];
        }
    }

    typeSelect.addEventListener('change', updateLogic);
    dateDebut.addEventListener('change', updateLogic);
    sportCheckboxes.forEach(cb => cb.addEventListener('change', updateLogic));
    updateLogic();
});
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    
    :root { 
        --accent: #ffed00; 
        --panel-bg: #0d0d0d; 
        --input-bg: rgba(0,0,0,0.6); 
        --border-tactical: rgba(255,255,255,0.07); 
    }

    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 11px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.4); display: block; }

    /* Terminal Structure */
    .edit-terminal { background: var(--panel-bg); border: 1px solid var(--border-tactical); border-radius: 20px; overflow: hidden; position: relative; box-shadow: 0 50px 100px rgba(0,0,0,0.9); }
    .edit-terminal::before { content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); box-shadow: 0 0 15px var(--accent); }
    
    .terminal-top-bar { background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-tactical); padding: 20px 30px; display: flex; align-items: center; justify-content: space-between; }
    .brand-logo { font-size: 1.4rem; color: #fff; } .brand-logo span { color: var(--accent); }
    .protocol-status { font-size: 9px; color: var(--accent); } .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite; }
    .sys-id { font-size: 8px; color: rgba(255,255,255,0.2); }

    .coach-identity-bar { background: rgba(255,255,255,0.01); border-bottom: 1px solid var(--border-tactical); padding: 25px 35px; display: flex; align-items: center; }
    .avatar-hex { position: relative; width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }

    /* Form Elements */
    .neo-input-group { background: var(--input-bg); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 15px 18px; display: flex; align-items: center; transition: 0.3s; }
    .neo-input-group:focus-within { border-color: var(--accent); }
    .neo-input-group i { color: var(--accent); margin-right: 15px; font-size: 14px; }
    .neo-input-group input, .neo-input-group select { background: transparent !important; border: none; color: #fff !important; width: 100%; outline: none; font-size: 15px; font-family: 'Oswald', sans-serif; }
    
    select option { background-color: #0d0d0d !important; color: #fff; }

    /* Tactical Sports Selector */
    .tactical-selection-hub { background: rgba(0,0,0,0.2); border: 1px solid var(--border-tactical); padding: 15px; border-radius: 15px; }
    .members-scroll-zone { overflow-y: auto; padding-right: 10px; scrollbar-width: thin; scrollbar-color: var(--accent) transparent; }
    .compact-member-card { display: block; cursor: pointer; margin-bottom: 0; width: 100%; }
    .card-inner { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px 15px; display: flex; align-items: center; transition: 0.3s; }
    .status-box { width: 8px; height: 8px; background: rgba(255,255,255,0.1); margin-right: 15px; transition: 0.3s; }
    .meta .name { display: block; font-size: 11px; font-weight: 900; color: rgba(255,255,255,0.4); letter-spacing: 1px; }
    .meta .sub { font-size: 8px; color: rgba(255,255,255,0.15); font-weight: 700; }

    .sport-checkbox:checked + .card-inner { background: rgba(255, 237, 0, 0.05); border-color: var(--accent); }
    .sport-checkbox:checked + .card-inner .status-box { background: var(--accent); box-shadow: 0 0 10px var(--accent); }
    .sport-checkbox:checked + .card-inner .name { color: var(--accent); }

    /* Select2 Tactical Fix */
    .select2-container--default .select2-selection--single { background-color: transparent !important; border: none !important; height: auto !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: #fff !important; font-weight: 900; text-transform: uppercase; font-size: 15px; padding-left: 0 !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }
    .select2-dropdown { background-color: #0d0d0d !important; border: 1px solid var(--accent) !important; border-radius: 12px !important; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.9); }
    .select2-results__option { background-color: #0d0d0d !important; color: #fff !important; font-family: 'Oswald'; text-transform: uppercase; font-weight: 700; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .select2-results__option--highlighted { background-color: var(--accent) !important; color: #000 !important; }
    .select2-search__field { background: #1a1a1a !important; color: var(--accent) !important; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 8px !important; }

    /* Buttons */
    .btn-submit { background: var(--accent); color: #000; border: none; width: 100%; padding: 20px; border-radius: 12px; transition: 0.4s; cursor: pointer; font-size: 16px; }
    .btn-submit:hover { background: #fff; transform: translateY(-3px); }
    .elite-btn-back { text-decoration: none; color: rgba(255,255,255,0.4); font-size: 11px; transition: 0.3s; }
    .elite-btn-back:hover { color: var(--accent); }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    
    input[type="date"]::-webkit-calendar-picker-indicator { 
        filter: invert(1) sepia(100%) saturate(10000%) hue-rotate(10deg); cursor: pointer; 
    }
</style>
@endsection