<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration FESPACO')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #bcc2cb; }
    </style>
</head>
<body>
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">FESPACO Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#gestion">Sections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.films.index') }}">Films</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.acteurs.index') }}">Acteurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.realisateurs.index') }}">Réalisateurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.projections') }}">Projections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.actualites') }}">Actualités</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.galerie') }}">Galerie</a></li>
                    @guest
                        <li class="nav-item ms-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm mt-1">Connexion</a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item ms-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm mt-1">Déconnexion</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5" style="margin-top: 80px;">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
