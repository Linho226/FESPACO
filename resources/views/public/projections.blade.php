<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projections - FESPACO</title>
    <script>
        (() => {
            const mq = window.matchMedia('(prefers-color-scheme: dark)');
            const apply = () => document.documentElement.setAttribute('data-bs-theme', mq.matches ? 'dark' : 'light');
            apply();
            if (typeof mq.addEventListener === 'function') mq.addEventListener('change', apply);
            else mq.addListener(apply);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/public/projections.css') }}" rel="stylesheet">
</head>
<body>
    @include('public.navbar')
    
    <div class="container py-4 py-md-5">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <h1 class="page-title">Projections en direct</h1>
                <p class="page-subtitle">Découvrez nos séances et regardez en ligne</p>
            </div>
            <span class="badge-count">{{ $projections->count() }} séance(s)</span>
        </div>

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 12px; border: 1px solid rgba(255,193,7,.3); background: rgba(255,193,7,.05);">
                {{ session('warning') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @guest
            <div class="alert alert-info" role="alert" style="border-radius: 12px; border: 1px solid rgba(13,202,240,.35); background: rgba(13,202,240,.08); color: #d9f6ff;">
                Connexion requise pour visionner une projection en direct.
            </div>
        @endguest

        <!-- Filtres -->
        <div class="filter-card">
            <form method="GET" action="{{ route('public.projections') }}" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="q" class="form-control" placeholder="Film, lieu..."
                        value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="etat">
                        <option value="tous" {{ request('etat', 'tous') === 'tous' ? 'selected' : '' }}>Tous les états</option>
                        <option value="en_cours" {{ request('etat') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="a_venir" {{ request('etat') === 'a_venir' ? 'selected' : '' }}>À venir</option>
                        <option value="arretee" {{ request('etat') === 'arretee' ? 'selected' : '' }}>En pause</option>
                        <option value="terminee" {{ request('etat') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-filter flex-grow-1">🔍 Filtrer</button>
                    <a href="{{ route('public.projections') }}" class="btn btn-filter-reset flex-grow-1">↻ Réinitialiser</a>
                </div>
            </form>
        </div>

        @if($topProjections->isNotEmpty())
            <div class="reco-section">
                <h5 class="reco-title">🔥 À regarder maintenant</h5>
                <div class="row g-3">
                    @foreach($topProjections as $projection)
                        @php
                            $etatReco = $projection->etat();
                            $minutesAvant = now()->diffInMinutes($projection->dateHeure(), false);
                            $secondesAvant = max(0, now()->diffInSeconds($projection->dateHeure(), false));
                            $heuresAvant = intdiv($secondesAvant, 3600);
                            $minutesAvantCompte = intdiv($secondesAvant % 3600, 60);
                            $secondesAvantCompte = $secondesAvant % 60;
                            $projectionPublicUrl = $projection->estTerminee()
                                ? route('public.projections.finished', $projection)
                                : route('public.projections.visionner', $projection);
                        @endphp
                        <div class="col-md-4">
                            <div class="projection-card">
                                <div class="card-body">
                                    <h6 class="projection-card .card-title">{{ $projection->getTitreAffiche() }}</h6>
                                    <p class="location-text">
                                        📍 {{ $projection->lieu }}
                                    </p>
                                    <p class="time-text">
                                        📅 {{ $projection->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($projection->heure)->format('H\hi:s') }}
                                    </p>
                                    @if($projection->estAVenir())
                                        <p class="text-success small"><strong>⏱️ Prochaine dans {{ $heuresAvant }}h{{ str_pad((string) $minutesAvantCompte, 2, '0', STR_PAD_LEFT) }}min{{ str_pad((string) $secondesAvantCompte, 2, '0', STR_PAD_LEFT) }}s</strong></p>
                                    @elseif($projection->estEnCours())
                                        <p class="text-success small"><strong>🔴 En direct</strong></p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    @if(($projection->film?->galeries?->count() ?? 0) > 0)
                                        @auth
                                            <a href="{{ $projectionPublicUrl }}" class="btn-watch">{{ $projection->estTerminee() ? 'Voir le message' : 'Regarder' }}</a>
                                        @else
                                            <a href="{{ $projection->estTerminee() ? $projectionPublicUrl : route('login', ['redirect' => route('public.projections.visionner', $projection, false)]) }}" class="btn-watch">{{ $projection->estTerminee() ? 'Voir le message' : 'Se connecter' }}</a>
                                        @endauth
                                    @else
                                        <span class="text-muted small">Pas de vidéo</span>
                                    @endif
                                    <span class="status-badge bg-{{ $etatReco['badge'] }}">{{ $etatReco['label'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Programme -->
        @if($projections->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">🎬</div>
                <h5>Aucune projection ne correspond à vos critères</h5>
                <p class="mb-0">Essayez d'ajuster vos filtres ou consultez toutes les séances.</p>
            </div>
        @elseif($projectionsProgramme->isNotEmpty())
            <h5 class="reco-title" style="color: #e8e8e8;">Programme complet</h5>
            <div class="row g-3" id="programme">
                @foreach($projectionsProgramme as $projection)
                    @php
                        $etatItem = $projection->etat();
                        $dureeSecondes = $projection->dureeProjectionSecondes();
                        $finPrevue = $projection->finPrevue()->format('H\\hi:s');
                        $minutesAvantSalle = now()->diffInMinutes($projection->dateHeure(), false);
                        $secondesAvantSalle = max(0, now()->diffInSeconds($projection->dateHeure(), false));
                        $heuresAvantSalle = intdiv($secondesAvantSalle, 3600);
                        $minutesAvantSalleCompte = intdiv($secondesAvantSalle % 3600, 60);
                        $secondesAvantSalleCompte = $secondesAvantSalle % 60;
                        $projectionPublicUrl = $projection->estTerminee()
                            ? route('public.projections.finished', $projection)
                            : route('public.projections.visionner', $projection);
                    @endphp
                    <div class="col-md-6 col-lg-4" id="projection-{{ $projection->id }}">
                        <a href="{{ $projectionPublicUrl }}" class="projection-card" style="text-decoration: none;">
                            <div class="card-body">
                                <h6 class="card-title">{{ $projection->getTitreAffiche() }}</h6>
                                <p class="location-text">📍 {{ $projection->lieu }}</p>
                                <p class="time-text">
                                    <strong>📅 {{ $projection->date->format('d/m/Y') }}</strong>
                                </p>
                                <p class="time-text">
                                    ⏰ {{ \Carbon\Carbon::parse($projection->heure)->format('H\hi:s') }} 
                                    (durée: {{ $dureeSecondes > 0 ? \App\Models\Film::formatterDureeSecondes($dureeSecondes) : 'non renseignée' }})
                                </p>
                                @if($projection->estAVenir())
                                    <p class="text-success small mb-2"><strong>⏱️ Prochaine dans {{ $heuresAvantSalle }}h{{ str_pad((string) $minutesAvantSalleCompte, 2, '0', STR_PAD_LEFT) }}min{{ str_pad((string) $secondesAvantSalleCompte, 2, '0', STR_PAD_LEFT) }}s</strong></p>
                                @endif
                                <p class="time-text">
                                    🏁 Fin: <strong>{{ $finPrevue }}</strong>
                                </p>
                                @if($projection->notes)
                                    <p class="text-muted small mb-0">📝 {{ $projection->notes }}</p>
                                @endif
                            </div>
                            <div class="card-footer">
                                @if(($projection->film?->galeries?->count() ?? 0) > 0)
                                    @auth
                                        <span class="btn-watch text-center w-100" style="margin: 0;">{{ $projection->estTerminee() ? 'Voir le message' : 'Regarder' }}</span>
                                    @else
                                        <span class="btn-watch text-center w-100" style="margin: 0;">{{ $projection->estTerminee() ? 'Voir le message' : 'Se connecter' }}</span>
                                    @endauth
                                @else
                                    <span class="text-muted small">⏳ Pas de vidéo</span>
                                @endif
                                <span class="status-badge bg-{{ $etatItem['badge'] }}">{{ $etatItem['label'] }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @include('public.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
