@extends('layouts.app')

@section('page_title', 'MODIFICATION MEMBRE')

@section('content')
<div class="container-fluid py-4 px-lg-5">

    <!-- 1. En-tête (Header) -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-900 text-white mb-1 uppercase letter-spacing">MODIFIER <span style="color: var(--accent);">L'ADHÉRENT</span></h1>
            <p class="text-secondary small uppercase" style="letter-spacing: 2px; font-size: 10px;">Mise à jour des protocoles d'accès pour le Membre #{{ 1000 + $member->id }}</p>
        </div>
        <a href="{{ route('admin.members.index') }}" class="elite-btn-back">
            <i class="fas fa-arrow-left me-2"></i> RETOUR AU HUB
        </a>
    </div>

    <div class="row">
        <div class="col-12">

            <!-- Terminal Card -->
            <div class="edit-terminal">

                <!-- En-tête du Terminal -->
                <div class="terminal-top-bar">
                    <div class="brand-logo">FIT<span>PRO</span></div>
                    <div class="protocol-status">
                        <span class="status-dot"></span> PROTOCOLE_OVERRIDE: MODIFICATION_EN_COURS
                    </div>
                    <div class="sys-id">MEMBER_ID // {{ 1000 + $member->id }}</div>
                </div>

                <!-- Profil Identity Bar -->
                <div class="coach-identity-bar">
                    <div class="avatar-hex">
                        <span class="avatar-letter" style="z-index: 2; font-weight: 900; color: white; font-size: 1.5rem;">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </span>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <div class="ms-4">
                        <h5 class="m-0 fw-900 text-white uppercase" style="letter-spacing: 2px;">{{ $member->name }}</h5>
                        <span class="text-secondary" style="font-size: 9px; letter-spacing: 2px; text-transform: uppercase;">
                            STATUT ACCÈS : 
                            @if($member->expired_at && \Carbon\Carbon::parse($member->expired_at)->isPast())
                                <span style="color: #ff3e3e;">🔴 ACCÈS REFUSÉ (EXPIRÉ)</span>
                            @else
                                <span style="color: #00ff88;">🟢 ACCÈS AUTORISÉ</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Formulaire Section -->
                <div class="p-4 p-lg-5">
                    <form action="{{ route('admin.members.update', $member->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">

                            <!-- Nom Complet -->
                            <div class="col-md-12">
                                <label class="tiny-caps">Identité Nominale</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-id-card"></i>
                                    <input type="text" name="name" value="{{ old('name', $member->name) }}" required placeholder="Nom complet de l'adhérent" class="uppercase">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="tiny-caps">Adresse E-mail</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-envelope-open-text"></i>
                                    <input type="email" name="email" value="{{ old('email', $member->email) }}" required placeholder="email@client.com" class="uppercase">
                                </div>
                            </div>

                            <!-- رقم الهاتف (الحقل الذي تم إضافته) ✅ -->
                            <div class="col-md-6">
                                <label class="tiny-caps">Numéro de Téléphone</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-phone-alt"></i>
                                    <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" placeholder="06 00 00 00 00" class="uppercase">
                                </div>
                            </div>

                            <!-- Pack/Abonnement -->
                            <div class="col-md-12">
                                <label class="tiny-caps">Niveau d'Accès (Plan d'Abonnement)</label>
                                <div class="neo-input-group mt-2">
                                    <i class="fas fa-crown"></i>
                                    <select name="abonnement_id" required class="uppercase">
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" {{ $member->abonnement_id == $type->id ? 'selected' : '' }} style="background: #111; color: #fff;">
                                                {{ strtoupper($type->nom) }} — {{ number_format($type->prix, 0) }} MAD
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-caret-down caret-icon"></i>
                                </div>
                            </div>

                            <!-- Bouton Submit -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-sync-alt me-2"></i> ENREGISTRER LES MODIFICATIONS
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

    :root { --accent: #ffed00; --panel-bg: #0d0d0d; --input-bg: rgba(0,0,0,0.4); --border-tactical: rgba(255,255,255,0.07); }
    body { background: #050505; font-family: 'Oswald', sans-serif; }
    
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing { letter-spacing: 3px; }
    .tiny-caps { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.4); display: block; }

    /* Terminal Card */
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
    .brand-logo { font-size: 1.4rem; letter-spacing: 4px; font-weight: 800; color: #fff; }
    .brand-logo span { color: var(--accent); }
    .protocol-status { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: var(--accent); }
    .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite; }
    .sys-id { font-size: 7px; color: rgba(255,255,255,0.2); font-family: monospace; }

    .coach-identity-bar {
        background: rgba(255,255,255,0.01);
        border-bottom: 1px solid var(--border-tactical);
        padding: 25px 35px;
        display: flex; align-items: center;
    }
    .avatar-hex { position: relative; width: 65px; height: 65px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }

    .neo-input-group {
        background: var(--input-bg);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 13px 18px;
        display: flex; align-items: center;
        transition: 0.3s;
    }
    .neo-input-group:focus-within { border-color: var(--accent); box-shadow: 0 0 20px rgba(255,237,0,0.08); }
    .neo-input-group i { color: var(--accent); margin-right: 12px; font-size: 13px; }
    .neo-input-group input, .neo-input-group select {
        background: transparent !important; border: none; color: #fff !important;
        width: 100%; font-weight: 600; font-size: 14px; outline: none; appearance: none;
    }
    .caret-icon { color: var(--accent); font-size: 10px; margin-left: 8px; pointer-events: none; }

    .btn-submit {
        background: var(--accent); color: #000; border: none; width: 100%;
        padding: 18px; border-radius: 12px; font-weight: 900; font-size: 13px;
        text-transform: uppercase; letter-spacing: 2px; transition: 0.4s;
        cursor: pointer;
    }
    .btn-submit:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }

    .elite-btn-back { text-decoration: none; color: rgba(255,255,255,0.4); font-size: 10px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; transition: 0.3s; }
    .elite-btn-back:hover { color: var(--accent); }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
</style>
@endsection