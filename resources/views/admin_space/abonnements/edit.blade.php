@extends('layouts.app')

@section('page_title', 'PROTOCOLE DE MODIFICATION')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid py-4 px-lg-5">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-900 text-white mb-1 uppercase letter-spacing-lg">MODIFIER <span style="color: var(--accent);">LE CONTRAT</span></h1>
            <p class="text-secondary small uppercase letter-spacing-sm" style="font-size: 10px;">Réécriture des protocoles d'accès pour l'Abonnement #{{ 1000 + $abonnement->id }}</p>
        </div>
        <a href="{{ route('admin.abonnements.index') }}" class="elite-btn-back uppercase fw-900 letter-spacing-sm">
            <i class="fas fa-arrow-left me-2"></i> RETOUR AU HUB
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger bg-danger text-white border-0 mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.abonnements.update', $abonnement->id) }}" method="POST" id="abonnementForm" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="edit-terminal">

                    <div class="terminal-top-bar">
                        <div class="brand-logo fw-900 uppercase letter-spacing-xs">FIT<span>PRO</span></div>
                        <div class="protocol-status uppercase fw-900 letter-spacing-xs">
                            <span class="status-dot"></span> SYSTÈME_OVERRIDE : RECONFIGURATION
                        </div>
                        <div class="sys-id fw-900 letter-spacing-xs">CONTRACT_ID // #{{ 1000 + $abonnement->id }}</div>
                    </div>

                    <div class="coach-identity-bar">
                        <div class="avatar-hex">
                            <span class="fw-900" style="z-index: 2; color: white; font-size: 1.5rem;">
                                {{ strtoupper(substr($abonnement->user->name ?? '?', 0, 1)) }}
                            </span>
                            <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                        </div>
                        <div class="ms-4">
                            <h5 class="m-0 fw-900 text-white uppercase letter-spacing-sm">{{ strtoupper($abonnement->user->name) }}</h5>
                            <span class="uppercase fw-900 letter-spacing-xs" style="font-size: 9px;">
                                STATUT ACTUEL :
                                @if(\Carbon\Carbon::parse($abonnement->date_fin)->isPast())
                                    <span style="color: #ff3e3e;">🔴 ACCÈS RÉVOQUÉ</span>
                                @else
                                    <span style="color: #00ff88;">🟢 ACCÈS AUTORISÉ</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="p-4 p-lg-5">
                        <div class="row g-5">

                            <!-- الجهة اليسرى -->
                            <div class="col-lg-7 border-end border-white border-opacity-5">
                                <h6 class="tiny-caps text-accent mb-4 fw-900 letter-spacing-sm"><i class="fas fa-user-shield me-2"></i> INFORMATIONS ADHÉRENT</h6>

                                <div class="row g-4">

                                    <!-- اختيار العضو -->
                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">IDENTITÉ DE L'ADHÉRENT (MEMBRE)</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-user-circle"></i>
                                            <select name="user_id" id="user_id_select" required class="uppercase fw-900">
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $abonnement->user_id == $user->id ? 'selected' : '' }} style="background: #111;">
                                                        {{ strtoupper($user->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- اختيار الباك -->
                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">NIVEAU D'ACCÈS (PACK)</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-crown"></i>
                                            <select name="type_abonnement_id" id="type_abonnement_id" class="uppercase fw-900">
                                                <option value="" data-months="0" data-price="0" {{ is_null($abonnement->type_abonnement_id) ? 'selected' : '' }} style="background: #111;">SANS PACK (À LA CARTE)</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}"
                                                        data-months="{{ $type->duree_mois }}"
                                                        data-price="{{ $type->prix }}"
                                                        {{ $abonnement->type_abonnement_id == $type->id ? 'selected' : '' }}
                                                        style="background: #111;">
                                                        {{ strtoupper($type->nom) }} ({{ $type->duree_mois }} MOIS)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- التواريخ -->
                                    <div class="col-md-6">
                                        <label class="tiny-caps letter-spacing-sm">DATE D'ACTIVATION</label>
                                        <div class="neo-input-group mt-2">
                                            <i class="fas fa-calendar-plus"></i>
                                            <input type="date" name="date_debut" id="date_debut" value="{{ $abonnement->date_debut }}" required class="fw-900 uppercase">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="tiny-caps letter-spacing-sm">EXPIRATION (MANUAL OVERRIDE)</label>
                                        <div class="neo-input-group mt-2" style="background: rgba(255, 237, 0, 0.05); border: 1px dashed var(--accent);">
                                            <i class="fas fa-calendar-check text-accent"></i>
                                            <input type="date" name="date_fin" id="date_fin" value="{{ $abonnement->date_fin }}" required class="text-accent fw-900 uppercase">
                                        </div>
                                    </div>

                                    <!-- المبلغ -->
                                    <div class="col-md-12">
                                        <label class="tiny-caps letter-spacing-sm">MONTANT DE LA TRANSACTION (MAD)</label>
                                        <div class="neo-input-group mt-2 glow-warning">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <input type="number" name="montant_paye" id="montant_paye" value="{{ $abonnement->montant_paye }}" required step="0.01" class="fw-900 letter-spacing-xs">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- الجهة اليمنى: الرياضات -->
                            <div class="col-lg-5">
                                <h6 class="tiny-caps text-accent mb-4 fw-900 letter-spacing-sm"><i class="fas fa-dumbbell me-2"></i> DISCIPLINES INCLUSES</h6>

                                <p class="text-secondary small uppercase mb-4" style="font-size: 8px;">Sélectionnez les disciplines pour ce contrat</p>

                                <div class="sports-selector-grid">
                                    @foreach($sports as $sport)
                                    <label class="sport-tactical-item d-flex align-items-center p-3 mb-2">
                                        <input type="checkbox" name="sports_ids[]" value="{{ $sport->id }}"
                                            class="sport-checkbox d-none"
                                            {{ optional($abonnement->typeSeances)->contains('id', $sport->id) ? 'checked' : '' }}>
                                        <div class="tactical-check me-3"></div>
                                        <span class="text-white fw-900 uppercase small">{{ $sport->nom }}</span>
                                    </label>
                                    @endforeach
                                </div>

                                <div class="mt-4 p-2 border border-white border-opacity-5 rounded text-center">
                                    <p class="text-secondary mb-0 fw-900 uppercase" style="font-size: 9px;">
                                        TARIF SQUAD : <span class="text-accent">200 MAD</span> (1ère) + <span class="text-accent">50 MAD</span> (SUPP)
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn-submit uppercase fw-900 letter-spacing-sm">
                                        <i class="fas fa-sync-alt me-2"></i> APPLIQUER LA RECONFIGURATION
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
    const sportCheckboxes = document.querySelectorAll('.sport-checkbox');

    function updateDateFin() {
        const selected = typeSelect.options[typeSelect.selectedIndex];
        const months = parseInt(selected.getAttribute('data-months') || 0);
        if (months > 0 && dateDebut.value) {
            let start = new Date(dateDebut.value);
            start.setMonth(start.getMonth() + months);
            dateFin.value = start.toISOString().split('T')[0];
        }
    }

    function updateSportsStyle() {
        sportCheckboxes.forEach(cb => {
            const parent = cb.closest('.sport-tactical-item');
            if(cb.checked) { parent.classList.add('bg-white', 'bg-opacity-10'); }
            else { parent.classList.remove('bg-white', 'bg-opacity-10'); }
        });
    }

    // Init styles للرياضات المحددة من البداية
    updateSportsStyle();

    typeSelect.addEventListener('change', updateDateFin);
    dateDebut.addEventListener('change', updateDateFin);
    sportCheckboxes.forEach(cb => cb.addEventListener('change', updateSportsStyle));
});
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; --input-bg: rgba(0,0,0,0.4); --border-tactical: rgba(255,255,255,0.07); }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }

    .elite-btn-back {
        color: rgba(255,255,255,0.4) !important;
        font-size: 11px !important;
        letter-spacing: 1px;
        text-decoration: none !important;
        transition: 0.3s;
    }
    .elite-btn-back:hover { color: var(--accent) !important; }

    /* ===== SELECT2 DARK FIX ===== */
    .select2-container--default .select2-selection--single {
        background-color: transparent !important;
        border: none !important;
        height: 100% !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #fff !important;
        font-weight: 900 !important;
        font-family: 'Oswald', sans-serif !important;
        text-transform: uppercase !important;
        font-size: 15px !important;
        padding-left: 0 !important;
        line-height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        display: none !important;
    }
    .select2-dropdown {
        background-color: #0d0d0d !important;
        border: 1px solid var(--accent) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.9) !important;
    }
    .select2-search--dropdown {
        background-color: #0d0d0d !important;
        padding: 10px !important;
        border-bottom: 1px solid rgba(255,255,255,0.07) !important;
    }
    .select2-search--dropdown .select2-search__field {
        background-color: #1a1a1a !important;
        color: var(--accent) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        border-radius: 8px !important;
        font-family: 'Oswald', sans-serif !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        padding: 8px 12px !important;
        outline: none !important;
        width: 100% !important;
    }
    .select2-results {
        background-color: #0d0d0d !important;
    }
    .select2-results__options {
        background-color: #0d0d0d !important;
        max-height: 220px !important;
    }
    .select2-results__option {
        background-color: #0d0d0d !important;
        color: #fff !important;
        font-family: 'Oswald', sans-serif !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        text-transform: uppercase !important;
        padding: 10px 15px !important;
        border-bottom: 1px solid rgba(255,255,255,0.04) !important;
    }
    .select2-results__option--highlighted,
    .select2-results__option--highlighted[aria-selected] {
        background-color: var(--accent) !important;
        color: #000 !important;
    }
    .select2-results__option[aria-selected="true"] {
        background-color: rgba(255,237,0,0.1) !important;
        color: var(--accent) !important;
    }
    /* ===== END SELECT2 FIX ===== */

    .edit-terminal { background: var(--panel-bg); border: 1px solid var(--border-tactical); border-radius: 20px; overflow: hidden; position: relative; box-shadow: 0 50px 100px rgba(0,0,0,0.9); }
    .edit-terminal::before { content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); box-shadow: 0 0 15px var(--accent); }
    .terminal-top-bar { background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-tactical); padding: 20px 30px; display: flex; align-items: center; justify-content: space-between; }
    .brand-logo span { color: var(--accent); }
    .protocol-status { font-size: 9px; color: var(--accent); }
    .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; animation: pulse 2s infinite; }
    .sys-id { font-size: 8px; color: rgba(255,255,255,0.2); }
    .coach-identity-bar { background: rgba(255,255,255,0.01); border-bottom: 1px solid var(--border-tactical); padding: 25px 35px; display: flex; align-items: center; }
    .avatar-hex { position: relative; width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .neo-input-group { background: var(--input-bg); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 12px 18px; display: flex; align-items: center; transition: 0.3s; }
    .neo-input-group:focus-within { border-color: var(--accent); box-shadow: 0 0 20px rgba(255,237,0,0.08); }
    .neo-input-group i { color: var(--accent); margin-right: 15px; font-size: 14px; }
    .neo-input-group input, .neo-input-group select { background: transparent !important; border: none; color: #fff !important; width: 100%; outline: none; font-family: 'Oswald', sans-serif; font-size: 15px; }
    .sport-tactical-item { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; cursor: pointer; transition: 0.3s; }
    .tactical-check { width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.2); border-radius: 4px; transition: 0.3s; }
    .sport-checkbox:checked + .tactical-check { background: var(--accent); border-color: var(--accent); box-shadow: 0 0 10px var(--accent); }
    .sport-checkbox:checked ~ span { color: var(--accent) !important; }
    .btn-submit { background: var(--accent); color: #000; border: none; width: 100%; padding: 20px; border-radius: 12px; transition: 0.4s; cursor: pointer; }
    .btn-submit:hover { background: #fff; transform: translateY(-3px); }
    .uppercase { text-transform: uppercase; } .fw-900 { font-weight: 900; } .letter-spacing-lg { letter-spacing: 4px; } .letter-spacing-sm { letter-spacing: 2px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.4); display: block; }
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1) sepia(100%) saturate(10000%) hue-rotate(10deg); cursor: pointer; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
</style>
@endsection