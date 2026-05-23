@extends('layouts.app')

@section('page_title', 'DÉTAILS SQUAD')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. En-tête de Mission -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <div class="d-flex align-items-center mb-2">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-sm fw-700">UNITÉ OPÉRATIONNELLE : REGISTRE DE SÉANCE</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase letter-spacing-lg">DÉTAILS <span style="color: var(--accent);">DU SQUAD</span></h1>
        </div>
        <a href="{{ route('coach.dashboard') }}" class="btn-scan-action uppercase fw-900" style="font-size: 11px;">
            <i class="fas fa-arrow-left me-2"></i> RETOUR AU TABLEAU DE BORD
        </a>
    </div>

    <!-- 2. Informations sur la Séance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="validation-terminal p-4 border-accent-dim">
                <div class="row align-items-center">
                    <div class="col-md-8 d-flex align-items-center">
                        <div class="user-avatar-hex me-4" style="width: 60px; height: 60px;">
                            <span class="hex-text" style="font-size: 1.5rem;"><i class="fas fa-bolt text-accent"></i></span>
                            <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                        </div>
                        <div>
                            <h4 class="text-white fw-900 uppercase m-0">{{ $seance->typeSeance->nom ?? 'SÉANCE' }}</h4>
                            <span class="text-secondary tiny-caps uppercase">DÉPLOIEMENT : {{ \Carbon\Carbon::parse($seance->heure_seance)->format('H:i') }} // COACH : {{ auth()->user()->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge-status-elite active fw-900 uppercase">MISSION EN COURS</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Liste des Adhérents -->
    <div class="validation-terminal shadow-2xl">
        <div class="terminal-header d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-white border-opacity-5">
            <span class="tiny-caps text-white fw-900 letter-spacing-sm uppercase"><i class="fas fa-users text-accent me-2"></i> ADHÉRENTS DÉPLOYÉS DANS CETTE UNITÉ</span>
            <span class="text-secondary fw-900" style="font-size: 9px;">TOTAL DETECTÉ : {{ $members->count() }} UNITÉS</span>
        </div>
        <div class="table-responsive">
            <table class="table table-dark m-0">
                <thead>
                    <tr class="tiny-caps text-secondary uppercase" style="font-size: 10px;">
                        <th class="ps-4 py-3">IDENTITÉ DE L'UNITÉ</th>
                        <th class="text-center">ÉTAT ABONNEMENT</th>
                        <th class="text-end pe-5">POINTAGE PRÉSENCE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    @php
                        // بما أننا جلبنا الأعضاء من علاقة Séance، يمكننا الوصول لبيانات الحضور مباشرة من pivot
                        $isPresent = $member->pivot->is_present;
                    @endphp
                    <tr class="terminal-row-hover {{ $isPresent ? 'row-active' : '' }}" id="row-{{ $member->id }}">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-hex me-3" style="width: 40px; height: 40px;">
                                    <span class="hex-text fw-900" style="font-size: 11px;">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                                </div>
                                <div>
                                    <div class="text-white fw-900 small uppercase">{{ $member->name }}</div>
                                    <div class="text-secondary fw-600" style="font-size: 8px;">SIGNAL: ACTIF // ID: #{{ 1000 + $member->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge-status-elite active uppercase fw-900" style="font-size: 8px;">AUTORISÉ</span>
                        </td>
                        <td class="text-end pe-5">
                            <button class="btn-op-approve py-1 px-3 fw-900 uppercase btn-attendance {{ $isPresent ? 'is-present' : '' }}" 
                                    data-user-id="{{ $member->id }}" 
                                    data-seance-id="{{ $seance->id }}"
                                    style="font-size: 9px;">
                                <i class="fas {{ $isPresent ? 'fa-user-check' : 'fa-check' }} me-1"></i> 
                                <span>{{ $isPresent ? 'PRÉSENT' : 'MARQUER PRÉSENT' }}</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5 tiny-caps opacity-30 text-white fw-700">AUCUN ADHÉRENT DÉPLOYÉ POUR CETTE SÉANCE</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Scripts AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.btn-attendance', function() {
    let btn = $(this);
    let userId = btn.data('user-id');
    let seanceId = btn.data('seance-id');
    let row = $('#row-' + userId);

    // إذا كان الممبر ديجا حاضر، لا نقوم بشيء (اختياري)
    if(btn.hasClass('is-present')) return;

    $.ajax({
        url: "{{ route('coach.markMemberAttendance') }}", 
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            user_id: userId,
            seance_id: seanceId
        },
        beforeSend: function() {
            btn.prop('disabled', true);
            btn.find('i').removeClass('fa-check fa-user-check').addClass('fa-circle-notch fa-spin');
        },
        success: function(response) {
            if(response.success) {
                btn.addClass('is-present').find('span').text('PRÉSENT');
                btn.find('i').removeClass('fa-circle-notch fa-spin').addClass('fa-user-check');
                row.addClass('row-active');
            }
        },
        error: function() {
            alert('ERREUR DE SIGNAL : Connexion perdue');
            btn.prop('disabled', false);
            btn.find('i').removeClass('fa-circle-notch fa-spin').addClass('fa-check');
        }
    });
});
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    a { text-decoration: none !important; }
    .fw-900 { font-weight: 900; } .uppercase { text-transform: uppercase; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    .validation-terminal { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.03); border-radius: 20px; overflow: hidden; }
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    .hex-text { color: #fff; z-index: 2; }

    .btn-scan-action { background: var(--accent); color: #000; padding: 10px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; border:none; }
    .btn-scan-action:hover { background: #fff; }
    
    .btn-op-approve { background: var(--accent); color: #000; border: none; border-radius: 6px; transition: 0.3s; cursor: pointer; }
    .btn-op-approve:hover:not(.is-present) { background: #fff; transform: translateY(-2px); }

    .btn-op-approve.is-present { background: #00ff88 !important; color: #000 !important; box-shadow: 0 0 15px rgba(0, 255, 136, 0.3); cursor: default; }
    .row-active { background: rgba(0, 255, 136, 0.05) !important; border-left: 3px solid #00ff88; }

    .badge-status-elite { padding: 4px 12px; border-radius: 4px; font-size: 10px; background: rgba(0, 210, 255, 0.1); color: #00d2ff; border: 1px solid rgba(0, 210, 255, 0.2); }
    .status-indicator-pulse { width: 12px; height: 12px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 15px var(--accent); animation: pGlow 2s infinite; }
    @keyframes pGlow { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.1); } 100% { opacity: 1; transform: scale(1); } }
    .terminal-row-hover:hover { background: rgba(255,255,255,0.01); transition: 0.2s; }
</style>
@endsection