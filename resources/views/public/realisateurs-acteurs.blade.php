<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réalisateurs & Acteurs - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .talent-card { border: none; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .avatar { width: 88px; height: 88px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>
@include('public.navbar')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h1 class="mb-0">Réalisateurs & Acteurs</h1>
        <span class="badge bg-dark">{{ $realisateurs->total() + $acteurs->total() }} talent(s)</span>
    </div>
    <p class="text-muted mb-4">Découvrez les talents du festival, recherchez par nom, type ou nationalité.</p>

    <form method="GET" action="{{ route('public.realisateurs_acteurs') }}" class="row g-2 mb-4">
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
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
        <div class="col-md-1 d-grid">
            <a href="{{ route('public.realisateurs_acteurs') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    @if($showRealisateurs)
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-body">
            <h4 class="mb-3">🎬 Réalisateurs</h4>
            @if($realisateurs->isEmpty())
                <div class="alert alert-light border">Aucun réalisateur trouvé.</div>
            @else
                <div class="row g-3">
                    @foreach($realisateurs as $realisateur)
                        <div class="col-md-6 col-lg-3">
                            <div class="card talent-card h-100">
                                <div class="card-body text-center">
                                    @if($realisateur->photo)
                                        <img src="{{ asset('storage/'.$realisateur->photo) }}" alt="Photo de {{ $realisateur->prenom }}" class="avatar mb-2">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($realisateur->prenom.' '.$realisateur->nom) }}&background=cccccc&color=222222&size=88" class="avatar mb-2" alt="Avatar">
                                    @endif
                                    <h6 class="mb-1">{{ $realisateur->prenom }} {{ $realisateur->nom }}</h6>
                                    <p class="small text-muted mb-1">{{ $realisateur->nationalite ?: 'Nationalité non renseignée' }}</p>
                                    <span class="badge bg-info text-dark mb-2">{{ $realisateur->type ?: 'Réalisateur' }}</span>
                                    <p class="small text-muted mb-3">{{ \Illuminate\Support\Str::limit($realisateur->biographie, 90) ?: 'Biographie non renseignée.' }}</p>
                                    <a href="{{ route('public.realisateurs.show', $realisateur) }}" class="btn btn-sm btn-outline-primary">Voir le profil</a>
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
    <div class="card border-0 shadow-sm" style="border-radius:12px;">
        <div class="card-body">
            <h4 class="mb-3">🎭 Acteurs</h4>
            @if($acteurs->isEmpty())
                <div class="alert alert-light border">Aucun acteur trouvé.</div>
            @else
                <div class="row g-3">
                    @foreach($acteurs as $acteur)
                        <div class="col-md-6 col-lg-3">
                            <div class="card talent-card h-100">
                                <div class="card-body text-center">
                                    @if($acteur->photo)
                                        <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo de {{ $acteur->prenom }}" class="avatar mb-2">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($acteur->prenom.' '.$acteur->nom) }}&background=cccccc&color=222222&size=88" class="avatar mb-2" alt="Avatar">
                                    @endif
                                    <h6 class="mb-1">{{ $acteur->prenom }} {{ $acteur->nom }}</h6>
                                    <p class="small text-muted mb-1">{{ $acteur->nationalite ?: 'Nationalité non renseignée' }}</p>
                                    <span class="badge bg-warning text-dark mb-2">{{ $acteur->type ?: 'Acteur' }}</span>
                                    <p class="small text-muted mb-3">{{ \Illuminate\Support\Str::limit($acteur->biographie, 90) ?: 'Biographie non renseignée.' }}</p>
                                    <a href="{{ route('public.acteurs.show', $acteur) }}" class="btn btn-sm btn-outline-primary">Voir le profil</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
