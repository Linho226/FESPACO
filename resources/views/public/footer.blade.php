<link rel="stylesheet" href="/css/public/footer.css">

@php
    $socialLinks = array_filter(config('services.fespaco.social', []), fn ($url) => filled($url));
    $socialIcons = [
        'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-7h2.4l.4-3h-2.8V9.2c0-.9.3-1.5 1.6-1.5H16.5V5.1c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4V11H8v3h2.3v7h3.2Z" fill="currentColor"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.8 3h8.4A4.8 4.8 0 0 1 21 7.8v8.4a4.8 4.8 0 0 1-4.8 4.8H7.8A4.8 4.8 0 0 1 3 16.2V7.8A4.8 4.8 0 0 1 7.8 3Zm0 1.8A3 3 0 0 0 4.8 7.8v8.4a3 3 0 0 0 3 3h8.4a3 3 0 0 0 3-3V7.8a3 3 0 0 0-3-3H7.8Zm8.85 1.35a1.05 1.05 0 1 1 0 2.1 1.05 1.05 0 0 1 0-2.1ZM12 7.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5Zm0 1.8A2.7 2.7 0 1 0 14.7 12 2.7 2.7 0 0 0 12 9.3Z" fill="currentColor"/></svg>',
        'youtube' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.4 7.2a2.9 2.9 0 0 0-2-2C17.6 4.7 12 4.7 12 4.7s-5.6 0-7.4.5a2.9 2.9 0 0 0-2 2A30.6 30.6 0 0 0 2 12a30.6 30.6 0 0 0 .6 4.8 2.9 2.9 0 0 0 2 2c1.8.5 7.4.5 7.4.5s5.6 0 7.4-.5a2.9 2.9 0 0 0 2-2A30.6 30.6 0 0 0 22 12a30.6 30.6 0 0 0-.6-4.8ZM10.2 15.6V8.4l6.2 3.6-6.2 3.6Z" fill="currentColor"/></svg>',
        'x' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.9 3H21l-6.8 7.8L22 21h-6.1l-4.8-6.3L5.6 21H2.5l7.2-8.2L2 3h6.2l4.3 5.8L17.9 3Zm-1.1 16h1.7L7.3 4.9H5.4L16.8 19Z" fill="currentColor"/></svg>',
        'twitter' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.9 3H21l-6.8 7.8L22 21h-6.1l-4.8-6.3L5.6 21H2.5l7.2-8.2L2 3h6.2l4.3 5.8L17.9 3Zm-1.1 16h1.7L7.3 4.9H5.4L16.8 19Z" fill="currentColor"/></svg>',
    ];
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
                            @php
                                $networkKey = strtolower((string) $label);
                                $iconMarkup = $socialIcons[$networkKey] ?? null;
                            @endphp
                            <a href="{{ $url }}" class="site-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">
                                <span class="site-footer__social-icon" aria-hidden="true">{!! $iconMarkup ?? e(mb_substr((string) $label, 0, 1)) !!}</span>
                                <span class="site-footer__social-label">{{ $label }}</span>
                            </a>
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