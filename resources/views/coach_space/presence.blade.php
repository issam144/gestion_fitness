@extends('layouts.app')

@section('page_title', 'LOG DE PRÉSENCE OPÉRATIONNEL')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <!-- 1. Mission Briefing & Live Counter -->
    <div class="row g-4 mb-5">
        <div class="col-md-7">
            <div class="scanner-stat-card shadow-glow">
                <div class="stat-label uppercase letter-spacing-sm fw-700">Unité de Mission Actuelle</div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <div>
                        <h2 class="fw-900 m-0 text-white uppercase letter-spacing-xs">{{ $seance->type_seance->nom ?? 'SÉANCE UNIT' }}</h2>
                        <span class="tiny-caps text-accent fw-900 uppercase">
                            <i class="fas fa-calendar-check me-1"></i> DATE DU LOG : {{ \Carbon\Carbon::parse($seance->date_seance)->format('d M Y') }}
                        </span>
                    </div>
                    <div class="scan-icon-mini pulse-yellow"><i class="fas fa-fingerprint"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="scanner-stat-card border-info">
                <div class="stat-label uppercase letter-spacing-sm fw-700">Progression de l'Exécution</div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <div>
                        <h2 class="fw-900 m-0 text-white letter-spacing-xs">
                            <span class="text-accent counter">{{ $seance->members->where('pivot.is_present', 1)->count() }}</span> 
                            <span class="text-secondary" style="font-size: 1.2rem;">/ {{ $seance->members->count() }}</span>
                        </h2>
                        <span class="tiny-caps text-info fw-900 uppercase">Adhérents Identifiés</span>
                    </div>
                    <div class="execution-hex pulse-blue">
                        <i class="fas fa-microchip text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert bg-accent bg-opacity-10 text-accent border-accent border-opacity-25 rounded-4 text-center mb-4 small fw-900 uppercase letter-spacing-xs">
            <i class="fas fa-shield-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- 2. The Validation Terminal (Attendance List) -->
    <!-- التعديل هنا: استعملنا coach.updatePresence كما في web.php -->
    <form action="{{ route('coach.updatePresence', $seance->id) }}" method="POST" id="attendanceForm">
        @csrf
        <div class="validation-terminal shadow-2xl">
            <div class="terminal-header d-none d-lg-grid uppercase letter-spacing-xs" style="grid-template-columns: 2.5fr 1fr 1fr;">
                <div class="ps-5">Identité de l'Adhérent</div>
                <div class="text-center">Statut Opérationnel</div>
                <div class="text-center">Validation Présence</div>
            </div>

            @forelse($seance->members as $member)
            <div class="terminal-row {{ $member->pivot->is_present ? 'row-executed' : '' }}" style="grid-template-columns: 2.5fr 1fr 1fr;">
                
                <!-- Member Info -->
                <div class="col-applicant d-flex align-items-center">
                    <div class="user-avatar-hex {{ $member->pivot->is_present ? 'hex-active' : '' }}">
                        <span class="hex-text fw-900">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <div class="ms-4 text-start">
                        <h6 class="m-0 fw-900 text-white uppercase letter-spacing-xs">{{ $member->name }}</h6>
                        <span class="text-secondary tiny-caps fw-700">SIGNAL-ID : #{{ 1000 + $member->id }}</span>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="text-center">
                    @if($member->pivot->is_present)
                        <span class="badge-executed fw-900 uppercase"><i class="fas fa-check-double me-1"></i> CONFIRMÉ</span>
                    @else
                        <span class="badge-standby fw-900 uppercase"><i class="fas fa-spinner fa-spin me-1"></i> EN ATTENTE</span>
                    @endif
                </div>

                <!-- Custom Tactical Switch -->
                <div class="text-center">
                    <div class="form-check form-switch custom-terminal-switch d-inline-block">
                        <input class="form-check-input" type="checkbox" 
                               name="presence[{{ $member->id }}]" value="1" 
                               {{ $member->pivot->is_present ? 'checked' : '' }}
                               onchange="this.form.submit()">
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <h5 class="text-secondary uppercase fw-900">AUCUN ADHÉRENT DÉPLOYÉ POUR CETTE UNITÉ</h5>
            </div>
            @endforelse

            <!-- 3. Terminal Footer -->
            <div class="terminal-footer p-4 border-top border-white border-opacity-5 d-flex justify-content-between align-items-center bg-black bg-opacity-20">
                <a href="{{ route('coach.seances') }}" class="btn-abort uppercase fw-900 letter-spacing-xs">
                    <i class="fas fa-arrow-left me-2"></i> RETOUR À LA TIMELINE
                </a>
                <span class="tiny-caps text-secondary d-none d-md-inline uppercase fw-700">
                    Synchronisation Auto : Les modifications sont enregistrées instantanément.
                </span>
            </div>
        </div>
    </form>
</div>

<style>
    :root { --accent: #ffed00; --executed-green: #00ff88; --panel-bg: #0d0d0d; }
    .scanner-stat-card { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--accent); padding: 25px; border-radius: 15px; }
    .border-info { border-left-color: #00d2ff !important; }
    .user-avatar-hex { position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: rgba(255,255,255,0.1); stroke-width: 4px; z-index: 3; }
    .hex-active .hex-svg { stroke: var(--executed-green); filter: drop-shadow(0 0 8px var(--executed-green)); }
    .hex-text { color: white; font-weight: 900; z-index: 2; }
    .badge-executed { color: var(--executed-green); font-size: 10px; }
    .badge-standby { color: rgba(255,255,255,0.2); font-size: 10px; }
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; overflow: hidden; border: 1px solid rgba(255,255,255,0.03); }
    .terminal-header { display: grid; padding: 20px 30px; background: rgba(255,255,255,0.02); color: rgba(255,255,255,0.3); }
    .terminal-row { display: grid; align-items: center; padding: 25px 30px; border-bottom: 1px solid rgba(255,255,255,0.03); }
    .row-executed { background: rgba(0, 255, 136, 0.02) !important; border-left: 3px solid var(--executed-green); }
    .custom-terminal-switch .form-check-input { width: 3.5rem; height: 1.8rem; cursor: pointer; }
    .custom-terminal-switch .form-check-input:checked { background-color: var(--executed-green); border-color: var(--executed-green); }
    .btn-abort { color: rgba(255,255,255,0.4); text-decoration: none; font-size: 10px; }
    .pulse-yellow { animation: pYellow 2s infinite; }
    @keyframes pYellow { 0% { box-shadow: 0 0 0 0 rgba(255,237,0,0.4); } 70% { box-shadow: 0 0 0 10px rgba(255,237,0,0); } 100% { box-shadow: 0 0 0 0 rgba(255,237,0,0); } }
</style>
@endsection