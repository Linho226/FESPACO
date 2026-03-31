<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration FESPACO')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
                                                        /* Correctif visibilité extrême pour les lignes du tableau projections (états) */
                                                        .admin-main .table tr.table-success > *,
                                                        .admin-main .table tr.table-warning > *,
                                                        .admin-main .table tr.table-success a,
                                                        .admin-main .table tr.table-warning a,
                                                        .admin-main .table tr.table-success span,
                                                        .admin-main .table tr.table-warning span,
                                                        .admin-main .table tr.table-success small,
                                                        .admin-main .table tr.table-warning small,
                                                        .admin-main .table tr.table-success button,
                                                        .admin-main .table tr.table-warning button {
                                                            color: #1a1a1a !important;
                                                        }
                                                        .admin-main .table tr.table-success {
                                                            background-color: #b6f5c9 !important;
                                                        }
                                                        .admin-main .table tr.table-warning {
                                                            background-color: #fff7b3 !important;
                                                        }
                                                        .admin-main .table tr.table-success .btn-danger,
                                                        .admin-main .table tr.table-warning .btn-danger {
                                                            color: #fff !important;
                                                        }
                                                        .admin-main .table tr.table-success .btn-warning,
                                                        .admin-main .table tr.table-warning .btn-warning {
                                                            color: #fff !important;
                                                        }
                                                        .admin-main .table tr.table-success .badge,
                                                        .admin-main .table tr.table-warning .badge {
                                                            color: #fff !important;
                                                        }
                                                /* Contraste renforcé pour dashboard et widgets */
                                                .dashboard-header h1,
                                                .stat-content p,
                                                .stat-content h3,
                                                .period-label,
                                                .frequency-label,
                                                .frequency-value,
                                                .film-title,
                                                .card-title,
                                                .actualite-title,
                                                .projection-badge,
                                                .upcoming-date strong,
                                                .upcoming-time,
                                                .upcoming-film .film-title {
                                                    color: var(--app-text) !important;
                                                }
                                                .actualite-desc,
                                                .period-count,
                                                .projection-period-item .period-count,
                                                .most-projected-list .text-muted,
                                                .actualite-date,
                                                .upcoming-film small,
                                                .text-muted {
                                                    color: var(--muted-text) !important;
                                                }
                                        /* Contraste renforcé pour tous les labels et aides de formulaire */
                                        .form-label,
                                        label,
                                        .form-text {
                                            color: var(--app-text) !important;
                                        }
                                        .text-muted {
                                            color: var(--muted-text) !important;
                                        }
                                /* Correction contraste pour .realisateur-admin */
                                .realisateur-admin h3,
                                .realisateur-admin strong,
                                .realisateur-admin p {
                                    color: var(--app-text);
                                }
                                /* Correction contraste pour .acteur-admin */
                                .acteur-admin h3,
                                .acteur-admin strong,
                                .acteur-admin p {
                                    color: var(--app-text);
                                }
                                /* Correction contraste pour .films-admin valeurs */
                                .films-admin strong,
                                .films-admin p {
                                    color: var(--app-text);
                                }
                        :root {
                    /* Correction contraste labels, textes et placeholders */
                    .admin-main label,
                    .admin-main .form-label,
                    .admin-main .form-check-label {
                        color: var(--app-text);
                    }
                    .admin-main .form-control::placeholder {
                        color: var(--muted-text);
                        opacity: 1;
                    }
                    .admin-main .text-muted {
                        color: var(--muted-text) !important;
                    }
            color-scheme: light;
            --sidebar-width: 272px;
            --sidebar-collapsed-width: 92px;
            --sidebar-bg-start: #111827;
            --sidebar-bg-end: #1f2937;
            --sidebar-text: #d8e1ec;
            --sidebar-accent-soft: rgba(255, 255, 255, .1);
            --app-bg: #e9eef4;
            --app-text: #132033;
            --surface: #f7f9fc;
            --surface-border: rgba(15, 23, 42, .08);
            --surface-shadow: 0 14px 30px rgba(15, 23, 42, .08);
            --muted-text: #5f6b7a;
            --table-head-bg: rgba(17, 24, 39, .06);
            --table-head-text: #1b2432;
            --table-border: rgba(17, 24, 39, .12);
            --table-zebra: rgba(17, 24, 39, .025);
            --table-row-hover: rgba(15, 23, 42, .06);
            --table-row-bg: #ffffff;
            --table-text: #182537;
            --field-bg: #ffffff;
            --field-text: #132033;
            --field-border: rgba(15, 23, 42, .18);
            --topbar-bg: rgba(17, 24, 39, .92);
            --btn-hover-shadow: 0 6px 12px rgba(15, 23, 42, .12);
        }
        @media (prefers-color-scheme: dark) {
            :root {
                color-scheme: dark;
                --sidebar-bg-start: #101720;
                --sidebar-bg-end: #151f2c;
                --sidebar-text: #d7dfeb;
                --sidebar-accent-soft: rgba(255, 255, 255, .14);
                --app-bg: #0f1722;
                --app-text: #e3eaf4;
                --surface: #151f2b;
                --surface-border: rgba(148, 163, 184, .18);
                --surface-shadow: 0 16px 30px rgba(2, 6, 23, .4);
                --muted-text: #a2b2c8;
                --table-head-bg: #233143;
                --table-head-text: #e4ecf7;
                --table-border: rgba(148, 163, 184, .2);
                --table-zebra: rgba(148, 163, 184, .08);
                --table-row-hover: rgba(148, 163, 184, .14);
                --table-row-bg: #1a2534;
                --table-text: #e7eef9;
                --field-bg: #1a2534;
                --field-text: #e5edf8;
                --field-border: rgba(148, 163, 184, .35);
                --topbar-bg: rgba(16, 23, 32, .95);
                --btn-hover-shadow: 0 8px 16px rgba(2, 6, 23, .42);
            }
        }
        body {
            background: var(--app-bg);
            min-height: 100vh;
            color: var(--app-text);
            overflow-x: hidden;
        }
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg-start), var(--sidebar-bg-end));
            color: #fff;
            padding: 1rem .8rem;
            z-index: 1030;
            overflow-y: auto;
            box-shadow: 14px 0 35px rgba(17, 24, 39, .26);
            transition: width .35s ease, transform .35s ease;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.2) transparent;
        }
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.22);
            border-radius: 999px;
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
            margin-bottom: 1rem;
            padding: .3rem .4rem;
        }
        .admin-sidebar .brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 700;
            font-size: 1.08rem;
            color: #fff;
            text-decoration: none;
            padding: .4rem;
            border-radius: 10px;
            transition: background-color .25s ease;
        }
        .admin-sidebar .brand:hover {
            background: rgba(255,255,255,.06);
        }
        .brand-logo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #ffffff;
            font-weight: 700;
            font-size: .82rem;
            flex-shrink: 0;
            box-shadow: 0 8px 18px rgba(22, 163, 74, .35);
        }
        .sidebar-toggle {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid rgba(255, 255, 255, .2);
            background: rgba(255,255,255,.05);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .25s ease;
        }
        .sidebar-toggle:hover {
            background: rgba(255,255,255,.12);
            transform: translateY(-1px);
        }
        .sidebar-section {
            font-size: .73rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: rgba(216, 225, 236, .62);
            font-weight: 600;
            padding: .6rem .65rem .35rem;
        }
        .admin-sidebar .nav-link {
            color: var(--sidebar-text);
            border-radius: 12px;
            margin-bottom: .24rem;
            padding: .62rem .75rem;
            display: flex;
            align-items: center;
            gap: .72rem;
            font-weight: 500;
            transition: all .25s ease;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: var(--sidebar-accent-soft);
            color: #fff;
            transform: translateX(4px);
        }
        .admin-sidebar .nav-icon {
            width: 27px;
            height: 27px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.08);
            font-size: .85rem;
            flex-shrink: 0;
        }
        .admin-sidebar .nav-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-sidebar .auth-actions {
            margin-top: .7rem;
            padding: .45rem;
            border-radius: 12px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.06);
            transition: opacity .25s ease;
        }
        .admin-sidebar .auth-actions .btn {
            border-radius: 10px;
            font-weight: 600;
        }
        .admin-main {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
            transition: margin-left .35s ease, padding .25s ease;
            animation: adminContentFade .45s ease;
        }
        .admin-main .container-fluid {
            background: var(--surface);
            border-radius: 16px;
            box-shadow: var(--surface-shadow);
            border: 1px solid var(--surface-border);
        }
        .admin-mobile-topbar {
            display: none;
        }
        body.sidebar-collapsed .admin-sidebar {
            width: var(--sidebar-collapsed-width);
        }
        body.sidebar-collapsed .admin-main {
            margin-left: var(--sidebar-collapsed-width);
        }
        body.sidebar-collapsed .brand-text,
        body.sidebar-collapsed .sidebar-section,
        body.sidebar-collapsed .nav-label,
        body.sidebar-collapsed .auth-actions {
            opacity: 0;
            pointer-events: none;
            width: 0;
            overflow: hidden;
        }
        body.sidebar-collapsed .sidebar-header {
            justify-content: center;
        }
        body.sidebar-collapsed .admin-sidebar .brand {
            justify-content: center;
            padding: .4rem 0;
        }
        body.sidebar-collapsed .admin-sidebar .nav-link {
            justify-content: center;
            padding: .62rem 0;
        }
        body.sidebar-collapsed .admin-sidebar .nav-link:hover,
        body.sidebar-collapsed .admin-sidebar .nav-link.active {
            transform: none;
        }
        @keyframes adminContentFade {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-main {
                margin-left: 0;
                padding: .9rem;
            }
            .admin-main .container-fluid {
                border-radius: 14px;
            }
            .admin-mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                backdrop-filter: blur(8px);
                background: var(--topbar-bg);
                color: #fff;
                padding: .7rem .95rem;
                position: sticky;
                top: 0;
                z-index: 1031;
                box-shadow: 0 10px 22px rgba(17, 24, 39, .2);
            }
            .offcanvas.offcanvas-start {
                width: 285px;
                background: linear-gradient(180deg, var(--sidebar-bg-start), var(--sidebar-bg-end));
            }
            .offcanvas .nav-link {
                color: var(--sidebar-text) !important;
                border-radius: 12px;
                padding: .58rem .7rem;
                transition: all .25s ease;
            }
            .offcanvas .nav-link.active,
            .offcanvas .nav-link:hover {
                color: #fff !important;
                background: linear-gradient(135deg, var(--sidebar-accent-soft), rgba(255,255,255,.08));
            }
        }
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
        .admin-main .card {
            border-radius: 14px !important;
            transition: transform .25s ease, box-shadow .25s ease;
            border: 1px solid var(--surface-border);
            background: transparent;
        }
        .admin-main .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--surface-shadow);
        }
        .admin-main .card .card-body {
            border-radius: 14px;
        }
        .admin-main .table {
            border-radius: 12px;
            overflow: hidden;
            border-color: var(--table-border);
            --bs-table-bg: var(--table-row-bg);
            --bs-table-color: var(--table-text);
            --bs-table-border-color: var(--table-border);
            --bs-table-striped-bg: var(--table-zebra);
            --bs-table-striped-color: var(--table-text);
            --bs-table-hover-bg: var(--table-row-hover);
            --bs-table-hover-color: var(--table-text);
            --bs-table-active-bg: var(--table-row-hover);
            --bs-table-active-color: var(--table-text);
        }
        .admin-main .table thead th {
            font-weight: 600;
            letter-spacing: .2px;
            background: var(--table-head-bg);
            color: var(--table-head-text);
            border-bottom-width: 1px;
            border-color: var(--table-border);
        }
        .admin-main .table > :not(caption) > * > * {
            border-color: var(--table-border);
            color: var(--table-text);
            background-color: var(--table-row-bg);
        }
        .admin-main .table tbody tr:nth-child(even) {
            background-color: var(--table-zebra);
        }
        .admin-main .table tbody tr:nth-child(even) > * {
            background-color: var(--table-zebra);
        }
        .admin-main .table tbody tr:hover > * {
            background-color: var(--table-row-hover);
        }
        .admin-main .table-light,
        .admin-main .bg-light,
        .admin-main .table .table-light {
            color: var(--table-text) !important;
            background-color: var(--table-row-bg) !important;
            border-color: var(--table-border) !important;
        }
        .admin-main .table td,
        .admin-main .table th,
        .admin-main .table td span,
        .admin-main .table td small,
        .admin-main .table td a {
            color: var(--table-text);
        }
        .admin-main .table-responsive {
            border-radius: 12px;
            overflow: auto;
            max-height: 72vh;
        }
        .admin-main .table-responsive .table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .admin-main .table tbody tr {
            transition: background-color .2s ease, transform .2s ease;
        }
        .admin-main .table tbody tr:hover {
            background-color: var(--table-row-hover);
        }
        .admin-main .btn {
            border-radius: 10px;
            transition: transform .18s ease, box-shadow .2s ease, filter .2s ease;
        }
        .admin-main .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--btn-hover-shadow);
            filter: none;
        }
        .admin-main .btn:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .admin-main .badge {
            border-radius: 999px;
            font-weight: 600;
        }
        .admin-main .form-control,
        .admin-main .form-select,
        .admin-main .form-check-input {
            border-radius: 10px;
            transition: box-shadow .2s ease, border-color .2s ease;
            background-color: var(--field-bg);
            color: var(--field-text);
            border-color: var(--field-border);
        }
        .admin-main .form-control:disabled,
        .admin-main .form-select:disabled {
            background-color: color-mix(in srgb, var(--field-bg) 80%, #9ca3af);
            color: var(--muted-text);
        }
        .admin-main .form-control::placeholder {
            color: var(--muted-text);
        }
        .admin-main .form-control:focus,
        .admin-main .form-select:focus,
        .admin-main .form-check-input:focus {
            border-color: rgba(34, 197, 94, .5);
            box-shadow: 0 0 0 .2rem rgba(34, 197, 94, .18);
        }
        .admin-main .text-muted {
            color: var(--muted-text) !important;
        }
    </style>
</head>
<body>
    <div class="admin-mobile-topbar d-lg-none">
        <span class="fw-bold">FESPACO Admin</span>
        <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile" aria-controls="adminSidebarMobile">Menu</button>
    </div>

    <aside class="admin-sidebar d-none d-lg-block">
        <div class="sidebar-header">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-logo">F</span>
                <span class="brand-text">FESPACO Admin</span>
            </a>
            <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Réduire la sidebar">⟨</button>
        </div>
        <div class="sidebar-section">Navigation</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-label">Tableau de Bord</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.films.*') ? 'active' : '' }}" href="{{ route('admin.films.index') }}">
                    <span class="nav-icon">🎬</span>
                    <span class="nav-label">Films</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.acteurs.*') ? 'active' : '' }}" href="{{ route('admin.acteurs.index') }}">
                    <span class="nav-icon">🎭</span>
                    <span class="nav-label">Acteurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.realisateurs.*') ? 'active' : '' }}" href="{{ route('admin.realisateurs.index') }}">
                    <span class="nav-icon">🎥</span>
                    <span class="nav-label">Réalisateurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.projections.*') ? 'active' : '' }}" href="{{ route('admin.projections.index') }}">
                    <span class="nav-icon">📽️</span>
                    <span class="nav-label">Projections</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.actualites.*') ? 'active' : '' }}" href="{{ route('admin.actualites.index') }}">
                    <span class="nav-icon">📰</span>
                    <span class="nav-label">Actualités</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.galeries.*') ? 'active' : '' }}" href="{{ route('admin.galeries.index') }}">
                    <span class="nav-icon">🖼️</span>
                    <span class="nav-label">Galerie</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                    <span class="nav-icon">✉️</span>
                    <span class="nav-label">Messages</span>
                </a>
            </li>
            <li class="nav-item auth-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm w-100">Connexion</a>
                @endguest
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-100">Déconnexion</button>
                    </form>
                @endauth
            </li>
        </ul>
    </aside>

    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="adminSidebarMobile" aria-labelledby="adminSidebarMobileLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="adminSidebarMobileLabel">FESPACO Admin</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">🏠 Tableau de Bord</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.films.*') ? 'active' : '' }}" href="{{ route('admin.films.index') }}">🎬 Films</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.acteurs.*') ? 'active' : '' }}" href="{{ route('admin.acteurs.index') }}">🎭 Acteurs</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.realisateurs.*') ? 'active' : '' }}" href="{{ route('admin.realisateurs.index') }}">🎥 Réalisateurs</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.projections.*') ? 'active' : '' }}" href="{{ route('admin.projections.index') }}">📽️ Projections</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.actualites.*') ? 'active' : '' }}" href="{{ route('admin.actualites.index') }}">📰 Actualités</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.galeries.*') ? 'active' : '' }}" href="{{ route('admin.galeries.index') }}">🖼️ Galerie</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">✉️ Messages</a></li>
                <li class="nav-item mt-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm w-100">Connexion</a>
                    @endguest
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm w-100">Déconnexion</button>
                        </form>
                    @endauth
                </li>
            </ul>
        </div>
    </div>

    <div class="admin-main">
        <div class="container-fluid py-3">
        @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const toggleButton = document.getElementById('sidebarToggle');
            const desktopBreakpoint = window.matchMedia('(min-width: 992px)');
            const storageKey = 'admin-sidebar-collapsed';

            const applyState = (collapsed) => {
                document.body.classList.toggle('sidebar-collapsed', collapsed && desktopBreakpoint.matches);
                if (toggleButton) {
                    toggleButton.textContent = collapsed ? '⟩' : '⟨';
                }
            };

            const savedState = localStorage.getItem(storageKey) === '1';
            applyState(savedState);

            if (toggleButton) {
                toggleButton.addEventListener('click', () => {
                    const nextCollapsed = !document.body.classList.contains('sidebar-collapsed');
                    applyState(nextCollapsed);
                    localStorage.setItem(storageKey, nextCollapsed ? '1' : '0');
                });
            }

            desktopBreakpoint.addEventListener('change', () => {
                const isCollapsed = localStorage.getItem(storageKey) === '1';
                applyState(isCollapsed);
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
