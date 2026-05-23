@extends('layouts.app')

@section('page_title', 'CENTRE DE COMMANDEMENT ADMIN')

@section('content')
<div class="container-fluid py-4 px-lg-5">
    
    <div class="row g-4 justify-content-center">
        <!-- 1. Left Column: Tactical Identity Card -->
        <div class="col-lg-4">
            <div class="profile-card-tactical shadow-lg">
                <div class="card-header-line"></div>
                
                <div class="text-center p-5">
                    <!-- Hexagon Profile Picture -->
                    <div class="user-avatar-hex mx-auto mb-4" style="width: 150px; height: 150px;">
                        @if($user->image)
                            <div class="hex-image-wrap">
                                <img src="{{ asset('storage/' . $user->image) }}" class="hex-img" alt="Admin">
                            </div>
                        @else
                            <span class="hex-text fw-900" style="font-size: 3rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                        <svg class="hex-svg" viewBox="0 0 100 100">
                            <polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" />
                        </svg>
                        <div class="admin-crown-pip"><i class="fas fa-crown"></i></div>
                    </div>

                    <h2 class="fw-900 text-white mb-1 uppercase letter-spacing-sm">{{ $user->name }}</h2>
                    <div class="badge-clearance mb-4 uppercase fw-900 letter-spacing-xs">NIVEAU 03 • ADMIN SYSTÈME</div>

                    <!-- Quick Info List -->
                    <div class="tactical-data-list text-start">
                        <div class="data-item">
                            <span class="label uppercase fw-700">Identifiant Réseau</span>
                            <span class="value fw-900 text-white">#ADM-{{ 100 + $user->id }}</span>
                        </div>
                        <div class="data-item">
                            <span class="label uppercase fw-700">Signal E-mail</span>
                            <span class="value fw-900 text-white">{{ strtoupper($user->email) }}</span>
                        </div>
                        <div class="data-item border-0">
                            <span class="label uppercase fw-700">Statut Système</span>
                            <span class="value fw-900 text-success uppercase">Opérationnel</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Right Column: System Configuration Console -->
        <div class="col-lg-8">
            <div class="console-terminal shadow-lg">
                <div class="console-header d-flex align-items-center px-4 py-3">
                    <i class="fas fa-terminal text-accent me-3"></i>
                    <span class="tiny-caps text-white fw-900 letter-spacing-sm">MODIFICATION IDENTITÉ & SÉCURITÉ</span>
                </div>

                @if(session('success'))
                    <div class="alert-success-terminal mx-4 mt-4 uppercase fw-900 letter-spacing-xs">
                        <i class="fas fa-shield-check me-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-4 p-lg-5">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12 mb-2">
                            <h6 class="text-accent tiny-caps border-bottom border-white border-opacity-10 pb-2 fw-900">MARQUEURS PERSONNELS</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="label-tactical uppercase fw-700 letter-spacing-xs">Nom Complet du Commandant</label>
                            <input type="text" name="name" class="form-control input-tactical uppercase fw-900" value="{{ $user->name }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="label-tactical uppercase fw-700 letter-spacing-xs">Adresse Signal E-mail</label>
                            <input type="email" name="email" class="form-control input-tactical uppercase fw-900" value="{{ $user->email }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="label-tactical uppercase fw-700 letter-spacing-xs">Ligne de Communication (Tél)</label>
                            <input type="text" name="phone" class="form-control input-tactical fw-900" value="{{ $user->phone ?? '' }}" placeholder="+212 6 XX XX XX XX">
                        </div>

                        <div class="col-md-6">
                            <label class="label-tactical uppercase fw-700 letter-spacing-xs">Mise à jour Bio-Données (Photo)</label>
                            <input type="file" name="image" class="form-control input-tactical">
                        </div>

                        <!-- Security Section -->
                        <div class="col-12 mt-5 mb-2">
                            <h6 class="text-accent tiny-caps border-bottom border-white border-opacity-10 pb-2 fw-900">CLÉS D'AUTHENTIFICATION (SÉCURITÉ)</h6>
                        </div>

                        <div class="col-12">
                            <label class="label-tactical uppercase fw-700 letter-spacing-xs">Clé Actuelle (Autorisation Requise)</label>
                            <input type="password" name="current_password" class="form-control input-tactical" placeholder="VÉRIFIER L'IDENTITÉ...">
                            @error('current_password') <span class="text-danger tiny-caps mt-1 d-block fw-900">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="label-tactical uppercase fw-700 letter-spacing-xs">Nouvelle Clé de Sécurité</label>
                            <input type="password" name="password" class="form-control input-tactical" placeholder="MIN. 8 CARACTÈRES">
                            @error('password') <span class="text-danger tiny-caps mt-1 d-block fw-900">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="label-tactical uppercase fw-700 letter-spacing-xs">Confirmer la Nouvelle Clé</label>
                            <input type="password" name="password_confirmation" class="form-control input-tactical" placeholder="RÉPÉTER LA CLÉ...">
                        </div>
                    </div>

                    <div class="text-end mt-5">
                        <button type="submit" class="btn-authorize uppercase fw-900 letter-spacing-sm">
                            <span>AUTORISER LA MISE À JOUR SYSTÈME</span>
                            <i class="fas fa-bolt ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700;900&display=swap');

    :root { 
        --accent: #ffed00; 
        --panel-bg: #0d0d0d; 
        --body-bg: #050505;
        --border-tactical: rgba(255, 255, 255, 0.05);
    }
    
    body { background: var(--body-bg); font-family: 'Oswald', sans-serif; }
    
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-lg { letter-spacing: 4px; }
    .letter-spacing-sm { letter-spacing: 2px; }
    .letter-spacing-xs { letter-spacing: 1px; }
    .tiny-caps { font-size: 10px; font-weight: 800; letter-spacing: 2px; }
    .text-accent { color: var(--accent); }

    /* Tactical Card */
    .profile-card-tactical { background: var(--panel-bg); border-radius: 15px; border: 1px solid var(--border-tactical); overflow: hidden; position: relative; }
    .card-header-line { height: 4px; background: var(--accent); width: 100%; box-shadow: 0 0 15px var(--accent); }

    /* Hexagon Avatar */
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 10px rgba(255,237,0,0.4)); z-index: 3; }
    .hex-image-wrap { position: absolute; width: 88%; height: 88%; clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%); overflow: hidden; z-index: 2; }
    .hex-img { width: 100%; height: 100%; object-fit: cover; }
    .hex-text { color: #fff; z-index: 2; }
    .admin-crown-pip { position: absolute; bottom: 5%; right: 10%; background: #000; color: var(--accent); width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid var(--accent); z-index: 10; box-shadow: 0 0 15px rgba(255,237,0,0.4); }

    .badge-clearance { font-size: 10px; color: var(--accent); background: rgba(255, 237, 0, 0.1); padding: 6px 18px; border-radius: 4px; display: inline-block; border: 1px solid rgba(255,237,0,0.2); }

    /* Data List */
    .tactical-data-list { background: rgba(0,0,0,0.3); border-radius: 10px; padding: 20px; border: 1px solid var(--border-tactical); }
    .data-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .data-item .label { font-size: 9px; color: #666; }
    .data-item .value { font-size: 12px; }

    /* Console Styling */
    .console-terminal { background: var(--panel-bg); border-radius: 15px; border: 1px solid var(--border-tactical); overflow: hidden; }
    .console-header { background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-tactical); }

    /* Inputs */
    .label-tactical { color: rgba(255,255,255,0.4); margin-bottom: 8px; margin-left: 5px; }
    .input-tactical { 
        background: #000 !important; border: 1px solid #222 !important; color: #fff !important; 
        border-radius: 8px !important; padding: 15px !important; transition: 0.3s;
        font-family: 'Oswald', sans-serif;
    }
    .input-tactical:focus { border-color: var(--accent) !important; box-shadow: 0 0 20px rgba(255,237,0,0.1) !important; outline: none; }

    /* Button */
    .btn-authorize { 
        background: var(--accent); color: #000; border: none; padding: 18px 40px; 
        border-radius: 8px; transition: 0.4s; cursor: pointer;
    }
    .btn-authorize:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }

    .alert-success-terminal { background: rgba(0, 255, 136, 0.05); color: #00ff88; border: 1px solid rgba(0, 255, 136, 0.2); padding: 15px; border-radius: 8px; text-align: center; }
</style>
@endsection