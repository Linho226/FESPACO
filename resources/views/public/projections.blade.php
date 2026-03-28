<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projections - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .projection-card { border: none; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    </style>
</head>
<body>
    @include('public.navbar')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h1 class="mb-0">Projections publiques</h1>
            <span class="badge bg-dark">{{ $projections->count() }} projection(s)</span>
        </div>
        <p class="text-muted mb-4">Choisissez selon vos envies, l'état de diffusion et la salle.</p>

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if($recommandees->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                <div class="card-body">
                    <h5 class="mb-3">🔥 Recommandé maintenant</h5>
                    <div class="row g-3">
                        @foreach($recommandees as $projection)
                            @php
                                $etatReco = $projection->etat();
                                $minutesAvant = now()->diffInMinutes($projection->dateHeure(), false);
                            @endphp
                            <div class="col-md-4">
                                <div class="p-3 rounded border h-100 bg-white">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <strong>{{ $projection->film->titre ?? 'Film' }}</strong>
                                        <span class="badge bg-{{ $etatReco['badge'] }}">{{ $etatReco['label'] }}</span>
                                    </div>
                                    <div class="small text-muted mt-2">
                                        {{ $projection->salle }} • {{ $projection->lieu }}
                                    </div>
                                    <div class="small mt-2">
                                        {{ $projection->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($projection->heure)->format('H\\hi') }}
                                    </div>
                                    @if($projection->estAVenir())
                                        <div class="small text-primary mt-1">
                                            Dans {{ max(0, intdiv($minutesAvant, 60)) }}h{{ $minutesAvant % 60 }}min
                                        </div>
                                    @elseif($projection->estEnCours())
                                        <div class="small text-success mt-1">En cours de diffusion</div>
                                    @endif
                                    @if(($projection->film?->galeries?->count() ?? 0) > 0)
                                        <a class="btn btn-sm btn-outline-primary mt-2"
                                           href="{{ route('public.projections.visionner', $projection) }}">
                                            Regarder cette séance
                                        </a>
                                    @else
                                        <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small" role="alert">
                                            Cette séance n’a pas encore de vidéo disponible.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form method="GET" action="{{ route('public.projections') }}" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Film, salle, lieu...">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="salle">
                    <option value="">Toutes les salles</option>
                    @foreach($salles as $salle)
                        <option value="{{ $salle }}" {{ request('salle') === $salle ? 'selected' : '' }}>{{ $salle }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="etat">
                    <option value="tous" {{ request('etat', 'tous') === 'tous' ? 'selected' : '' }}>Tous</option>
                    <option value="en_cours" {{ request('etat') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="a_venir" {{ request('etat') === 'a_venir' ? 'selected' : '' }}>À venir</option>
                    <option value="arretee" {{ request('etat') === 'arretee' ? 'selected' : '' }}>En pause</option>
                    <option value="terminee" {{ request('etat') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-primary w-100" type="submit">Filtrer</button>
                <a class="btn btn-outline-secondary w-100" href="{{ route('public.projections') }}">Reset</a>
            </div>
        </form>

        @if($projections->isEmpty())
            <div class="alert alert-info">Aucune projection ne correspond à vos critères.</div>
        @else
            <div class="row g-3" id="programme">
                @foreach($projections as $projection)
                    @php
                        $etatItem = $projection->etat();
                        $duree = (int) ($projection->film->duree ?? 0);
                        $finPrevue = $projection->finPrevue()->format('H\\hi');
                    @endphp
                    <div class="col-md-6 col-lg-4" id="projection-{{ $projection->id }}">
                        <div class="card projection-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $projection->film->titre ?? 'Film' }}</h5>
                                    <span class="badge bg-{{ $etatItem['badge'] }}">{{ $etatItem['icon'] }} {{ $etatItem['label'] }}</span>
                                </div>
                                <p class="text-muted mb-2">{{ $projection->lieu }} • {{ $projection->salle }}</p>
                                <ul class="list-unstyled small mb-0">
                                    <li><strong>Date :</strong> {{ $projection->date->format('d/m/Y') }}</li>
                                    <li><strong>Début :</strong> {{ \Carbon\Carbon::parse($projection->heure)->format('H\\hi') }}</li>
                                    <li><strong>Durée :</strong> {{ $duree > 0 ? $duree.' min' : 'Non renseignée' }}</li>
                                    <li><strong>Fin prévue :</strong> {{ $finPrevue }}</li>
                                </ul>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0">
                                @if($projection->notes)
                                    <small class="text-muted">{{ $projection->notes }}</small>
                                @endif
                                <div class="mt-2">
                                    @if(($projection->film?->galeries?->count() ?? 0) > 0)
                                        <a class="btn btn-sm btn-primary"
                                           href="{{ route('public.projections.visionner', $projection) }}">
                                            Regarder cette séance
                                        </a>
                                    @else
                                        <div class="alert alert-warning py-2 px-3 mb-0 small" role="alert">
                                            Cette séance n’a pas encore de vidéo disponible.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
