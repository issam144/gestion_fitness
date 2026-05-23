@extends('layouts.app')

@section('page_title', 'DÉPLOIEMENT DE L\'UNITÉ D\'ÉLITE')

@section('content')
<div class="container-fluid py-4 px-lg-5 dashboard-main">
    
    @php
        // Logic dyal tsawer kbar l-kola sport (Auto-selection)
        $name = strtoupper($sport->nom);
        $sportImg = match(true) {
            str_contains($name, 'NATATION')    => 'https://images.unsplash.com/photo-1519315901367-f34ff9154487?q=80&w=2070',
            str_contains($name, 'CARDIO')      => 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?q=80&w=1200',
            str_contains($name, 'MUSCU')       => 'https://images.unsplash.com/photo-1549476464-37392f717541?q=80&w=2070',
            str_contains($name, 'CROSS')       => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=1200',
            str_contains($name, 'YOGA')        => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=1200',
            str_contains($name, 'BOXE')        => 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?q=80&w=2070',
            default => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1200'
        };
    @endphp

    <!-- 1. Operational HUD Header -->
    <div class="row mb-5 bg-black bg-opacity-40 rounded-4 border-accent-dim shadow-neon overflow-hidden g-0">
        <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
            <div class="d-flex align-items-center mb-3">
                <div class="status-indicator-pulse me-3"></div>
                <h6 class="text-accent tiny-caps m-0 letter-spacing-xs uppercase">IDENTIFICATION DE L'UNITÉ TACTIQUE</h6>
            </div>
            <h1 class="fw-900 text-white m-0 uppercase display-3">UNITÉ <span style="color: var(--accent);">{{ $sport->nom }}</span></h1>
            <p class="text-secondary tiny-caps mt-3 mb-0 fw-700 uppercase">Statut : {{ $hasAccess ? 'AUTORISÉ' : 'ACCÈS LIMITÉ' }} // Protocole {{ $sport->nom }}</p>
        </div>
        
        <div class="col-md-6">
            <div class="big-sport-frame">
                <img src="{{ $sportImg }}" class="big-sport-img" alt="{{ $sport->nom }}">
                <div class="scan-line-horizontal"></div>
                <div class="corner-tl-big"></div>
                <div class="corner-br-big"></div>
                <div class="vignette-overlay"></div>
            </div>
        </div>
    </div>

    <!-- 2. Command Bar -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <form action="{{ url()->current() }}" method="GET">
                <div class="neo-search-bar">
                    <i class="fas fa-fingerprint"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="uppercase fw-700" placeholder="IDENTIFIER UN COMMANDANT PAR NOM...">
                    <button type="submit" class="btn-scan-action fw-900 uppercase shadow-neon">SCANNER</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Grid of Coach Tactical Units -->
    <div class="row g-4">
        @forelse($coachs as $coach)
        <div class="col-lg-3 col-md-6">
            <div class="validation-terminal p-4 text-center h-100 border-accent-dim hover-glow">
                
                <div class="user-avatar-hex mx-auto mb-4" style="width: 100px; height: 100px;">
                    <div class="hex-image-wrap">
                        @if($coach->image)
                            <img src="{{ asset('storage/'.$coach->image) }}" class="hex-img" alt="Commandant">
                        @else
                            <div class="flex items-center justify-center h-100 bg-dark bg-opacity-50">
                                <i class="fas fa-user-tie text-accent" style="font-size: 30px;"></i>
                            </div>
                        @endif
                    </div>
                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    <div class="scan-line-mini"></div>
                </div>

                <h5 class="text-white fw-900 mb-1 uppercase letter-spacing-xs">{{ $coach->user->name }}</h5>
                
                <div class="mb-2">
                    @php $rating = round($coach->rating ?? 0); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $rating ? 'text-accent' : 'text-secondary opacity-50' }}" style="font-size: 11px;"></i>
                    @endfor
                </div>

                <span class="badge-status-elite active mb-3 d-inline-block uppercase fw-900">Spécialiste {{ $coach->specialite }}</span>
                
                <div class="tactical-divider mx-auto mb-4"></div>

                <div class="d-grid gap-2">
                    <button class="btn-quick-ops py-2 uppercase fw-900" data-bs-toggle="modal" data-bs-target="#intelModal{{ $coach->id }}">
                        <i class="fas fa-id-card me-2"></i> INFOS UNITÉ
                    </button>

                    {{-- --- LOGIC DIAL ACCESS CONTROL --- --}}
                    @if($hasAccess)
                        <form action="{{ route('client.join.coach', $coach->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-scan-action w-100 py-2 uppercase fw-900" style="font-size: 10px;">
                                <i class="fas fa-user-plus me-2"></i> DÉPLOYER AVEC L'INSTRUCTEUR
                            </button>
                        </form>
                    @else
                        <a href="{{ route('client.plans') }}" class="btn-lock w-100 py-2 uppercase fw-900 text-decoration-none text-center" style="font-size: 10px;">
                            <i class="fas fa-lock me-2"></i> ACCÈS REFUSÉ - UPGRADE REQUIS
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- MODAL INFO & PROFILE HUD -->
        <div class="modal fade" id="intelModal{{ $coach->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-accent shadow-neon-strong" style="background: #080808; border-radius: 0; border: 2px solid var(--accent);">
                    
                    <div class="modal-header border-0 p-4 pb-0">
                        <div class="d-flex align-items-center">
                            <div class="status-indicator-pulse me-2"></div>
                            <h6 class="tiny-caps text-accent m-0 fw-900">DOSSIER CLASSIFIÉ // COMMANDANT {{ $coach->user->name }}</h6>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-md-5 text-center border-end border-white-5">
                                <div class="user-avatar-hex mx-auto mb-4" style="width: 160px; height: 160px;">
                                    <div class="hex-image-wrap">
                                        @if($coach->image)
                                            <img src="{{ asset('storage/'.$coach->image) }}" class="hex-img">
                                        @else
                                            <i class="fas fa-user-tie text-accent mt-5" style="font-size: 60px;"></i>
                                        @endif
                                    </div>
                                    <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                                </div>
                                
                                <div class="rating-display-hud mb-4">
                                    <p class="tiny-caps text-secondary mb-1 fw-900">RATING OPÉRATIONNEL</p>
                                    <h2 class="text-accent fw-900 m-0"><i class="fas fa-star me-2"></i>{{ number_format($coach->rating, 1) }}/5</h2>
                                </div>

                                <form action="{{ route('client.rate.coach', $coach->id) }}" method="POST" class="p-3 bg-black border border-accent-dim">
                                    @csrf
                                    <p class="tiny-caps text-white mb-2 fw-900 text-center uppercase">Mettre à jour le signal</p>
                                    <div class="btn-group w-100" role="group">
                                        @for($i=1; $i<=5; $i++)
                                            <input type="radio" class="btn-check" name="stars" id="star{{ $i }}-{{ $coach->id }}" value="{{ $i }}" {{ round($coach->rating) == $i ? 'checked' : '' }}>
                                            <label class="btn btn-outline-accent btn-sm fw-900" for="star{{ $i }}-{{ $coach->id }}">{{ $i }}</label>
                                        @endfor
                                    </div>
                                    <button type="submit" class="btn-scan-action w-100 mt-2 py-1 tiny-caps fw-900 uppercase">VALIDER</button>
                                </form>
                            </div>

                            <div class="col-md-7">
                                <div class="stats-grid-hud mb-4">
                                    <div class="stat-box-hud">
                                        <span class="tiny-caps text-secondary d-block uppercase">Expérience</span>
                                        <span class="text-white h4 fw-900">{{ $coach->experience ?? '5' }} ANS</span>
                                    </div>
                                    <div class="stat-box-hud">
                                        <span class="tiny-caps text-secondary d-block uppercase">Recrues</span>
                                        <span class="text-white h4 fw-900">+150</span>
                                    </div>
                                </div>

                                <div class="bio-section-hud mb-4">
                                    <h6 class="tiny-caps text-accent fw-900 mb-2 underline-accent"><i class="fas fa-terminal me-2"></i>PROTOCOLE DE MISSION</h6>
                                    <p class="text-white-50 small letter-spacing-xs" style="line-height: 1.6;">
                                        {{ $coach->bio ?? "Spécialiste de haut niveau dédié à l'optimisation des performances physiques. Maître des protocoles tactiques dans le secteur " . $sport->nom . "." }}
                                    </p>
                                </div>

                                <div class="contact-box-hud p-3 bg-black border-start border-accent border-3">
                                    <p class="tiny-caps text-secondary mb-1 fw-900 uppercase">Signature de Contact</p>
                                    <p class="text-white fw-900 m-0 letter-spacing-xs h5"><i class="fas fa-phone-alt text-accent me-2"></i> {{ $coach->telephone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4">
                        <button type="button" class="btn-scan-action w-100 py-3 fw-900 uppercase" data-bs-dismiss="modal">FERMER LE FLUX</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-secondary uppercase fw-900 letter-spacing-xs">AUCUN COMMANDANT DÉTECTÉ.</h5>
        </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center custom-pagination">
        {{ $coachs->links() }}
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    body { background: #050505; font-family: 'Oswald', sans-serif; }
    
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* BIG SPORT IMAGE */
    .big-sport-frame { position: relative; width: 100%; height: 380px; background: #000; overflow: hidden; }
    .big-sport-img { width: 100%; height: 100%; object-fit: cover; opacity: 0.9; }
    .vignette-overlay { position: absolute; inset: 0; background: linear-gradient(90deg, #0d0d0d 0%, transparent 40%, transparent 100%); }

    /* BUTTON LOCK STYLE */
    .btn-lock { background: rgba(255, 0, 0, 0.1); color: #ff4d4d; border: 1px solid #ff4d4d; border-radius: 8px; transition: 0.3s; }
    .btn-lock:hover { background: #ff4d4d; color: #000; box-shadow: 0 0 15px #ff4d4d; }

    /* Tactical Cards */
    .validation-terminal { background: var(--panel-bg); border-radius: 25px; border: 1px solid rgba(255,255,255,0.03); transition: 0.4s; }
    .hover-glow:hover { border-color: var(--accent); transform: translateY(-10px); box-shadow: 0 0 20px rgba(255,237,0,0.2); }
    
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); z-index: 3; }
    .hex-image-wrap { position: absolute; width: 88%; height: 88%; clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%); z-index: 2; overflow: hidden; background: #1a1a1a; }
    .hex-img { width: 100%; height: 100%; object-fit: cover; }

    /* MODAL HUD */
    .shadow-neon-strong { box-shadow: 0 0 40px rgba(255, 237, 0, 0.15); }
    .stats-grid-hud { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .stat-box-hud { background: rgba(255, 255, 255, 0.03); padding: 15px; border: 1px solid rgba(255, 255, 255, 0.05); text-align: center; }
    .underline-accent { border-bottom: 2px solid var(--accent); display: inline-block; padding-bottom: 3px; }
    .btn-outline-accent { color: var(--accent); border-color: rgba(255, 237, 0, 0.3); }
    .btn-check:checked + .btn-outline-accent { background-color: var(--accent); color: #000; box-shadow: 0 0 10px var(--accent); }

    /* UI Details */
    .neo-search-bar { background: var(--panel-bg); border-radius: 12px; padding: 5px 25px; display: flex; align-items: center; border: 1px solid rgba(255,255,255,0.05); }
    .neo-search-bar input { background: transparent; border: none; color: white; width: 100%; font-size: 13px; outline: none; margin-left: 10px; font-weight: 700; }
    .btn-scan-action { background: var(--accent); color: #000; border: none; padding: 8px 20px; border-radius: 8px; transition: 0.3s; cursor: pointer; }
    .btn-quick-ops { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px; }
    .status-indicator-pulse { width: 10px; height: 10px; background: var(--accent); border-radius: 50%; animation: pGlow 2s infinite; }
    @keyframes pGlow { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.3); } }
</style>
@endsection