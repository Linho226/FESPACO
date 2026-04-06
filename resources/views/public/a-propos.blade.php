<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - FESPACO</title>
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
    <style>
        :root {
            --brand: #006241;
            --accent: #d97706;
            --bg-1: #0b1220;
            --bg-2: #111827;
            --text-main: #e5e7eb;
            --text-soft: #9ca3af;
            --panel: rgba(255,255,255,.04);
            --panel-border: rgba(255,255,255,.12);
        }

        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #1f2a44 0%, var(--bg-1) 35%, var(--bg-2) 100%);
            color: var(--text-main);
        }

        .about-wrap {
            max-width: 1100px;
            margin: 0 auto;
        }

        .hero {
            background: linear-gradient(135deg, rgba(0,98,65,.2), rgba(217,119,6,.16));
            border: 1px solid var(--panel-border);
            border-radius: 22px;
            padding: 2.2rem;
            backdrop-filter: blur(8px);
            box-shadow: 0 16px 34px rgba(0,0,0,.22);
            margin-bottom: 1.6rem;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(0,98,65,.22);
            border: 1px solid rgba(0,98,65,.45);
            color: #dcfce7;
            border-radius: 999px;
            padding: .34rem .8rem;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .3px;
            margin-bottom: .9rem;
        }

        .about-title {
            font-size: clamp(2rem, 5vw, 3rem);
            line-height: 1.08;
            font-weight: 800;
            margin-bottom: .8rem;
            color: #fff;
        }

        .about-subtitle {
            color: var(--text-soft);
            max-width: 760px;
            font-size: 1.05rem;
            margin-bottom: 0;
        }

        .story-panel {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 18px;
            padding: 1.4rem;
            margin-bottom: 1.2rem;
        }

        .story-panel p {
            margin-bottom: .9rem;
            color: #d1d5db;
            font-size: 1rem;
            line-height: 1.7;
        }

        .story-panel p:last-child {
            margin-bottom: 0;
        }

        .facts-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.2rem;
        }

        .fact-card {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: center;
            transition: transform .25s ease, border-color .25s ease;
        }

        .fact-card:hover {
            transform: translateY(-2px);
            border-color: rgba(0,98,65,.5);
        }

        .fact-title {
            color: #86efac;
            font-weight: 700;
            margin-bottom: .35rem;
        }

        .fact-text {
            color: #d1d5db;
            margin-bottom: 0;
        }

        .timeline {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 16px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.5rem;
        }

        .timeline-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fef3c7;
            margin-bottom: .9rem;
        }

        .timeline-items {
            display: flex;
            flex-wrap: wrap;
            gap: .7rem;
        }

        .timeline-item {
            background: rgba(255,255,255,.03);
            border: 1px solid var(--panel-border);
            border-radius: 10px;
            padding: .55rem .8rem;
            color: #d1d5db;
            font-size: .9rem;
        }

        .timeline-item strong {
            color: #fff;
        }

        .about-actions {
            display: flex;
            justify-content: center;
            gap: .7rem;
            flex-wrap: wrap;
        }

        .btn-contact {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
            font-weight: 700;
            padding: .65rem 1.2rem;
            border-radius: 10px;
        }

        .btn-contact:hover {
            background: #015535;
            border-color: #015535;
            color: #fff;
        }

        .btn-outline-lite {
            border: 1px solid var(--panel-border);
            color: #e5e7eb;
            font-weight: 700;
            padding: .65rem 1.2rem;
            border-radius: 10px;
            text-decoration: none;
        }

        .btn-outline-lite:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        @media (max-width: 900px) {
            .facts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-color-scheme: light) {
            :root {
                --bg-1: #edf2f7;
                --bg-2: #e6ebf2;
                --text-main: #111827;
                --text-soft: #4b5563;
                --panel: rgba(255,255,255,.84);
                --panel-border: rgba(15,23,42,.12);
            }

            body {
                background: linear-gradient(145deg, #eef2f7 0%, #e7ecf3 100%);
                color: var(--text-main);
            }

            .about-title {
                color: #111827;
            }

            .hero-badge {
                color: #065f46;
                background: rgba(6,95,70,.12);
                border-color: rgba(6,95,70,.25);
            }

            .story-panel p,
            .fact-text,
            .timeline-item,
            .btn-outline-lite {
                color: #374151;
            }

            .fact-title {
                color: #065f46;
            }

            .timeline-title {
                color: #92400e;
            }

            .timeline-item strong {
                color: #111827;
            }

            .btn-outline-lite:hover {
                background: rgba(15,23,42,.06);
                color: #111827;
            }
        }
    </style>
</head>
<body>
    @include('public.navbar')
    <div class="container py-4 py-md-5">
        <div class="about-wrap">
            <section class="hero">
                <span class="hero-badge">Depuis 1969</span>
                <h1 class="about-title">À propos du FESPACO</h1>
                <p class="about-subtitle">Le rendez-vous panafricain du cinéma qui met en lumière les créateurs du continent et de la diaspora, et fait rayonner les récits africains sur la scène internationale.</p>
            </section>

            <section class="story-panel">
                <p><strong>Le Festival Panafricain du Cinéma et de la Télévision de Ouagadougou (FESPACO)</strong> est l’un des plus grands festivals consacrés au cinéma africain. Créé en 1969 à Ouagadougou, il constitue un événement majeur qui valorise les productions cinématographiques africaines et celles de la diaspora.</p>
                <p>Le FESPACO se déroule tous les deux ans (biennale) et rassemble des réalisateurs, acteurs, producteurs et professionnels du cinéma venus de plusieurs pays.</p>
            </section>

            <section class="facts-grid">
                <article class="fact-card">
                    <h3 class="fact-title">Création</h3>
                    <p class="fact-text">1969, Ouagadougou</p>
                </article>
                <article class="fact-card">
                    <h3 class="fact-title">Périodicité</h3>
                    <p class="fact-text">Biennale (tous les 2 ans)</p>
                </article>
                <article class="fact-card">
                    <h3 class="fact-title">Portée</h3>
                    <p class="fact-text">Afrique & Diaspora</p>
                </article>
            </section>

            <section class="timeline">
                <h2 class="timeline-title">Repères du festival</h2>
                <div class="timeline-items">
                    <span class="timeline-item"><strong>1969</strong> Lancement à Ouagadougou</span>
                    <span class="timeline-item"><strong>Biennale</strong> Une édition tous les 2 ans</span>
                    <span class="timeline-item"><strong>Compétition</strong> Valorisation des œuvres africaines</span>
                    <span class="timeline-item"><strong>Réseau</strong> Professionnels venus de plusieurs pays</span>
                </div>
            </section>

            <div class="about-actions">
                <a href="/contact" class="btn btn-contact">Nous contacter</a>
                <a href="/projections" class="btn-outline-lite">Voir les projections</a>
            </div>
        </div>
    </div>
    @include('public.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
