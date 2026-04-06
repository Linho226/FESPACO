<style>
  :root {
    --nav-bg: rgba(21, 27, 36, 0.9);
    --nav-bg-scrolled: rgba(15, 21, 30, 0.95);
    --nav-border: rgba(255, 255, 255, 0.08);
    --nav-brand: #fff;
    --nav-link: rgba(255, 255, 255, 0.92);
    --nav-link-hover-bg: rgba(255, 255, 255, 0.08);
    --nav-link-hover-color: #fff;
    --nav-collapse-bg: rgba(16, 22, 32, 0.96);
    --nav-collapse-border: rgba(255, 255, 255, 0.09);
    --login-border: rgba(255, 255, 255, 0.35);
    --login-text: #fff;
    --nav-drawer-bg: #101827;
    --nav-drawer-border: rgba(255, 255, 255, 0.08);
    --nav-drawer-shadow: -28px 0 44px rgba(0, 0, 0, 0.42);
  }

  [data-bs-theme="dark"] {
    --nav-bg: rgba(21, 27, 36, 0.9);
    --nav-bg-scrolled: rgba(15, 21, 30, 0.95);
    --nav-border: rgba(255, 255, 255, 0.08);
    --nav-brand: #fff;
    --nav-link: rgba(255, 255, 255, 0.92);
    --nav-link-hover-bg: rgba(255, 255, 255, 0.08);
    --nav-link-hover-color: #fff;
    --nav-collapse-bg: rgba(16, 22, 32, 0.96);
    --nav-collapse-border: rgba(255, 255, 255, 0.09);
    --login-border: rgba(255, 255, 255, 0.35);
    --login-text: #fff;
    --nav-drawer-bg: #101827;
    --nav-drawer-border: rgba(255, 255, 255, 0.08);
    --nav-drawer-shadow: -28px 0 44px rgba(0, 0, 0, 0.42);
  }

  [data-bs-theme="light"] {
    --nav-bg: rgba(255, 255, 255, 0.9);
    --nav-bg-scrolled: rgba(255, 255, 255, 0.96);
    --nav-border: rgba(15, 23, 42, 0.08);
    --nav-brand: #111827;
    --nav-link: rgba(17, 24, 39, 0.9);
    --nav-link-hover-bg: rgba(15, 23, 42, 0.08);
    --nav-link-hover-color: #111827;
    --nav-collapse-bg: rgba(255, 255, 255, 0.98);
    --nav-collapse-border: rgba(15, 23, 42, 0.11);
    --login-border: rgba(15, 23, 42, 0.28);
    --login-text: #111827;
    --nav-drawer-bg: #f9fafc;
    --nav-drawer-border: rgba(15, 23, 42, 0.08);
    --nav-drawer-shadow: -28px 0 44px rgba(15, 23, 42, 0.14);
  }

  .fespaco-navbar {
    background: var(--nav-bg);
    border-bottom: 1px solid var(--nav-border);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    transition: transform 0.28s ease, box-shadow 0.28s ease, background-color 0.28s ease;
    will-change: transform;
  }

  .fespaco-navbar.nav-hidden {
    transform: translateY(-110%);
  }

  .fespaco-navbar.nav-scrolled {
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.22);
    background: var(--nav-bg-scrolled);
  }

  .fespaco-navbar .navbar-brand,
  .fespaco-mobile-drawer .navbar-brand {
    display: inline-flex;
    align-items: center;
    gap: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.3px;
    color: var(--nav-brand);
    text-decoration: none;
    min-width: 0;
  }

  .fespaco-navbar .navbar-brand:hover,
  .fespaco-mobile-drawer .navbar-brand:hover {
    color: var(--nav-brand);
  }

  .fespaco-brand-mark {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.1rem;
    height: 2.1rem;
    flex: 0 0 auto;
    border-radius: 0.72rem;
    overflow: hidden;
    background: linear-gradient(180deg, #ef2b2d 0 50%, #1f8b4c 50% 100%);
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.14);
  }

  .fespaco-brand-logo {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .fespaco-brand-mark.has-logo {
    background: transparent;
    box-shadow: none;
  }

  .fespaco-brand-mark.has-logo::after {
    content: none;
  }

  .fespaco-brand-mark::after {
    content: "★";
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    color: #f5c542;
    font-size: 0.88rem;
    text-shadow: 0 0 10px rgba(245, 197, 66, 0.35);
  }

  .fespaco-brand-copy {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1;
    min-width: 0;
  }

  .fespaco-brand-title {
    font-size: 0.98rem;
    font-weight: 800;
    letter-spacing: 0.08em;
    white-space: nowrap;
  }

  .fespaco-navbar-brand-compact {
    gap: 0.58rem;
    max-width: calc(100vw - 7rem);
  }

  .fespaco-navbar-brand-compact .fespaco-brand-mark {
    width: 1.9rem;
    height: 1.9rem;
    border-radius: 0.66rem;
  }

  .fespaco-navbar-brand-compact .fespaco-brand-title {
    font-size: 0.9rem;
    letter-spacing: 0.07em;
  }

  .fespaco-navbar-brand-compact .fespaco-brand-copy {
    overflow: hidden;
  }

  .fespaco-drawer-brand {
    gap: 0.72rem;
  }

  .fespaco-navbar .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.25);
    border-radius: 0.9rem;
    padding: 0.55rem 0.72rem;
  }

  .fespaco-navbar .navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(245, 166, 35, 0.35);
  }

  .fespaco-navbar-desktop {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex: 1 1 auto;
  }

  .fespaco-mobile-drawer {
    --bs-offcanvas-bg: var(--nav-drawer-bg);
    --bs-offcanvas-color: var(--nav-brand);
    --bs-offcanvas-width: min(84vw, 340px);
    --bs-offcanvas-border-width: 1px;
    background: var(--nav-drawer-bg);
    background-color: var(--nav-drawer-bg);
    border-left: 1px solid var(--nav-drawer-border);
    box-shadow: var(--nav-drawer-shadow);
    z-index: 1080;
  }

  .offcanvas-backdrop {
    --bs-backdrop-zindex: 1070;
    background-color: rgba(6, 10, 18, 0.58);
  }

  .offcanvas-backdrop.show {
    opacity: 1;
  }

  .fespaco-mobile-drawer .offcanvas-header {
    padding: 1rem 1rem 0.95rem;
    border-bottom: 1px solid var(--nav-drawer-border);
  }

  .fespaco-mobile-drawer .offcanvas-body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
  }

  .fespaco-mobile-drawer .btn-close {
    opacity: 1;
  }

  .fespaco-mobile-drawer .btn-close,
  [data-bs-theme="dark"] .fespaco-mobile-drawer .btn-close {
    filter: invert(1) grayscale(1) brightness(1.15);
  }

  [data-bs-theme="light"] .fespaco-mobile-drawer .btn-close {
    filter: none;
  }

  .fespaco-mobile-links .nav-link {
    display: block;
    padding: 0.88rem 0.9rem;
    border-radius: 0.9rem;
    font-size: 1.03rem;
    color: var(--nav-link);
  }

  .fespaco-mobile-links .nav-link:hover,
  .fespaco-mobile-links .nav-link:focus-visible {
    color: var(--nav-link-hover-color);
    background: var(--nav-link-hover-bg);
  }

  .fespaco-mobile-links .nav-link.active {
    color: #f5a623;
    background: rgba(245, 166, 35, 0.12);
  }

  .fespaco-mobile-links .nav-item + .nav-item {
    margin-top: 0.18rem;
  }

  .fespaco-mobile-auth {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--nav-drawer-border);
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    gap: 0.65rem;
    flex-wrap: wrap;
  }

  .fespaco-mobile-auth .btn {
    width: 100%;
    justify-content: center;
  }

  .fespaco-mobile-auth .fespaco-user-badge {
    max-width: 100%;
  }

  .fespaco-mobile-auth .fespaco-user-card {
    width: 100%;
    max-width: none;
    padding: 0.85rem 0.9rem;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.035);
  }

  .fespaco-mobile-auth .fespaco-user-avatar {
    width: 2.25rem;
    height: 2.25rem;
    font-size: 0.94rem;
  }

  .fespaco-mobile-auth .fespaco-user-name {
    font-size: 0.98rem;
  }

  .fespaco-navbar .nav-link {
    color: var(--nav-link);
    font-weight: 500;
    padding-left: 0.7rem;
    padding-right: 0.7rem;
    border-radius: 0.45rem;
    transition: color 0.2s ease, background-color 0.2s ease;
  }

  .fespaco-navbar .nav-link:hover {
    color: var(--nav-link-hover-color);
    background: var(--nav-link-hover-bg);
  }

  .fespaco-navbar .nav-link.active {
    color: #f5a623;
    background: rgba(245, 166, 35, 0.12);
  }

  .fespaco-auth-actions {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin-left: 0.55rem;
  }

  .fespaco-user-card {
    display: inline-flex;
    align-items: center;
    gap: 0.72rem;
    min-width: 0;
    max-width: 230px;
    padding: 0.55rem 0.8rem;
    border-radius: 1rem;
    border: 1px solid var(--nav-collapse-border);
    background: rgba(255, 255, 255, 0.04);
    color: var(--nav-brand);
    text-decoration: none;
    transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
  }

  .fespaco-user-card:hover {
    color: var(--nav-brand);
    background: rgba(255, 255, 255, 0.07);
    border-color: rgba(245, 166, 35, 0.24);
    transform: translateY(-1px);
  }

  .fespaco-user-avatar {
    width: 2rem;
    height: 2rem;
    flex: 0 0 auto;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5a623, #cf7f0f);
    color: #1f1301;
    font-size: 0.88rem;
    font-weight: 800;
    letter-spacing: 0.02em;
    box-shadow: 0 8px 16px rgba(245, 166, 35, 0.24);
  }

  .fespaco-user-name {
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 0.84rem;
    font-weight: 700;
    line-height: 1.1;
  }

  .fespaco-logout-link {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: transparent;
    border: 0;
    color: #ff7070;
    font-size: 0.92rem;
    font-weight: 700;
    padding: 0.4rem 0.2rem;
  }

  .fespaco-logout-link:hover {
    color: #ff9090;
  }

  .fespaco-logout-link::before {
    content: "↪";
    font-size: 1rem;
    line-height: 1;
  }

  .fespaco-admin-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 0.25rem 0.62rem;
    font-size: 0.74rem;
    font-weight: 700;
    letter-spacing: 0.2px;
    color: #1f1301;
    background: #f5a623;
    border: 1px solid rgba(245, 166, 35, 0.75);
  }

  .fespaco-user-badge {
    display: inline-flex;
    align-items: center;
    max-width: 180px;
    padding: 0.32rem 0.78rem;
    border-radius: 999px;
    border: 1px solid var(--nav-collapse-border);
    background: rgba(255, 255, 255, 0.04);
    color: var(--nav-brand);
    font-size: 0.82rem;
    font-weight: 700;
    line-height: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .fespaco-auth-actions .btn {
    border-radius: 999px;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 0.34rem 0.9rem;
  }

  .fespaco-btn-login {
    border-color: var(--login-border);
    color: var(--login-text);
  }

  .fespaco-btn-login:hover {
    border-color: var(--nav-link-hover-color);
    background: var(--nav-link-hover-bg);
    color: var(--login-text);
  }

  .fespaco-btn-register {
    background: #f5a623;
    border-color: #f5a623;
    color: #1f1301;
  }

  .fespaco-btn-register:hover {
    background: #e19319;
    border-color: #e19319;
    color: #1f1301;
  }

  @media (max-width: 991.98px) {
    .fespaco-navbar-desktop {
      display: none;
    }

    .fespaco-navbar .navbar-collapse {
      margin-top: 0.9rem;
      padding: 0.85rem;
      border-radius: 0.8rem;
      background: var(--nav-collapse-bg);
      border: 1px solid var(--nav-collapse-border);
    }

    .fespaco-mobile-drawer {
      width: min(84vw, 340px);
    }

    .fespaco-navbar-brand-compact {
      max-width: calc(100vw - 6.75rem);
    }
  }

  @media (max-width: 575.98px) {
    .fespaco-mobile-drawer {
      width: 100vw;
      max-width: 100vw;
    }

    .fespaco-mobile-drawer .offcanvas-header,
    .fespaco-mobile-drawer .offcanvas-body {
      padding-left: 1.1rem;
      padding-right: 1.1rem;
    }

    .fespaco-navbar-brand-compact {
      gap: 0.5rem;
      max-width: calc(100vw - 5.75rem);
    }

    .fespaco-navbar-brand-compact .fespaco-brand-mark {
      width: 1.72rem;
      height: 1.72rem;
      border-radius: 0.58rem;
    }

    .fespaco-navbar-brand-compact .fespaco-brand-title {
      font-size: 0.9rem;
      line-height: 1;
    }

    .fespaco-navbar-brand-compact .fespaco-brand-copy {
      justify-content: center;
    }
  }

  @media (min-width: 992px) {
    .fespaco-mobile-drawer {
      display: none;
    }
  }
</style>

@php
  $brandLogoPath = null;
  foreach (['images/logo.png', 'images/logo.svg', 'images/fespaco-logo.png', 'images/fespaco-logo.svg', 'images/images.png'] as $candidateLogoPath) {
    if (file_exists(public_path($candidateLogoPath))) {
      $brandLogoPath = $candidateLogoPath;
      break;
    }
  }
  $brandLogoExists = filled($brandLogoPath);
  $userInitials = null;
  if (auth()->check()) {
    $nameParts = preg_split('/\s+/u', trim(auth()->user()->name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $userInitials = collect($nameParts)
      ->take(2)
      ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
      ->implode('');
  }
  $navItems = [
    ['route' => 'public.home', 'label' => 'Accueil', 'is' => '/'],
    ['route' => 'public.realisateurs_acteurs', 'label' => 'Réalisateurs & Acteurs', 'is' => 'realisateurs-acteurs'],
    ['route' => 'public.films', 'label' => 'Films', 'is' => 'films'],
    ['route' => 'galerie.index', 'label' => 'Galerie', 'is' => 'galerie'],
    ['route' => 'public.projections', 'label' => 'Projections', 'is' => 'projections'],
    ['route' => 'public.actualites', 'label' => 'Actualités', 'is' => 'actualites'],
    ['route' => 'public.a_propos', 'label' => 'À propos', 'is' => 'a-propos'],
    ['route' => 'public.contact', 'label' => 'Contact', 'is' => 'contact'],
  ];
@endphp

<nav id="fespacoNavbarMain" class="navbar navbar-expand-lg navbar-dark fespaco-navbar sticky-top">
  <div class="container-xl">
    <a class="navbar-brand fespaco-navbar-brand-compact" href="{{ route('public.home') }}" aria-label="FESPACO">
      <span class="fespaco-brand-mark{{ $brandLogoExists ? ' has-logo' : '' }}" aria-hidden="true">
        @if($brandLogoExists)
          <img src="{{ asset($brandLogoPath) }}" alt="" class="fespaco-brand-logo">
        @endif
      </span>
      <span class="fespaco-brand-copy">
        <span class="fespaco-brand-title">FESPACO</span>
      </span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#fespacoMobileDrawer" aria-controls="fespacoMobileDrawer" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="fespaco-navbar-desktop">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        @foreach($navItems as $item)
          <li class="nav-item"><a class="nav-link{{ request()->is($item['is']) ? ' active' : '' }}" href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
        @endforeach
      </ul>

      <div class="fespaco-auth-actions">
        @guest
          <a class="btn btn-sm btn-outline-light fespaco-btn-login{{ request()->routeIs('login') ? ' active' : '' }}" href="{{ route('login') }}">Connexion</a>
          <a class="btn btn-sm fespaco-btn-register{{ request()->routeIs('register') ? ' active' : '' }}" href="{{ route('register') }}">Inscription</a>
        @endguest

        @auth
          <a class="fespaco-user-card" href="{{ auth()->user()->isAdmin() ? route('admin.profile.edit') : route('profile.edit') }}">
            <span class="fespaco-user-avatar">{{ $userInitials }}</span>
            <span class="fespaco-user-name">{{ auth()->user()->name }}</span>
          </a>
          @if(auth()->user()->isAdmin())
            <span class="fespaco-admin-badge">Admin</span>
          @endif
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="fespaco-logout-link">Se déconnecter</button>
          </form>
        @endauth
      </div>
    </div>
  </div>
</nav>

<div class="offcanvas offcanvas-end fespaco-mobile-drawer" tabindex="-1" id="fespacoMobileDrawer" aria-labelledby="fespacoMobileDrawerLabel">
  <div class="offcanvas-header">
    <a class="navbar-brand fespaco-drawer-brand" href="{{ route('public.home') }}" aria-label="FESPACO">
      <span class="fespaco-brand-mark{{ $brandLogoExists ? ' has-logo' : '' }}" aria-hidden="true">
        @if($brandLogoExists)
          <img src="{{ asset($brandLogoPath) }}" alt="" class="fespaco-brand-logo">
        @endif
      </span>
      <span class="fespaco-brand-copy">
        <span class="fespaco-brand-title" id="fespacoMobileDrawerLabel">FESPACO</span>
      </span>
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    <ul class="navbar-nav fespaco-mobile-links">
      @foreach($navItems as $item)
        <li class="nav-item"><a class="nav-link{{ request()->is($item['is']) ? ' active' : '' }}" href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>
      @endforeach
    </ul>

    <div class="fespaco-mobile-auth">
      @guest
        <a class="btn btn-sm btn-outline-light fespaco-btn-login{{ request()->routeIs('login') ? ' active' : '' }}" href="{{ route('login') }}">Connexion</a>
        <a class="btn btn-sm fespaco-btn-register{{ request()->routeIs('register') ? ' active' : '' }}" href="{{ route('register') }}">Inscription</a>
      @endguest

      @auth
        <a class="fespaco-user-card" href="{{ auth()->user()->isAdmin() ? route('admin.profile.edit') : route('profile.edit') }}">
          <span class="fespaco-user-avatar">{{ $userInitials }}</span>
          <span class="fespaco-user-name">{{ auth()->user()->name }}</span>
        </a>
        @if(auth()->user()->isAdmin())
          <span class="fespaco-admin-badge">Admin</span>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
          @csrf
          <button type="submit" class="fespaco-logout-link">Se déconnecter</button>
        </form>
      @endauth
    </div>
  </div>
</div>

<script>
  (() => {
    const nav = document.getElementById('fespacoNavbarMain');
    const mobileDrawer = document.getElementById('fespacoMobileDrawer');
    if (!nav) return;

    const applyNavbarScheme = () => {
      const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
      nav.classList.toggle('navbar-dark', isDark);
      nav.classList.toggle('navbar-light', !isDark);
    };

    applyNavbarScheme();

    const observer = new MutationObserver(applyNavbarScheme);
    observer.observe(document.documentElement, {
      attributes: true,
      attributeFilter: ['data-bs-theme'],
    });

    let lastY = window.scrollY;

    const onScroll = () => {
      const currentY = window.scrollY;

      nav.classList.toggle('nav-scrolled', currentY > 8);

      if (currentY <= 12) {
        nav.classList.remove('nav-hidden');
        lastY = currentY;
        return;
      }

      const delta = currentY - lastY;

      if (delta > 6) {
        nav.classList.add('nav-hidden');
      } else if (delta < -6) {
        nav.classList.remove('nav-hidden');
      }

      lastY = currentY;
    };

    window.addEventListener('scroll', onScroll, { passive: true });

    if (mobileDrawer && window.bootstrap?.Offcanvas) {
      mobileDrawer.querySelectorAll('.nav-link').forEach((link) => {
        link.addEventListener('click', () => {
          const instance = window.bootstrap.Offcanvas.getOrCreateInstance(mobileDrawer);
          instance.hide();
        });
      });
    }
  })();
</script>
