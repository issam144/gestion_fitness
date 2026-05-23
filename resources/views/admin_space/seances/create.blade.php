@extends('layouts.app')

@section('page_title', 'INITIALISATION DE SÉANCE')

@section('content')
<div class="container-fluid py-4 px-lg-5">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-900 text-white mb-1 uppercase letter-spacing-lg">NOUVELLE <span style="color: var(--accent);">SÉANCE</span></h1>
            <p class="text-secondary small uppercase letter-spacing-xs">Programmation d'une nouvelle opération dans la timeline</p>
        </div>
        <a href="{{ route('admin.seances.index') }}" class="elite-btn-back uppercase fw-900 letter-spacing-sm">
            <i class="fas fa-arrow-left me-2"></i> RETOUR AU HUB
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="edit-terminal">

                <div class="terminal-top-bar">
                    <div class="brand-logo fw-900 uppercase letter-spacing-xs">FIT<span>PRO</span></div>
                    <div class="protocol-status uppercase fw-900 letter-spacing-xs">
                        <span class="status-dot"></span> SÉANCE_PROTOCOLE : CONFIGURATION_UNITÉS
                    </div>
                    <div class="sys-id fw-900 letter-spacing-xs"></div>
                </div>

                <div class="coach-identity-bar">
                    <div class="avatar-hex">
                        <i class="fas fa-users text-white" style="z-index: 2; font-size: 1.5rem;"></i>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <div class="ms-4">
                        <h5 class="m-0 fw-900 text-white uppercase letter-spacing-sm">SÉLECTION DES UNITÉS CIBLES</h5>
                        <span id="selectedCount" class="text-secondary uppercase letter-spacing-xs" style="font-size: 9px;">
                            STATUT : <span style="color: var(--accent);">0 UNITÉS IDENTIFIÉES</span>
                        </span>
                    </div>
                </div>

                <div class="p-4 p-lg-5">
                    @if($errors->any())
                        <div class="alert bg-danger bg-opacity-10 text-danger border-0 rounded-3 mb-5 small fw-900 uppercase letter-spacing-xs">
                            <i class="fas fa-exclamation-triangle me-2"></i> Erreur : {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('admin.seances.store') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="row g-4">

                            <!-- 1. HUB MEMBRES -->
                            <div class="col-md-12">
                                <label class="tiny-caps letter-spacing-sm">MEMBRES PARTICIPANTS (UNITÉS)</label>
                                
                                <div class="tactical-selection-hub mt-2">
                                    <div class="hub-search-bar mb-3">
                                        <div class="neo-input-group" style="padding: 10px 18px; flex-wrap: wrap; gap: 10px;">
                                            <i class="fas fa-search"></i>
                                            <input type="text" id="memberSearch" 
                                                   placeholder="RECHERCHER PAR SIGNATURE (NOM)..." 
                                                   onkeyup="filterMembers()" 
                                                   class="fw-900 uppercase" 
                                                   style="flex:1; min-width: 150px;">
                                            
                                            <select id="typeFilter" onchange="filterMembers()" 
                                                    class="fw-900 uppercase custom-select-dark">
                                                <option value="" style="background: #0d0d0d;">⚡ TOUTES LES DISCIPLINES</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}" style="background: #0d0d0d;">{{ strtoupper($type->nom) }}</option>
                                                @endforeach
                                            </select>

                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn-hub-action" onclick="toggleAll(true)">SELECT ALL</button>
                                                <button type="button" class="btn-hub-action outline" onclick="toggleAll(false)">CLEAR</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="members-scroll-zone">
                                        <div class="row g-2" id="membersList">
                                            @foreach($members as $member)
                                                <div class="col-md-6 col-lg-4 col-xl-3 member-item" 
                                                     data-name="{{ strtolower($member->name) }}"
                                                     data-types="{{ json_encode(
                                                         $member->abonnements
                                                             ->flatMap(fn($a) => $a->typeAbonnement?->typeSeances ?? collect())
                                                             ->pluck('id')
                                                             ->unique()
                                                             ->values()
                                                     ) }}">
                                                    <label class="compact-member-card">
                                                        <input type="checkbox" name="member_ids[]" value="{{ $member->id }}" 
                                                               class="member-checkbox d-none" 
                                                               data-coaches="{{ json_encode($member->coachs->pluck('id') ?? []) }}"
                                                               onchange="updateRecommendedCoaches()">
                                                        <div class="card-inner">
                                                            <div class="status-box"></div>
                                                            <div class="meta">
                                                                <span class="name">{{ strtoupper($member->name) }}</span>
                                                                <span class="sub">ID:{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Date -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">DATE DE DÉPLOIEMENT</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-calendar-day"></i>
                                    <input type="date" name="date_seance" required class="fw-900 uppercase">
                                </div>
                            </div>

                            <!-- 3. Heure -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">HEURE DE L'OPÉRATION</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-clock"></i>
                                    <input type="time" name="heure_seance" required class="fw-900 uppercase">
                                </div>
                            </div>

                            <!-- 4. Coach -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">COMMANDANT ASSIGNÉ (COACH)</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-user-tie"></i>
                                    <select name="coach_id" id="coachSelect" required class="uppercase fw-900 custom-select-dark">
                                        <option value="" disabled selected style="background: #0d0d0d;">SÉLECTIONNER UN COMMANDANT...</option>
                                        @foreach($coachs as $coach)
                                            <option value="{{ $coach->id }}" class="coach-option" style="background: #0d0d0d;">
                                                {{ strtoupper($coach->user->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-caret-down caret-icon"></i>
                                </div>
                                <small id="coachHint" class="text-accent tiny-caps mt-2 d-none ms-2 fw-900 letter-spacing-xs">
                                    <i class="fas fa-star"></i> COACH RECOMMANDÉ POUR CETTE SÉLECTION
                                </small>
                            </div>

                            <!-- 5. Discipline -->
                            <div class="col-md-6">
                                <label class="tiny-caps letter-spacing-sm">DISCIPLINE D'ENTRAÎNEMENT</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-dumbbell"></i>
                                    <select name="type_seance_id" id="mainTypeSelect" required class="uppercase fw-900 custom-select-dark">
                                        <option value="" disabled selected style="background: #0d0d0d;">CHOISIR LA DISCIPLINE...</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" style="background: #0d0d0d;">{{ strtoupper($type->nom) }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-caret-down caret-icon"></i>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn-submit uppercase fw-900 letter-spacing-sm">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> DÉPLOYER LA SÉANCE & NOTIFIER LES UNITÉS
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

    /* Fix Select Backgrounds */
    select.custom-select-dark {
        background-color: transparent !important;
        color: #fff !important;
        border: none;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }

    /* Target the dropdown menu (options) */
    select option {
        background-color: #0d0d0d !important; /* Fond noir */
        color: #fff !important;
        padding: 10px;
    }

    /* Specific style for the top filter select */
    #typeFilter {
        background: rgba(255,237,0,0.05) !important; 
        border: 1px solid var(--accent); 
        color: var(--accent) !important; 
        padding: 5px 12px; 
        border-radius: 6px;
    }
    
    #typeFilter option {
        background-color: #111 !important;
        color: var(--accent) !important;
    }

    .tactical-selection-hub {
        background: rgba(0,0,0,0.2);
        border: 1px solid var(--border-tactical);
        padding: 20px;
        border-radius: 15px;
    }
    .members-scroll-zone {
        max-height: 280px;
        overflow-y: auto;
        padding-right: 10px;
        scrollbar-width: thin;
        scrollbar-color: var(--accent) transparent;
    }
    .compact-member-card { display: block; cursor: pointer; margin-bottom: 0; width: 100%; }
    .card-inner {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 12px 15px;
        display: flex; align-items: center;
        transition: 0.3s;
    }
    .status-box { width: 8px; height: 8px; background: rgba(255,255,255,0.1); margin-right: 15px; transition: 0.3s; }
    .meta .name { display: block; font-size: 11px; font-weight: 900; color: rgba(255,255,255,0.4); letter-spacing: 1px; }
    .meta .sub { font-size: 8px; color: rgba(255,255,255,0.2); font-weight: 700; }

    .member-checkbox:checked + .card-inner { background: rgba(255, 237, 0, 0.05); border-color: var(--accent); }
    .member-checkbox:checked + .card-inner .status-box { background: var(--accent); box-shadow: 0 0 10px var(--accent); }
    .member-checkbox:checked + .card-inner .name { color: var(--accent); }

    .btn-hub-action {
        background: var(--accent); color: #000; border: none; padding: 5px 12px;
        font-size: 9px; font-weight: 900; letter-spacing: 1px; cursor: pointer; transition: 0.2s;
    }
    .btn-hub-action.outline { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; }
    
    .edit-terminal { background: var(--panel-bg); border: 1px solid var(--border-tactical); border-radius: 20px; overflow: hidden; position: relative; box-shadow: 0 50px 100px rgba(0,0,0,0.9); }
    .edit-terminal::before { content: ''; position: absolute; top: 0; left: 15%; width: 70%; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); box-shadow: 0 0 15px var(--accent); }
    .terminal-top-bar { background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-tactical); padding: 20px 30px; display: flex; align-items: center; justify-content: space-between; }
    .brand-logo { font-size: 1.4rem; color: #fff; } .brand-logo span { color: var(--accent); }
    .protocol-status { font-size: 9px; color: var(--accent); } .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite; }
    .coach-identity-bar { background: rgba(255,255,255,0.01); border-bottom: 1px solid var(--border-tactical); padding: 25px 35px; display: flex; align-items: center; }
    .avatar-hex { position: relative; width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .neo-input-group { background: var(--input-bg); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 15px 18px; display: flex; align-items: center; transition: 0.3s; }
    .neo-input-group:focus-within { border-color: var(--accent); }
    .neo-input-group i { color: var(--accent); margin-right: 15px; font-size: 14px; }
    .neo-input-group input, .neo-input-group select { background: transparent !important; border: none; color: #fff !important; width: 100%; outline: none; font-size: 15px; font-family: 'Oswald', sans-serif; }
    .btn-submit { background: var(--accent); color: #000; border: none; width: 100%; padding: 20px; border-radius: 12px; transition: 0.4s; cursor: pointer; font-size: 16px; }
    .btn-submit:hover { background: #fff; transform: translateY(-3px); }
    .elite-btn-back { text-decoration: none; color: rgba(255,255,255,0.4); font-size: 11px; transition: 0.3s; }
    .elite-btn-back:hover { color: var(--accent); }
    
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    
    input[type="date"]::-webkit-calendar-picker-indicator, 
    input[type="time"]::-webkit-calendar-picker-indicator { 
        filter: invert(1) sepia(100%) saturate(10000%) hue-rotate(10deg); cursor: pointer; 
    }
</style>

<script>
function filterMembers() {
    let input = document.getElementById('memberSearch').value.toLowerCase();
    let typeFilter = document.getElementById('typeFilter').value;
    let items = document.getElementsByClassName('member-item');

    for (let item of items) {
        let name = item.getAttribute('data-name');
        let types = JSON.parse(item.getAttribute('data-types') || '[]');

        let matchName = name.includes(input);
        let matchType = typeFilter === '' || types.map(String).includes(String(typeFilter));

        item.style.display = (matchName && matchType) ? "block" : "none";
    }
}

document.getElementById('mainTypeSelect').addEventListener('change', function() {
    document.getElementById('typeFilter').value = this.value;
    filterMembers();
});

function toggleAll(status) {
    let checkboxes = document.querySelectorAll('.member-checkbox');
    checkboxes.forEach(cb => {
        if (cb.closest('.member-item').style.display !== 'none') {
            cb.checked = status;
        }
    });
    updateRecommendedCoaches();
}

function updateRecommendedCoaches() {
    let checkboxes = document.querySelectorAll('.member-checkbox:checked');
    document.getElementById('selectedCount').innerHTML = 'STATUT : <span style="color: var(--accent);">' + checkboxes.length + ' UNITÉS IDENTIFIÉES</span>';
    
    let coachSelect = document.getElementById('coachSelect');
    let coachHint = document.getElementById('coachHint');
    
    let allRecommendedIds = [];
    checkboxes.forEach(cb => {
        let coaches = JSON.parse(cb.getAttribute('data-coaches') || "[]");
        allRecommendedIds = allRecommendedIds.concat(coaches);
    });
    
    let uniqueRecommendedIds = [...new Set(allRecommendedIds)];
    let options = coachSelect.getElementsByClassName('coach-option');
    let found = false;

    for (let opt of options) {
        let coachId = parseInt(opt.value);
        if (uniqueRecommendedIds.includes(coachId)) {
            opt.style.color = "#ffed00"; opt.style.fontWeight = "bold";
            if (!opt.innerText.includes("⭐")) opt.innerText = "⭐ " + opt.innerText;
            found = true;
        } else {
            opt.style.color = ""; opt.style.fontWeight = "";
            opt.innerText = opt.innerText.replace("⭐ ", "");
        }
    }
    found && checkboxes.length > 0 ? coachHint.classList.remove('d-none') : coachHint.classList.add('d-none');
}
</script>
@endsection