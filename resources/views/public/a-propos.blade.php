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
    <link rel="stylesheet" href="/css/public/a-propos.css">
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
