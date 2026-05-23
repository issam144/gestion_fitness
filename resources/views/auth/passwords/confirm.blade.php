@extends('layouts.app')

@section('page_title', 'IDENTITY VERIFICATION')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        
        <!-- Tactical Terminal Card -->
        <div class="login-terminal shadow-neon">
            <div class="sys-id">V-LOG: 5.1.2 // SECURE_AUTH</div>
            
            <div class="terminal-header-strip p-4 text-center border-bottom border-white border-opacity-5">
                <div class="brand-logo" style="font-family: 'Oswald'; font-size: 2.2rem; letter-spacing: 5px;">
                    FIT<span style="color: var(--accent);">PRO</span>
                </div>
                <div class="protocol-status mt-2" style="font-size: 9px; font-weight: 800; color: var(--accent); letter-spacing: 2px;">
                    <span class="status-dot"></span> SECURITY_LOCK: ENABLED
                </div>
            </div>

            <div class="p-4 p-lg-5">
                <div class="text-center mb-5">
                    <div class="user-avatar-hex mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-fingerprint text-accent" style="z-index: 2; font-size: 24px;"></i>
                        <svg class="hex-svg" viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg>
                    </div>
                    <h5 class="fw-900 uppercase letter-spacing-1 m-0 text-white">Identity Check</h5>
                    <p class="text-secondary small mt-2">Authorize access with your secret key to continue.</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Access Key (Password) -->
                    <div class="mb-4">
                        <label class="tiny-caps">Authorized Access Key</label>
                        <input id="password" type="password" class="form-control-tactical w-100 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <span class="text-danger tiny-caps mt-2 d-block" role="alert"><strong><i class="fas fa-exclamation-triangle"></i> {{ $message }}</strong></span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-authorize shadow-lg w-100 py-3 mt-2">
                        <i class="fas fa-shield-alt me-2"></i> AUTHORIZE ACCESS
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-4">
                            <a class="tiny-caps text-secondary text-decoration-none hover-white" href="{{ route('password.request') }}">
                                <i class="fas fa-key me-1"></i> Forgot your access key?
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    :root { --accent: #ffed00; --panel-bg: #0d0d0d; }
    
    .fw-900 { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .tiny-caps { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.4); margin-bottom: 8px; display: block; }

    /* Terminal UI */
    .login-terminal { background: var(--panel-bg); border: 1px solid rgba(255,255,255,0.07); border-radius: 25px; position: relative; overflow: hidden; }
    .sys-id { position: absolute; top: 10px; right: 15px; font-size: 7px; color: rgba(255,255,255,0.2); font-family: monospace; }
    
    .form-control-tactical {
        background: rgba(0,0,0,0.4) !important; border: 1px solid rgba(255,255,255,0.06) !important;
        border-radius: 10px !important; color: #fff !important; padding: 12px 16px !important;
        font-weight: 600 !important; font-size: 14px !important; transition: 0.3s;
    }
    .form-control-tactical:focus { border-color: var(--accent) !important; background: #000 !important; outline: none; box-shadow: 0 0 15px rgba(255,237,0,0.1); }

    .btn-authorize { background: var(--accent); color: #000; border: none; border-radius: 12px; font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
    .btn-authorize:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(255,255,255,0.1); }

    /* Hexagon & Effects */
    .user-avatar-hex { position: relative; display: flex; align-items: center; justify-content: center; }
    .hex-svg { position: absolute; inset: 0; fill: transparent; stroke: var(--accent); stroke-width: 4px; filter: drop-shadow(0 0 5px var(--accent)); }
    
    .status-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; display: inline-block; margin-right: 6px; box-shadow: 0 0 10px var(--accent); animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    .shadow-neon { box-shadow: 0 50px 100px rgba(0,0,0,0.9); }
    .hover-white:hover { color: #fff !important; }
</style>
@endsection