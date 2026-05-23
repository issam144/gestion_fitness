@extends('layouts.app')

@section('page_title', 'LOG DE PRÉSENCE OPÉRATIONNEL')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Mission Briefing Header -->
    <div class="row g-4 mb-5">
        <div class="col-md-8">
            <div class="scanner-stat-card border-accent shadow-glow">
                <div class="stat-label uppercase letter-spacing-sm fw-700">Objectif de la Mission</div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <div>
                        <h2 class="fw-900 m-0 text-white uppercase letter-spacing-xs">{{ $seance->type_seance->nom ?? 'UNITÉ SÉANCE' }}</h2>
                        <span class="tiny-caps text-accent fw-900 uppercase">
                            <i class="fas fa-clock me-1"></i> TIMELINE : {{ \Carbon\Carbon::parse($seance->date_seance)->translatedFormat('d M Y') }} @ {{ $seance->heure_seance }}
                        </span>
                    </div>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-bullseye"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="scanner-info-bar h-100 d-flex align-items-center justify-content-center px-4">
                <a href="{{ route('coach.seances') }}" class="btn-scan-action w-100 text-center uppercase fw-900 letter-spacing-xs">
                    <i class="fas fa-arrow-left me-2"></i> ABANDONNER & RETOUR
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert bg-accent bg-opacity-10 text-accent border-accent border-opacity-25 rounded-4 text-center mb-4 small fw-900 uppercase letter-spacing-xs shadow-neon">
            <i class="fas fa-shield-check me-2"></i> SYNCHRONISATION DES DONNÉES RÉUSSIE
        </div>
    @endif

    <!-- 2. Validation Terminal - Attendance List -->
    <form action="{{ route('coach.seance.updatePresence', $seance->id) }}" method="POST">
        @csrf
        <div class="validation-terminal shadow-2xl">
            <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 3fr 1fr;">
                <div class="ps-5">Identité du Guerrier (Adhérent)</div>
                <div class="text-center">Statut Opérationnel</div>
            </div>

            @forelse($seance->members as $member)
            <div class="terminal-row" style="grid-template-columns: 3fr 1fr;">
                
                <!-- Member Identity with Hexagon -->
                <div class="col-applicant">
                    <div class="user-avatar-hex">
                        <span class="hex-text fw-900">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <div class="ms-4 text-start">
                        <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ $member->name }}</h6>
                        <span class="text-secondary tiny-caps fw-700">ID-SIGNAL : #{{ 1000 + $member->id }}</span>
                    </div>
                </div>

                <!-- Operational Toggle -->
                <div class="text-center">
                    <div class="d-flex flex-column align-items-center">
                        <div class="form-check form-switch custom-terminal-switch">
                            <input class="form-check-input" type="checkbox" 
                                   name="presence[{{ $member->id }}]" value="present" 
                                   {{ $member->pivot->is_present ? 'checked' : '' }}>
                        </div>
                        <span class="tiny-caps mt-2 fw-900 uppercase {{ $member->pivot->is_present ? 'text-accent' : 'text-secondary' }}">
                            {{ $member->pivot->is_present ? 'CONFIRMÉ' : 'EN ATTENTE' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div class="mb-3 opacity-20"><i class="fas fa-user-slash fa-4x text-white"></i></div>
                <h5 class="text-secondary uppercase fw-900 letter-spacing">AUCUN AGENT DÉPLOYÉ POUR CETTE UNITÉ</h5>
            </div>
            @endforelse

            <!-- 3. Final Authorization Footer -->
            @if($seance->members->count() > 0)
            <div class="terminal-footer p-4 border-top border-white border-opacity-5 bg-black bg-opacity-20">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="tiny-caps text-secondary d-none d-md-inline uppercase fw-700">
                        <i class="fas fa-info-circle me-2 text-accent"></i> Vérifiez toutes les confirmations avant validation finale.
                    </span>
                    <button type="submit" class="btn-submit-elite py-3 px-5 border-0 uppercase fw-900 letter-spacing-sm">
                        <span>VALIDER LE LOG DE PRÉSENCE</span>
                        <i class="fas fa-file-signature ms-2"></i>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </form>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    
    body { background: #050505; font-family: 'Oswald', sans-serif; color: #fff; }
    
    a { text-decoration: none !important; }

    .fw-900 { font-weight: 900; }
    .fw-700 { font-weight: 700; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }

    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Scanner Header */
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    .scan-icon-mini { width: 45px; height: 45px; border-radius: 10px; background: rgba(255, 237, 0, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    .scanner-info-bar { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; }

    /* Hexagon Profile */
    .user-avatar-hex { position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-text { color: white; font-weight: 900; z-index: 2; font-size: 1.1rem; }

    /* Terminal UI */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .terminal-row:hover { background: rgba(255,255,255,0.01); }

    /* Tactical Switch */
    .custom-terminal-switch .form-check-input { width: 3.5rem; height: 1.8rem; background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); cursor: pointer; }
    .custom-terminal-switch .form-check-input:checked { background-color: var(--accent); border-color: var(--accent); box-shadow: 0 0 15px rgba(255,237,0,0.3); }

    /* Buttons */
    .btn-scan-action { background: rgba(255,255,255,0.03); color: #fff; border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 8px; transition: 0.3s; }
    .btn-scan-action:hover { background: #fff; color: #000; }

    .btn-submit-elite { background: var(--accent); color: #000; border-radius: 10px; transition: 0.4s; cursor: pointer; }
    .btn-submit-elite:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }

    .shadow-glow { box-shadow: 0 0 30px rgba(255, 237, 0, 0.05); }
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
</style>
@endsection