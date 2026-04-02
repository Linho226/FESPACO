<style>
  [data-bs-theme="dark"] {
    --nav-bg: rgba(21, 27, 36, 0.9);
    --nav-bg-scrolled: rgba(15, 21, 30, 0.95);
    --nav-border: rgba(255, 255, 255, 0.08);
    --nav-brand: #fff;
    --nav-link: rgba(255, 255, 255, 0.78);
    --nav-link-hover-bg: rgba(255, 255, 255, 0.08);
    --nav-link-hover-color: #fff;
    --nav-collapse-bg: rgba(16, 22, 32, 0.96);
    --nav-collapse-border: rgba(255, 255, 255, 0.09);
    --login-border: rgba(255, 255, 255, 0.35);
    --login-text: #fff;
  }

  [data-bs-theme="light"] {
    --nav-bg: rgba(255, 255, 255, 0.9);
    --nav-bg-scrolled: rgba(255, 255, 255, 0.96);
    --nav-border: rgba(15, 23, 42, 0.08);
    --nav-brand: #111827;
    --nav-link: rgba(17, 24, 39, 0.82);
    --nav-link-hover-bg: rgba(15, 23, 42, 0.08);
    --nav-link-hover-color: #111827;
    --nav-collapse-bg: rgba(255, 255, 255, 0.98);
    --nav-collapse-border: rgba(15, 23, 42, 0.11);
    --login-border: rgba(15, 23, 42, 0.28);
    --login-text: #111827;
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

  .fespaco-navbar .navbar-brand {
    font-weight: 800;
    letter-spacing: 0.3px;
    color: var(--nav-brand);
  }

  .fespaco-navbar .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.25);
  }

  .fespaco-navbar .navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(245, 166, 35, 0.35);
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
    .fespaco-navbar .navbar-collapse {
      margin-top: 0.9rem;
      padding: 0.85rem;
      border-radius: 0.8rem;
      background: var(--nav-collapse-bg);
      border: 1px solid var(--nav-collapse-border);
    }

    .fespaco-auth-actions {
      margin-left: 0;
      margin-top: 0.6rem;
      flex-wrap: wrap;
    }
  }
</style>

<nav id="fespacoNavbarMain" class="navbar navbar-expand-lg navbar-dark fespaco-navbar sticky-top">
  <div class="container-xl">
    <a class="navbar-brand" href="{{ route('public.home') }}">FESPACO</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#fespacoNavbar" aria-controls="fespacoNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="fespacoNavbar">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link{{ request()->is('/') ? ' active' : '' }}" href="{{ route('public.home') }}">Accueil</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('realisateurs-acteurs') ? ' active' : '' }}" href="{{ route('public.realisateurs_acteurs') }}">Réalisateurs & Acteurs</a></li>
                <li class="nav-item"><a class="nav-link{{ request()->is('films') ? ' active' : '' }}" href="{{ route('public.films') }}">Films</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('galerie') ? ' active' : '' }}" href="{{ route('galerie.index') }}">Galerie</a></li>
                <li class="nav-item"><a class="nav-link{{ request()->is('projections') ? ' active' : '' }}" href="{{ route('public.projections') }}">Projections</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('actualites') ? ' active' : '' }}" href="{{ route('public.actualites') }}">Actualités</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('a-propos') ? ' active' : '' }}" href="{{ route('public.a_propos') }}">À propos</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('contact') ? ' active' : '' }}" href="{{ route('public.contact') }}">Contact</a></li>
      </ul>

      <div class="fespaco-auth-actions">
        @guest
          <a class="btn btn-sm btn-outline-light fespaco-btn-login{{ request()->routeIs('login') ? ' active' : '' }}" href="{{ route('login') }}">Connexion</a>
          <a class="btn btn-sm fespaco-btn-register{{ request()->routeIs('register') ? ' active' : '' }}" href="{{ route('register') }}">Inscription</a>
        @endguest

        @auth
          @if(auth()->user()->isAdmin())
            <span class="fespaco-admin-badge">Admin</span>
          @endif
          <a class="btn btn-sm btn-outline-light fespaco-btn-login{{ request()->routeIs('profile.edit', 'admin.profile.edit') ? ' active' : '' }}" href="{{ auth()->user()->isAdmin() ? route('admin.profile.edit') : route('profile.edit') }}">Profil</a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm fespaco-btn-register">Déconnexion</button>
          </form>
        @endauth
      </div>
    </div>
  </div>
</nav>

<script>
  (() => {
    const nav = document.getElementById('fespacoNavbarMain');
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
  })();
</script>
