<link rel="stylesheet" href="/css/public/navbar.css">
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

@include('partials.projection-resume-alert')

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
