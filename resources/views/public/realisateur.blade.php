<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $realisateur->prenom }} {{ $realisateur->nom }} - FESPACO</title>
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
    <style>
        :root {
            color-scheme: light dark;
        }

        [data-bs-theme="light"] {
            --profile-page-bg: #f4f6f9;
            --profile-card-bg: #ffffff;
            --profile-card-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        [data-bs-theme="dark"] {
            --profile-page-bg: #0f1722;
            --profile-card-bg: #111b2a;
            --profile-card-shadow: 0 10px 24px rgba(0, 0, 0, .35);
        }

        body { background: var(--profile-page-bg); }
        .profile-card {
            border: none;
            border-radius: 14px;
            background: var(--profile-card-bg);
            box-shadow: var(--profile-card-shadow);
        }
        .avatar { width: 140px; height: 140px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>
@include('public.navbar')

<div class="container py-5">
    <a href="{{ route('public.realisateurs_acteurs') }}" class="btn btn-outline-secondary btn-sm mb-3">← Retour</a>

    <div class="card profile-card">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-md-3 text-center">
                    @if($realisateur->photo)
                        <img src="{{ asset('storage/'.$realisateur->photo) }}" alt="Photo de {{ $realisateur->prenom }}" class="avatar">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($realisateur->prenom.' '.$realisateur->nom) }}&background=cccccc&color=222222&size=140" class="avatar" alt="Avatar">
                    @endif
                </div>
                <div class="col-md-9">
                    <h2 class="mb-1">{{ $realisateur->prenom }} {{ $realisateur->nom }}</h2>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-info text-dark">{{ $realisateur->type ?: 'Réalisateur' }}</span>
                        <span class="badge bg-secondary">{{ $realisateur->nationalite ?: 'Nationalité non renseignée' }}</span>
                    </div>
                    <h6 class="text-muted">Biographie</h6>
                    <p class="mb-0">{{ $realisateur->biographie ?: 'Aucune biographie disponible pour le moment.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('public.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
