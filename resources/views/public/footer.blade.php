<link rel="stylesheet" href="/css/public/footer.css">

@php
    $socialLinks = array_filter(config('services.fespaco.social', []), fn ($url) => filled($url));
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 site-footer__section">
                <div class="site-footer__heading site-footer__heading--accent">Festival panafricain</div>
                <div class="site-footer__brand">FESPACO</div>
                <div class="site-footer__tagline">Festival Panafricain du Cinéma et de la Télévision de Ouagadougou</div>
                <div class="mt-3 d-flex gap-3">
                    <a href="/contact" class="site-footer__contact-link">Nous écrire</a>
                </div>
                @if(!empty($socialLinks))
                    <div class="site-footer__socials">
                        @foreach($socialLinks as $label => $url)
                            <a href="{{ $url }}" class="site-footer__social-link" target="_blank" rel="noopener noreferrer">{{ $label }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-6 col-md-2 site-footer__section site-footer__section--delay-1">
                <div class="site-footer__heading">Navigation</div>
                <ul class="site-footer__links">
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/films">Films</a></li>
                    <li><a href="/projections">Projections</a></li>
                    <li><a href="/galerie">Galerie</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-2 site-footer__section site-footer__section--delay-2">
                <div class="site-footer__heading">Le festival</div>
                <ul class="site-footer__links">
                    <li><a href="/a-propos">À propos</a></li>
                    <li><a href="/actualites">Actualités</a></li>
                    <li><a href="/realisateurs-acteurs">Talents</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4 site-footer__section site-footer__section--delay-3">
                <div class="site-footer__heading">À propos du festival</div>
                <p class="site-footer__about">Fondé en 1969 à Ouagadougou, le FESPACO est le plus grand festival de cinéma d'Afrique, célébrant et promouvant le cinéma africain dans le monde entier.</p>
            </div>
        </div>
        <hr class="site-footer__divider">
        <div class="site-footer__bottom">
            <span>&copy; {{ date('Y') }} FESPACO. Tous droits réservés.</span>
            <span>Ouagadougou, Burkina Faso</span>
        </div>
    </div>
</footer>