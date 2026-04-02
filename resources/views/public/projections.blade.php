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
    <style>
        :root {
            --primary: #006241;
            --success: #10b981;
        }

        [data-bs-theme="dark"] {
            --proj-bg: #0f1722;
            --proj-text: #e5e7eb;
        }

        [data-bs-theme="light"] {
            --proj-bg: #f8fafc;
            --proj-text: #1f2937;
        }
        
        * { box-sizing: border-box; }
        body { 
            background: var(--proj-bg);
            color: var(--proj-text);
            min-height: 100vh;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #d0d0d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: rgba(255,255,255,.6);
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        
        .badge-count {
            background: var(--primary);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .filter-card {
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            margin-bottom: 2rem;
        }
        
        .filter-card .form-control,
        .filter-card .form-select {
            background: rgba(255,255,255,.05);
            border-color: rgba(255,255,255,.1);
            color: #f0f0f0;
            border-radius: 8px;
        }
        
        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            background: rgba(255,255,255,.08);
            border-color: var(--primary);
            color: #f0f0f0;
            box-shadow: 0 0 0 0.2rem rgba(0, 98, 65, .2);
        }
        
        .filter-card .form-control::placeholder,
        .filter-card .form-select option {
            color: rgba(255,255,255,.5);
        }
        
        .btn-filter {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-filter:hover {
            background: #00582f;
            border-color: #00582f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 98, 65, .3);
        }
        
        .btn-filter-reset {
            background: rgba(255,255,255,.1);
            border-color: rgba(255,255,255,.2);
            color: #f0f0f0;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-filter-reset:hover {
            background: rgba(255,255,255,.15);
            border-color: rgba(255,255,255,.3);
        }
        
        .reco-section {
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .reco-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #ff6b6b;
        }
        
        .projection-card {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: none;
            border-radius: 14px;
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.1);
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            backdrop-filter: blur(10px);
        }
        
        .projection-card:hover {
            background: rgba(0, 98, 65, .05);
            border-color: rgba(0, 98, 65, .3);
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 98, 65, .2);
        }
        
        .projection-card .card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        
        .projection-card .card-title {
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 0.75rem;
        }
        
        .projection-card .location-text {
            color: rgba(255,255,255,.6);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .projection-card .time-text {
            color: rgba(255,255,255,.7);
            font-size: 0.85rem;
        }
        
        .projection-card .card-footer {
            background: transparent;
            border-top: 1px solid rgba(255,255,255,.1);
            padding-top: 1rem;
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .status-badge {
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.8rem;
        }
        
        .btn-watch {
            background: var(--primary);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-watch:hover {
            background: #00582f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 98, 65, .3);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 14px;
            color: rgba(255,255,255,.6);
        }
        
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        @media (prefers-color-scheme: light) {

            .page-title {
                background: none;
                color: #111827;
                -webkit-text-fill-color: #111827;
            }

            .page-subtitle {
                color: rgba(31, 41, 55, .7);
            }

            .reco-title {
                color: #d43f3f;
            }

            .filter-card,
            .reco-section,
            .projection-card,
            .empty-state {
                background: rgba(255,255,255,.88);
                border-color: rgba(15, 23, 42, .12);
                box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
            }

            .filter-card .form-control,
            .filter-card .form-select {
                background: #ffffff;
                border-color: rgba(15, 23, 42, .18);
                color: #111827;
            }

            .filter-card .form-control:focus,
            .filter-card .form-select:focus {
                background: #ffffff;
                color: #111827;
            }

            .filter-card .form-control::placeholder,
            .filter-card .form-select option {
                color: rgba(31, 41, 55, .55);
            }

            .btn-filter-reset {
                background: rgba(15, 23, 42, .08);
                border-color: rgba(15, 23, 42, .18);
                color: #1f2937;
            }

            .btn-filter-reset:hover {
                background: rgba(15, 23, 42, .14);
                border-color: rgba(15, 23, 42, .22);
            }

            .projection-card .card-title,
            .projection-card .location-text,
            .projection-card .time-text,
            .empty-state {
                color: #1f2937;
            }

            .projection-card .time-text strong,
            .projection-card .text-muted,
            .text-muted {
                color: #475569 !important;
            }

            .projection-card .card-footer {
                border-top-color: rgba(15, 23, 42, .1);
            }
        }

    </style>
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
                        @endphp
                        <div class="col-md-4">
                            <div class="projection-card">
                                <div class="card-body">
                                    <h6 class="projection-card .card-title">{{ $projection->getTitreAffiche() }}</h6>
                                    <p class="location-text">
                                        📍 {{ $projection->lieu }}
                                    </p>
                                    <p class="time-text">
                                        📅 {{ $projection->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($projection->heure)->format('H\hi') }}
                                    </p>
                                    @if($projection->estAVenir())
                                        <p class="text-success small"><strong>⏱️ Prochaine dans {{ max(0, intdiv($minutesAvant, 60)) }}h{{ $minutesAvant % 60 }}min</strong></p>
                                    @elseif($projection->estEnCours())
                                        <p class="text-success small"><strong>🔴 En direct</strong></p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    @if(($projection->film?->galeries?->count() ?? 0) > 0)
                                        @auth
                                            <a href="{{ route('public.projections.visionner', $projection) }}" class="btn-watch">Regarder</a>
                                        @else
                                            <a href="{{ route('login', ['redirect' => route('public.projections.visionner', $projection, false)]) }}" class="btn-watch">Se connecter</a>
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
                    @endphp
                    <div class="col-md-6 col-lg-4" id="projection-{{ $projection->id }}">
                        <a href="{{ route('public.projections.visionner', $projection) }}" class="projection-card" style="text-decoration: none;">
                            <div class="card-body">
                                <h6 class="card-title">{{ $projection->getTitreAffiche() }}</h6>
                                <p class="location-text">📍 {{ $projection->lieu }}</p>
                                <p class="time-text">
                                    <strong>📅 {{ $projection->date->format('d/m/Y') }}</strong>
                                </p>
                                <p class="time-text">
                                    ⏰ {{ \Carbon\Carbon::parse($projection->heure)->format('H\hi') }} 
                                    (durée: {{ $dureeSecondes > 0 ? \App\Models\Film::formatterDureeSecondes($dureeSecondes) : 'non renseignée' }})
                                </p>
                                @if($projection->estAVenir())
                                    <p class="text-success small mb-2"><strong>⏱️ Prochaine dans {{ max(0, intdiv($minutesAvantSalle, 60)) }}h{{ $minutesAvantSalle % 60 }}min</strong></p>
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
                                        <span class="btn-watch text-center w-100" style="margin: 0;">Regarder</span>
                                    @else
                                        <span class="btn-watch text-center w-100" style="margin: 0;">Se connecter</span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
