<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/">FESPACO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link{{ request()->is('/') ? ' active' : '' }}" href="/">Accueil</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('films') ? ' active' : '' }}" href="/films">Films</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('realisateurs-acteurs') ? ' active' : '' }}" href="/realisateurs-acteurs">Réalisateurs & Acteurs</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('projections') ? ' active' : '' }}" href="/projections">Projections</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('actualites') ? ' active' : '' }}" href="/actualites">Actualités</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('galerie') ? ' active' : '' }}" href="/galerie">Galerie</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('a-propos') ? ' active' : '' }}" href="/a-propos">À propos</a></li>
        <li class="nav-item"><a class="nav-link{{ request()->is('contact') ? ' active' : '' }}" href="/contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>
