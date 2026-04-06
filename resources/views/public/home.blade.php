<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FESPACO - Accueil</title>
    <script>
        (() => {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const applyTheme = () => {
                document.documentElement.setAttribute('data-bs-theme', mediaQuery.matches ? 'dark' : 'light');
            };

            applyTheme();

            if (typeof mediaQuery.addEventListener === 'function') {
                mediaQuery.addEventListener('change', applyTheme);
            } else {
                mediaQuery.addListener(applyTheme);
            }
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --gold: #f5a623;
            --gold-dark: #d4891a;
            --bg-main: #0a0e17;
            --bg-card: #111827;
            --bg-card-hover: #1a2540;
            --border-color: rgba(245, 166, 35, 0.15);
            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-main);
            color: var(--text-main);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* ===== HERO ===== */
        .hero {
            position: relative;
            min-height: 92vh;
            background: url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                160deg,
                rgba(10,14,23,0.9) 0%,
                rgba(10,14,23,0.6) 55%,
                rgba(10,14,23,0.88) 100%
            );
        }
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 860px;
            padding: 2rem;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(245, 166, 35, 0.12);
            color: var(--gold);
            border: 1px solid rgba(245, 166, 35, 0.35);
            border-radius: 50px;
            padding: 7px 22px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-size: clamp(2.6rem, 7vw, 5rem);
            font-weight: 800;
            line-height: 1.08;
            margin-bottom: 1.25rem;
            letter-spacing: -1px;
        }
        .hero h1 .accent { color: var(--gold); }
        .hero p {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(229, 231, 235, 0.82);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }
        .hero-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-gold {
            background: var(--gold);
            color: #0a0e17;
            border: none;
            font-weight: 700;
            padding: 14px 36px;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-gold:hover {
            background: var(--gold-dark);
            color: #0a0e17;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(245, 166, 35, 0.38);
        }
        .btn-outline-gold {
            background: transparent;
            color: var(--gold);
            border: 2px solid var(--gold);
            font-weight: 700;
            padding: 12px 34px;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline-gold:hover {
            background: var(--gold);
            color: #0a0e17;
            transform: translateY(-2px);
        }
        .hero-scroll {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            color: rgba(255,255,255,0.4);
            font-size: 0.72rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            letter-spacing: 1px;
            text-transform: uppercase;
            animation: bobup 2.2s ease-in-out infinite;
        }
        @keyframes bobup {
            0%, 100% { opacity: 0.4; transform: translateX(-50%) translateY(0); }
            50% { opacity: 0.8; transform: translateX(-50%) translateY(9px); }
        }

        /* ===== STATS ===== */
        .stats-section {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }
        .stat-item {
            text-align: center;
            padding: 2.5rem 1rem;
            border-right: 1px solid var(--border-color);
        }
        .stat-item:last-child { border-right: none; }
        .stat-number {
            font-size: 2.6rem;
            font-weight: 800;
            color: var(--gold);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 7px;
        }

        /* ===== SECTION HEADERS ===== */
        .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: var(--gold);
            margin-bottom: 0.4rem;
        }
        .section-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.6rem;
        }
        .section-subtitle {
            color: var(--text-muted);
            max-width: 560px;
            margin: 0 auto;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* ===== FEATURE CARDS ===== */
        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.25,0.46,0.45,0.94);
            height: 100%;
            text-decoration: none;
            color: var(--text-main);
            display: flex;
            flex-direction: column;
        }
        .feature-card:hover {
            background: var(--bg-card-hover);
            border-color: rgba(245, 166, 35, 0.45);
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(0,0,0,0.45);
            color: var(--text-main);
        }
        .feature-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.2rem;
            background: rgba(245, 166, 35, 0.1);
            color: var(--gold);
            border: 1px solid rgba(245, 166, 35, 0.18);
        }
        .feature-card h5 {
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            font-size: 0.875rem;
            color: var(--text-muted);
            flex: 1;
            line-height: 1.6;
        }
        .feature-link {
            color: var(--gold);
            font-size: 0.82rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 1rem;
        }
        .feature-link i { transition: transform 0.2s; }
        .feature-card:hover .feature-link i { transform: translateX(5px); }

        /* ===== FILM CARDS ===== */
        .film-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            text-decoration: none;
            color: var(--text-main);
            display: block;
            height: 100%;
        }
        .film-card:hover {
            transform: translateY(-5px);
            border-color: rgba(245, 166, 35, 0.45);
            box-shadow: 0 14px 36px rgba(0,0,0,0.5);
            color: var(--text-main);
        }
        .film-poster {
            height: 230px;
            object-fit: cover;
            width: 100%;
        }
        .film-poster-placeholder {
            height: 230px;
            background: linear-gradient(135deg, #1e2435 0%, #2a3448 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: rgba(245, 166, 35, 0.25);
        }
        .film-card-body { padding: 1rem 1.2rem 1.25rem; }
        .film-badge {
            display: inline-block;
            background: rgba(245, 166, 35, 0.1);
            color: var(--gold);
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
            margin-bottom: 7px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .film-card-title {
            font-weight: 700;
            font-size: 0.92rem;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .film-meta {
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            gap: 10px;
        }

        /* ===== ACTUALITÉS CARDS ===== */
        .actu-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            text-decoration: none;
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .actu-card:hover {
            transform: translateY(-5px);
            border-color: rgba(245, 166, 35, 0.45);
            box-shadow: 0 14px 36px rgba(0,0,0,0.5);
            color: var(--text-main);
        }
        .actu-img { height: 190px; object-fit: cover; width: 100%; }
        .actu-img-placeholder {
            height: 190px;
            background: linear-gradient(135deg, #1e2435 0%, #2a3448 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: rgba(245, 166, 35, 0.2);
        }
        .actu-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
        .actu-date {
            font-size: 0.72rem;
            color: var(--gold);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }
        .actu-title {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 8px;
        }
        .actu-excerpt {
            font-size: 0.845rem;
            color: var(--text-muted);
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.6;
        }
        .actu-link {
            color: var(--gold);
            font-size: 0.8rem;
            font-weight: 700;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ===== CTA BANNER ===== */
        .cta-banner {
            background: linear-gradient(135deg, rgba(245,166,35,0.1) 0%, rgba(245,166,35,0.03) 100%);
            border: 1px solid rgba(245, 166, 35, 0.2);
            border-radius: 20px;
        }

        /* ===== SECTION SEPARATOR ===== */
        .section-alt { background: var(--bg-card); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 767px) {
            .stat-item { border-right: none; border-bottom: 1px solid var(--border-color); }
            .stat-item:last-child { border-bottom: none; }
        }

        /* ===== LIGHT MODE ===== */
        @media (prefers-color-scheme: light) {
            :root {
                --bg-main: #f4f6fb;
                --bg-card: #ffffff;
                --bg-card-hover: #edf0f8;
                --border-color: rgba(0,0,0,0.08);
                --text-main: #111827;
                --text-muted: #6b7280;
            }
            body { background: var(--bg-main); color: var(--text-main); }
            .hero-overlay {
                background: linear-gradient(160deg, rgba(10,14,23,0.82) 0%, rgba(10,14,23,0.52) 55%, rgba(10,14,23,0.82) 100%);
            }
        }
    </style>
</head>
<body>
    @include('public.navbar')

    {{-- ===== HERO ===== --}}
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-camera-reels me-1"></i> Festival du cinéma africain
            </div>
            <h1>Bienvenue au <span class="accent">FESPACO</span></h1>
            <p>Le plus grand festival du cinéma africain. Découvrez des films, des talents et une culture d'exception venues de tout le continent.</p>
            <div class="hero-btns">
                <a href="/films" class="btn-gold"><i class="bi bi-play-fill"></i> Découvrir les films</a>
                <a href="/projections" class="btn-outline-gold"><i class="bi bi-calendar3"></i> Voir le programme</a>
            </div>
        </div>
        <div class="hero-scroll">
            <i class="bi bi-chevron-down fs-4"></i>
            <span>Défiler</span>
        </div>
    </section>

    {{-- ===== STATS ===== --}}
    <section class="stats-section">
        <div class="container">
            <div class="row g-0">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">1969</div>
                        <div class="stat-label">Année de fondation</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">{{ $filmsCount }}+</div>
                        <div class="stat-label">Films au catalogue</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">46</div>
                        <div class="stat-label">Pays africains</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">2 ans</div>
                        <div class="stat-label">Périodicité</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURE CARDS ===== --}}
    <section class="py-5 py-lg-6">
        <div class="container py-3">
            <div class="text-center mb-5">
                <div class="section-label">Explorer</div>
                <h2 class="section-title">Tout le festival, en un coup d'œil</h2>
                <p class="section-subtitle">Films, projections, galerie, portraits de cinéastes — tout est ici sur la plateforme officielle du FESPACO.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <a href="/films" class="feature-card">
                        <div class="feature-icon"><i class="bi bi-film"></i></div>
                        <h5>Films</h5>
                        <p>Parcourez le catalogue complet des films africains sélectionnés et primés au FESPACO.</p>
                        <div class="feature-link">Voir les films <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="/projections" class="feature-card">
                        <div class="feature-icon"><i class="bi bi-calendar3"></i></div>
                        <h5>Projections</h5>
                        <p>Consultez le programme des séances, ateliers et événements spéciaux du festival.</p>
                        <div class="feature-link">Voir le programme <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="/galerie" class="feature-card">
                        <div class="feature-icon"><i class="bi bi-images"></i></div>
                        <h5>Galerie</h5>
                        <p>Revivez les meilleurs moments du festival en images et vidéos exclusives.</p>
                        <div class="feature-link">Voir la galerie <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="/realisateurs-acteurs?profil=realisateurs" class="feature-card">
                        <div class="feature-icon"><i class="bi bi-camera2"></i></div>
                        <h5>Réalisateurs</h5>
                        <p>Découvrez les cinéastes africains qui façonnent le 7e art du continent.</p>
                        <div class="feature-link">Voir les réalisateurs <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="/realisateurs-acteurs?profil=acteurs" class="feature-card">
                        <div class="feature-icon"><i class="bi bi-person-badge"></i></div>
                        <h5>Acteurs</h5>
                        <p>Rencontrez les comédiens et talents de la scène cinématographique africaine.</p>
                        <div class="feature-link">Voir les acteurs <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="/actualites" class="feature-card">
                        <div class="feature-icon"><i class="bi bi-newspaper"></i></div>
                        <h5>Actualités</h5>
                        <p>Restez informé des dernières nouvelles et annonces officielles du festival.</p>
                        <div class="feature-link">Lire les actualités <i class="bi bi-arrow-right"></i></div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA BANNER ===== --}}
    <section class="section-alt py-5">
        <div class="container py-3">
            <div class="cta-banner p-4 p-md-5 text-center">
                <div class="section-label mb-3">Rejoignez-nous</div>
                <h2 class="section-title">Vivez le cinéma africain</h2>
                <p class="section-subtitle mb-4">Que vous soyez cinéphile, professionnel du cinéma ou simplement curieux, le FESPACO vous ouvre ses portes.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="/projections" class="btn-gold"><i class="bi bi-ticket-perforated"></i> Voir les projections</a>
                    <a href="/contact" class="btn-outline-gold"><i class="bi bi-envelope"></i> Nous contacter</a>
                </div>
            </div>
        </div>
    </section>

    @include('public.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

