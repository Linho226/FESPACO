<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réalisateurs & Acteurs - FESPACO</title>
    <script>
        (() => {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

            const applyTheme = (eventOrQuery) => {
                const isDark = typeof eventOrQuery.matches === 'boolean' ? eventOrQuery.matches : mediaQuery.matches;
                document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            };

            applyTheme(mediaQuery);

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
            --fespaco-page-bg: #f4f6f9;
            --fespaco-surface-bg: #ffffff;
            --fespaco-surface-border: rgba(15, 23, 42, 0.08);
            --fespaco-card-shadow: 0 4px 16px rgba(0, 0, 0, .08);
            --fespaco-muted: #64748b;
            --fespaco-heading: #0f172a;
            --fespaco-sticky-bg: rgba(255, 255, 255, 0.86);
        }

        [data-bs-theme="dark"] {
            --fespaco-page-bg: #0f1722;
            --fespaco-surface-bg: #111b2a;
            --fespaco-surface-border: rgba(148, 163, 184, 0.22);
            --fespaco-card-shadow: 0 10px 24px rgba(0, 0, 0, .35);
            --fespaco-muted: #9aa5b1;
            --fespaco-heading: #e5e7eb;
            --fespaco-sticky-bg: rgba(17, 27, 42, 0.78);
        }

        body {
            background: var(--fespaco-page-bg);
        }

        .page-title {
            color: var(--fespaco-heading);
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .page-subtitle {
            color: var(--fespaco-muted);
            max-width: 780px;
        }

        .stats-pill {
            border-radius: 999px;
            padding: 0.42rem 0.8rem;
            border: 1px solid var(--fespaco-surface-border);
            background: var(--fespaco-surface-bg);
            color: var(--fespaco-heading);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .profiles-tabs .nav-link {
            border-radius: 999px;
            border: 1px solid var(--fespaco-surface-border);
            color: var(--fespaco-heading);
            background: var(--fespaco-surface-bg);
            font-weight: 600;
            padding: 0.38rem 0.9rem;
        }

        .profiles-tabs .nav-link.active {
            background: rgba(245, 166, 35, 0.18);
            border-color: rgba(245, 166, 35, 0.4);
            color: #f5a623;
        }

        .filters-sticky {
            position: sticky;
            top: 82px;
            z-index: 8;
            border-radius: 14px;
            border: 1px solid var(--fespaco-surface-border);
            background: var(--fespaco-sticky-bg);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: var(--fespaco-card-shadow);
            padding: 0.9rem;
        }

        .filters-sticky .form-control,
        .filters-sticky .form-select {
            border-radius: 10px;
        }

        .active-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.28rem 0.65rem;
            background: rgba(59, 130, 246, 0.14);
            border: 1px solid rgba(59, 130, 246, 0.28);
            color: var(--fespaco-heading);
            font-size: 0.78rem;
            font-weight: 600;
        }

        .section-card {
            border-radius: 12px;
            border: 1px solid var(--fespaco-surface-border);
            background: var(--fespaco-surface-bg);
        }

        .section-title {
            color: var(--fespaco-heading);
            font-weight: 700;
            margin-bottom: 0;
        }

        .section-count {
            color: var(--fespaco-muted);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .talent-card {
            border: none;
            border-radius: 12px;
            background: var(--fespaco-surface-bg);
            box-shadow: var(--fespaco-card-shadow);
            border: 1px solid var(--fespaco-surface-border);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .talent-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.18);
        }

        .bio-excerpt {
            color: var(--fespaco-muted);
            min-height: 3.9em;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .avatar { width: 88px; height: 88px; object-fit: cover; border-radius: 50%; }

        .empty-state {
            border-radius: 12px;
            border: 1px dashed var(--fespaco-surface-border);
            padding: 1rem;
            color: var(--fespaco-muted);
            background: rgba(148, 163, 184, 0.08);
        }

        @media (max-width: 991.98px) {
            .filters-sticky {
                top: 72px;
            }
        }
    </style>
</head>
<body>
@include('public.navbar')

<div class="container py-5">
    @php
        $cleanQuery = [
            'q' => $search,
            'nationalite' => $nationalite,
            'type' => $type,
        ];
        $cleanQuery = array_filter($cleanQuery, fn ($value) => filled($value));
        $allTalentsTotal = $realisateurs->total() + $acteurs->total();
    @endphp

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <h1 class="mb-0 page-title">Réalisateurs & Acteurs</h1>
        <div class="d-flex flex-wrap gap-2">
            <span class="stats-pill">{{ $allTalentsTotal }} talent(s)</span>
            <span class="stats-pill">{{ $realisateurs->total() }} réalisateurs</span>
            <span class="stats-pill">{{ $acteurs->total() }} acteurs</span>
        </div>
    </div>
    <p class="page-subtitle mb-3">Explorez les talents du festival, filtrez par profil, type et nationalité, puis ouvrez chaque fiche en un clic.</p>

    <ul class="nav profiles-tabs gap-2 mb-3">
        <li class="nav-item">
            <a class="nav-link{{ $profil === 'tous' ? ' active' : '' }}" href="{{ route('public.realisateurs_acteurs', array_merge($cleanQuery, ['profil' => 'tous'])) }}">Tous</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $profil === 'realisateurs' ? ' active' : '' }}" href="{{ route('public.realisateurs_acteurs', array_merge($cleanQuery, ['profil' => 'realisateurs'])) }}">Réalisateurs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $profil === 'acteurs' ? ' active' : '' }}" href="{{ route('public.realisateurs_acteurs', array_merge($cleanQuery, ['profil' => 'acteurs'])) }}">Acteurs</a>
        </li>
    </ul>

    <div class="filters-sticky mb-4">
        <form method="GET" action="{{ route('public.realisateurs_acteurs') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Rechercher un acteur ou réalisateur...">
            </div>
            <div class="col-md-2">
                <select name="profil" class="form-select">
                    <option value="tous" {{ $profil === 'tous' ? 'selected' : '' }}>Tous les profils</option>
                    <option value="realisateurs" {{ $profil === 'realisateurs' ? 'selected' : '' }}>Réalisateurs</option>
                    <option value="acteurs" {{ $profil === 'acteurs' ? 'selected' : '' }}>Acteurs</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="nationalite" class="form-select">
                    <option value="">Toutes nationalités</option>
                    @foreach($nationalites as $n)
                        <option value="{{ $n }}" {{ $nationalite === $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">Tous types</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ $type === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-grid">
                <button class="btn btn-primary" type="submit">Filtrer</button>
            </div>
            <div class="col-md-1 d-grid">
                <a href="{{ route('public.realisateurs_acteurs') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        @if(filled($search) || filled($nationalite) || filled($type) || $profil !== 'tous')
            <div class="d-flex flex-wrap gap-2 mt-3">
                @if(filled($search))
                    <span class="active-filter-chip">Recherche: {{ $search }}</span>
                @endif
                @if($profil !== 'tous')
                    <span class="active-filter-chip">Profil: {{ $profil }}</span>
                @endif
                @if(filled($nationalite))
                    <span class="active-filter-chip">Nationalité: {{ $nationalite }}</span>
                @endif
                @if(filled($type))
                    <span class="active-filter-chip">Type: {{ $type }}</span>
                @endif
            </div>
        @endif
    </div>

    @if($showRealisateurs)
    <div class="card border-0 shadow-sm mb-4 section-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="section-title">🎬 Réalisateurs</h4>
                <span class="section-count">{{ $realisateurs->total() }} résultat(s)</span>
            </div>
            @if($realisateurs->isEmpty())
                <div class="empty-state">Aucun réalisateur trouvé avec ces filtres.</div>
            @else
                <div class="row g-3">
                    @foreach($realisateurs as $realisateur)
                        <div class="col-md-6 col-lg-3">
                            <div class="card talent-card h-100">
                                <div class="card-body text-center d-flex flex-column">
                                    @if($realisateur->photo)
                                        <img src="{{ asset('storage/'.$realisateur->photo) }}" alt="Photo de {{ $realisateur->prenom }}" class="avatar mb-2">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($realisateur->prenom.' '.$realisateur->nom) }}&background=cccccc&color=222222&size=88" class="avatar mb-2" alt="Avatar">
                                    @endif
                                    <h6 class="mb-1">{{ $realisateur->prenom }} {{ $realisateur->nom }}</h6>
                                    <p class="small text-muted mb-1">{{ $realisateur->nationalite ?: 'Nationalité non renseignée' }}</p>
                                    <span class="badge bg-info text-dark mb-2">{{ $realisateur->type ?: 'Réalisateur' }}</span>
                                    <p class="small mb-3 bio-excerpt">{{ \Illuminate\Support\Str::limit($realisateur->biographie, 140) ?: 'Biographie non renseignée.' }}</p>
                                    <a href="{{ route('public.realisateurs.show', $realisateur) }}" class="btn btn-sm btn-outline-primary mt-auto">Voir le profil</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">{{ $realisateurs->links() }}</div>
            @endif
        </div>
    </div>
    @endif

    @if($showActeurs)
    <div class="card border-0 shadow-sm section-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="section-title">🎭 Acteurs</h4>
                <span class="section-count">{{ $acteurs->total() }} résultat(s)</span>
            </div>
            @if($acteurs->isEmpty())
                <div class="empty-state">Aucun acteur trouvé avec ces filtres.</div>
            @else
                <div class="row g-3">
                    @foreach($acteurs as $acteur)
                        <div class="col-md-6 col-lg-3">
                            <div class="card talent-card h-100">
                                <div class="card-body text-center d-flex flex-column">
                                    @if($acteur->photo)
                                        <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo de {{ $acteur->prenom }}" class="avatar mb-2">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($acteur->prenom.' '.$acteur->nom) }}&background=cccccc&color=222222&size=88" class="avatar mb-2" alt="Avatar">
                                    @endif
                                    <h6 class="mb-1">{{ $acteur->prenom }} {{ $acteur->nom }}</h6>
                                    <p class="small text-muted mb-1">{{ $acteur->nationalite ?: 'Nationalité non renseignée' }}</p>
                                    <span class="badge bg-warning text-dark mb-2">{{ $acteur->type ?: 'Acteur' }}</span>
                                    <p class="small mb-3 bio-excerpt">{{ \Illuminate\Support\Str::limit($acteur->biographie, 140) ?: 'Biographie non renseignée.' }}</p>
                                    <a href="{{ route('public.acteurs.show', $acteur) }}" class="btn btn-sm btn-outline-primary mt-auto">Voir le profil</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">{{ $acteurs->links() }}</div>
            @endif
        </div>
    </div>
    @endif
</div>

@include('public.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
