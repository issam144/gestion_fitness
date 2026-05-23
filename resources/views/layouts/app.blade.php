<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIT PRO | CENTRE DE COMMANDEMENT</title>
    
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Oswald:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo-v2.png') }}?v={{ time() }}">
    
    <style>
        :root { 
            --accent: #ffed00; 
            --bg-deep: #080808;
            --sidebar-bg: #080808;
            --card-dark: #111111;
            --border-tactical: rgba(255, 255, 255, 0.05);
        }

        body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-deep); color: #fff; min-height: 100vh; overflow-x: hidden; }

        .mesh-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -2; background-color: #080808; background-image: radial-gradient(at 0% 0%, rgba(255, 237, 0, 0.05) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.03) 0px, transparent 50%); }
        .wrapper { display: flex; width: 100%; align-items: stretch; }

        /* --- SIDEBAR STYLE --- */
        #sidebar { 
            min-width: 280px; 
            max-width: 280px; 
            background: var(--sidebar-bg); 
            border-right: 1px solid var(--border-tactical); 
            min-height: 100vh; 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        /* Mode réduit (collapsed) */
        #sidebar.collapsed { min-width: 85px; max-width: 85px; }

        #sidebar .sidebar-header { 
            padding: 40px 20px; 
            text-align: center; 
            font-family: 'Oswald', sans-serif; 
            font-size: 1.8rem; 
            letter-spacing: 5px; 
            border-bottom: 1px solid var(--border-tactical); 
            color: #fff; /* FIT en blanc */
            white-space: nowrap;
            overflow: hidden;
        }

        /* Correction Couleur PRO en Jaune */
        #sidebar .sidebar-header span { 
            color: var(--accent) !important; 
            text-shadow: 0 0 15px rgba(255, 237, 0, 0.3); 
        }

        #sidebar.collapsed .sidebar-header { font-size: 1.2rem; letter-spacing: 2px; padding: 40px 10px; }

        #sidebar ul li a { padding: 14px 25px; display: flex; align-items: center; color: rgba(255,255,255,0.4); text-decoration: none; transition: 0.3s; position: relative; }
        
        /* Cacher le texte quand c'est réduit */
        #sidebar.collapsed ul li a span { display: none; }
        #sidebar.collapsed ul li a { justify-content: center; padding: 14px 0; }

        #sidebar ul li a span { 
            font-size: 10px !important; 
            font-weight: 800 !important; 
            text-transform: uppercase !important; 
            letter-spacing: 1.5px !important; 
        }

        #sidebar ul li a:hover, #sidebar ul li a.active { color: var(--accent); background: rgba(255, 237, 0, 0.02); }
        #sidebar ul li a.active::after { content: ''; position: absolute; left: 0; top: 20%; height: 60%; width: 4px; background: var(--accent); box-shadow: 0 0 10px var(--accent); }

        .nav-icon-hex { position: relative; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: 0.3s; flex-shrink: 0; }
        #sidebar:not(.collapsed) .nav-icon-hex { margin-right: 15px; }

        .nav-icon-hex i { font-size: 13px; z-index: 2; }
        .nav-icon-hex svg { position: absolute; inset: 0; fill: transparent; stroke: currentColor; stroke-width: 5px; opacity: 0.2; }
        #sidebar ul li a.active .nav-icon-hex svg { opacity: 1; filter: drop-shadow(0 0 5px var(--accent)); }

        .navbar-custom { background: #000; border-bottom: 1px solid var(--border-tactical); padding: 15px 40px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        
        /* Bouton Toggle Styles */
        .sidebar-toggle-btn {
            background: none; border: none; color: var(--accent); font-size: 1.2rem; cursor: pointer; margin-right: 20px; transition: 0.3s;
        }
        .sidebar-toggle-btn:hover { transform: scale(1.1); }

        .hud-title { font-family: 'Oswald'; font-size: 14px; letter-spacing: 2px; color: var(--accent); margin: 0; text-transform: uppercase; }
        .content { width: 100%; background: var(--bg-deep); transition: all 0.3s; }
        .logout-link { color: #ff3e3e !important; margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px !important; }

        /* --- NOTIFICATIONS ENHANCED DESIGN --- */
        .notification-btn { 
            color: rgba(255,255,255,0.7); 
            position: relative; 
            width: 45px; height: 45px; 
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.03); 
            border-radius: 10px; 
            border: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s; cursor: pointer; 
        }
        .notification-btn:hover { background: rgba(255, 237, 0, 0.05); color: var(--accent); }

        .notif-badge { 
            position: absolute; top: -5px; right: -5px; 
            background: #ff3e3e; color: #fff; font-size: 9px; font-weight: 900; 
            min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; 
            border-radius: 6px; border: 2px solid #000; box-shadow: 0 0 15px rgba(255, 62, 62, 0.4); 
        }

        .dropdown-menu-dark-tactical { 
            background: #0d0d0d !important; 
            border: 1px solid rgba(255, 237, 0, 0.15) !important; 
            width: 400px; padding: 0; border-radius: 12px; 
            box-shadow: 0 25px 80px rgba(0,0,0,0.9); margin-top: 15px !important; 
        }

        .notif-header { padding: 18px; background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-tactical); font-family: 'Oswald'; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; font-size: 11px; text-align: center; }
        
        .notif-item { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 18px; transition: 0.3s; text-decoration: none !important; }
        .notif-item:hover { background: rgba(255, 255, 255, 0.02); }

        .notif-icon-box { 
            width: 42px; height: 42px; border-radius: 10px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 16px; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.05); 
        }

        .notif-title-blue { color: #00a8ff !important; font-weight: 800; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; }
        .notif-title-yellow { color: var(--accent) !important; font-weight: 800; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; }
        .notif-title-red { color: #ff3e3e !important; font-weight: 800; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; }

        .notif-msg { display: block; font-size: 12px; color: rgba(255,255,255,0.85) !important; line-height: 1.5; font-weight: 500; margin-top: 4px; }
        .notif-time { display: block; font-size: 8px; color: rgba(255,255,255,0.3); margin-top: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: #222; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">FIT<span>PRO</span></div>
            <ul class="list-unstyled mt-4">
                @auth
                    @php $user = auth()->user(); @endphp

                    @if($user->role == 'admin')
                        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-th-large"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Tableau de bord</span></a></li>
                        <li><a href="{{ route('admin.scanner') }}" class="{{ request()->routeIs('admin.scanner') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-qrcode"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Radar Pointage</span></a></li>
                        <li><a href="{{ route('admin.pending') }}" class="{{ request()->routeIs('admin.pending') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-user-check"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Validations</span></a></li>
                        <li><a href="{{ route('admin.coachs.index') }}" class="{{ request()->routeIs('admin.coachs.*') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-user-shield"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Gestion Coachs</span></a></li>
                        <li><a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-users"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Gestion Membres</span></a></li>
                        <li><a href="{{ route('admin.seances.index') }}" class="{{ request()->routeIs('admin.seances.*') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-calendar-alt"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Gestion Séances</span></a></li>
                        <li><a href="{{ route('admin.type-seances.index') }}" class="{{ request()->routeIs('admin.type-seances.*') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-layer-group"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Catégories</span></a></li>
                        <li><a href="{{ route('admin.abonnements.index') }}" class="{{ request()->routeIs('admin.abonnements.*') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-id-card"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Abonnements</span></a></li>
                        <li><a href="{{ route('admin.type-abonnements.index') }}" class="{{ request()->routeIs('admin.type-abonnements.*') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-tags"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Tarifs</span></a></li>
                        <li><a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-user-circle"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Mon Profil</span></a></li>

                    @elseif($user->role == 'coach')
                        <li><a href="{{ route('coach.dashboard') }}" class="{{ request()->routeIs('coach.dashboard') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-terminal"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Tableau de bord</span></a></li>
                        <li><a href="{{ route('coach.presences_hub') }}" class="{{ request()->routeIs('coach.presences_hub') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-clipboard-check"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Hub Présences</span></a></li>
                        <li><a href="{{ route('coach.seances') }}" class="{{ request()->routeIs('coach.seances') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-stopwatch"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Mes Séances</span></a></li>
                        <li><a href="{{ route('coach.members') }}" class="{{ request()->routeIs('coach.members') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-users-cog"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Mes Membres</span></a></li>
                        <li><a href="{{ route('coach.profile') }}" class="{{ request()->routeIs('coach.profile') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-user-circle"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Mon Profil</span></a></li>

                    @elseif($user->role == 'client')
                        <li><a href="{{ route('client.dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-home"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Accueil / Pass</span></a></li>
                        <li><a href="{{ route('client.planning') }}" class="{{ request()->routeIs('client.planning') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-calendar-alt"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Planification</span></a></li>
                        <li><a href="{{ route('client.sports') }}" class="{{ request()->routeIs('client.sports') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-dumbbell"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Mes Sports</span></a></li>
                        <li><a href="{{ route('client.history') }}" class="{{ request()->routeIs('client.history') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-history"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Historique</span></a></li>
                        <li><a href="{{ route('client.profile') }}" class="{{ request()->routeIs('client.profile') ? 'active' : '' }}"><div class="nav-icon-hex"><i class="fas fa-user-circle"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div><span>Mon Profil</span></a></li>
                    @endif

                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-link">
                            <div class="nav-icon-hex"><i class="fas fa-power-off"></i><svg viewBox="0 0 100 100"><polygon points="50 1, 95 25, 95 75, 50 99, 5 75, 5 25" /></svg></div>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>

        <div class="content">
            <div class="navbar-custom">
                <div class="d-flex align-items-center">
                    <!-- Toggle Button Added Here -->
                    <button id="sidebarCollapse" class="sidebar-toggle-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="hud-title">@yield('page_title', 'FIT PRO QG')</h4>
                </div>
                
                <div class="dropdown">
                    <div class="notification-btn px-3" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notif-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </div>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark-tactical shadow-lg" aria-labelledby="notifDropdown">
                        <div class="notif-header">CENTRE DE NOTIFICATIONS</div>
                        <div style="max-height: 450px; overflow-y: auto;">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                @php 
                                    $type = $notification->data['type'] ?? 'default';
                                    $isReg = ($type == 'registration_request');
                                    $isAbsence = ($type == 'absence_signal');
                                @endphp
                                <a href="{{ $isReg ? route('admin.pending') : '#' }}" class="notif-item">
                                    <div class="notif-icon-box" style="background: {{ $isReg ? 'rgba(0, 168, 255, 0.1)' : ($isAbsence ? 'rgba(255, 62, 62, 0.1)' : 'rgba(255, 237, 0, 0.1)') }}; color: {{ $isReg ? '#00a8ff' : ($isAbsence ? '#ff3e3e' : '#ffed00') }}; border-color: {{ $isReg ? 'rgba(0, 168, 255, 0.2)' : ($isAbsence ? 'rgba(255, 62, 62, 0.2)' : 'rgba(255, 237, 0, 0.2)') }};">
                                        <i class="fas {{ $isReg ? 'fa-user-plus' : ($isAbsence ? 'fa-exclamation-triangle' : 'fa-bolt') }}"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="{{ $isReg ? 'notif-title-blue' : ($isAbsence ? 'notif-title-red' : 'notif-title-yellow') }}">
                                            {{ $isReg ? 'NOUVEL ACCÈS' : ($isAbsence ? 'ALERTE ABSENCE' : 'MISE À JOUR') }}
                                        </span>
                                        <span class="notif-msg mt-1">{{ $notification->data['message'] ?? 'Signal entrant détecté' }}</span>
                                        <span class="notif-time"><i class="far fa-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="p-5 text-center text-muted small uppercase letter-spacing-lg">Aucun signal entrant</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-lg-5 pb-5">@yield('content')</div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Mémoriser l'état du menu
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                $('#sidebar').addClass('collapsed');
            }

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('collapsed');
                
                // Enregistrer l'état
                if ($('#sidebar').hasClass('collapsed')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
            });
        });
    </script>
</body>
</html>