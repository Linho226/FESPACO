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
    <link rel="stylesheet" href="/css/public/home.css">
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

