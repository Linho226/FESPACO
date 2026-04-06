<style>
    .site-footer {
        --footer-bg: var(--footer-bg, linear-gradient(160deg, #090f1a 0%, #0b1220 58%, #0c1322 100%));
        --footer-border: var(--footer-border, rgba(148, 163, 184, 0.12));
        --footer-title: var(--footer-title, #e4ebf7);
        --footer-text: var(--footer-text, #93a3bb);
        --footer-accent: var(--footer-accent, #f5a623);
        --footer-accent-2: var(--footer-accent-2, #55a7ff);
        --footer-accent-3: var(--footer-accent-3, #006241);
        background: var(--footer-bg);
        border-top: 1px solid var(--footer-border);
        padding: 3.5rem 0 1.5rem;
        color: var(--footer-text);
        margin-top: 3rem;
        position: relative;
        overflow: hidden;
    }

    .site-footer::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            radial-gradient(circle at 12% 18%, rgba(245, 166, 35, 0.06), transparent 24%),
            radial-gradient(circle at 82% 24%, rgba(85, 167, 255, 0.06), transparent 26%),
            radial-gradient(circle at 72% 88%, rgba(0, 98, 65, 0.08), transparent 24%);
        opacity: 0.75;
    }

    .site-footer > .container {
        position: relative;
        z-index: 1;
        width: min(100%, 1480px);
        max-width: none;
        padding-left: clamp(1.4rem, 3vw, 2.6rem);
        padding-right: clamp(1.4rem, 3vw, 2.6rem);
    }

    .site-footer__section {
        opacity: 0;
        transform: translateY(18px);
        animation: footerFadeUp 0.7s ease forwards;
    }

    .site-footer__section--delay-1 {
        animation-delay: 0.08s;
    }

    .site-footer__section--delay-2 {
        animation-delay: 0.16s;
    }

    .site-footer__section--delay-3 {
        animation-delay: 0.24s;
    }

    @keyframes footerFadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .site-footer__brand {
        font-size: 1.72rem;
        font-weight: 800;
        color: #edf2fb;
        margin-bottom: 0.4rem;
        letter-spacing: -0.7px;
        line-height: 1;
        text-shadow: 0 0 18px rgba(255, 255, 255, 0.05);
    }

    .site-footer__tagline {
        font-size: 0.92rem;
        line-height: 1.65;
        max-width: 320px;
        color: #c1cde0;
        text-wrap: balance;
    }

    .site-footer__heading {
        color: var(--footer-title);
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 1.6px;
        margin-bottom: 1rem;
    }

    .site-footer__heading--accent {
        color: rgba(245, 166, 35, 0.88);
        text-shadow: 0 0 14px rgba(245, 166, 35, 0.1);
    }

    .site-footer__links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .site-footer__links li {
        margin-bottom: 9px;
    }

    .site-footer__links a,
    .site-footer__contact-link {
        color: var(--footer-text);
        text-decoration: none;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .site-footer__links a {
        font-size: 0.875rem;
        position: relative;
        display: inline-flex;
        align-items: center;
        padding-bottom: 0.08rem;
    }

    .site-footer__links a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -0.08rem;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, rgba(245, 166, 35, 0.85), rgba(85, 167, 255, 0));
        transform: scaleX(0);
        transform-origin: left center;
        transition: transform 0.24s ease;
    }

    .site-footer__links a:hover,
    .site-footer__contact-link:hover {
        color: var(--footer-accent);
        transform: translateX(1px);
    }

    .site-footer__links a:hover::after {
        transform: scaleX(1);
    }

    .site-footer__contact-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.55rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(245, 166, 35, 0.16);
        background: rgba(255, 255, 255, 0.03);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.015);
    }

    .site-footer__about {
        font-size: 0.94rem;
        line-height: 1.8;
        margin-bottom: 0;
        color: #c7d2e3;
        max-width: 420px;
    }

    .site-footer__socials {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-top: 1rem;
    }

    .site-footer__social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 0.5rem 0.9rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(255, 255, 255, 0.025);
        color: var(--footer-text);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.02rem;
        transition: transform 0.22s ease, border-color 0.22s ease, background 0.22s ease, color 0.22s ease;
    }

    .site-footer__social-link:hover {
        color: var(--footer-title);
        border-color: rgba(245, 166, 35, 0.24);
        background: rgba(245, 166, 35, 0.06);
        transform: translateY(-1px);
    }

    .site-footer__divider {
        border-color: var(--footer-border);
        margin: 2.5rem 0 1.25rem;
        width: calc(100% + 1.2rem);
        margin-left: -0.6rem;
    }

    .site-footer__bottom {
        font-size: 0.78rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }

    @media (max-width: 767px) {
        .site-footer {
            text-align: center;
        }

        .site-footer > .container {
            width: 100%;
            padding-left: 1.2rem;
            padding-right: 1.2rem;
        }

        .site-footer__contact-link {
            justify-content: center;
        }

        .site-footer__socials,
        .site-footer .mt-3 {
            justify-content: center;
        }

        .site-footer__bottom {
            flex-direction: column;
            text-align: center;
        }

        .site-footer__divider {
            width: 100%;
            margin-left: 0;
        }
    }

    @media (prefers-color-scheme: light) {
        .site-footer {
            --footer-bg: linear-gradient(160deg, #f7f8fb 0%, #f2f4f8 55%, #f8fafc 100%);
            --footer-border: rgba(15, 23, 42, 0.06);
            --footer-title: #111827;
            --footer-text: #6b7280;
        }

        .site-footer::before {
            background:
                radial-gradient(circle at 12% 18%, rgba(217, 119, 6, 0.06), transparent 24%),
                radial-gradient(circle at 82% 24%, rgba(85, 167, 255, 0.05), transparent 26%),
                radial-gradient(circle at 72% 88%, rgba(0, 98, 65, 0.06), transparent 24%);
        }

        .site-footer__brand {
            color: #1f2937;
            text-shadow: none;
        }

        .site-footer__tagline,
        .site-footer__about {
            color: #4b5563;
        }

        .site-footer__social-link {
            border-color: rgba(15, 23, 42, 0.06);
            background: rgba(255, 255, 255, 0.55);
            color: #6b7280;
        }

        .site-footer__social-link:hover {
            color: #111827;
        }
    }
</style>

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