@extends('admin.layout')

@section('title', 'Détail du film')

@section('content')
<div class="container px-0 films-admin">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1">{{ $film->titre }}</h2>
            <p class="text-muted mb-0">Fiche technique du film.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.films.index') }}" class="btn btn-outline-secondary">Retour</a>
            <a href="{{ route('admin.films.edit', $film) }}" class="btn btn-warning">Modifier</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-body p-4 p-md-5">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-3 mb-2">
                        <div class="col-sm-6 col-xl-4">
                            <span class="text-muted d-block small">Année</span>
                            <strong>{{ $film->annee_production }}</strong>
                        </div>
                        <div class="col-sm-6 col-xl-4">
                            <span class="text-muted d-block small">Durée</span>
                            <strong>{{ $film->duree }} min</strong>
                        </div>
                        <div class="col-sm-6 col-xl-4">
                            <span class="text-muted d-block small">Pays</span>
                            <strong>{{ $film->pays }}</strong>
                        </div>
                        <div class="col-sm-6 col-xl-6">
                            <span class="text-muted d-block small">Catégorie</span>
                            <strong>{{ $film->categorie }}</strong>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Réalisateur</h6>
                        <p class="mb-0">{{ $film->realisateur }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Acteurs</h6>
                        <p class="mb-0">{{ $film->acteurs }}</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-muted mb-1">Description / Synopsis</h6>
                        <p class="mb-0">{{ $film->description ?: 'Aucune description fournie.' }}</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="border rounded p-3 bg-light text-center h-100 d-flex flex-column align-items-center justify-content-center">
                        @if($film->affiche)
                            <img src="{{ asset('storage/'.$film->affiche) }}" alt="Affiche de {{ $film->titre }}" class="img-fluid rounded" style="max-height:280px; object-fit:cover;">
                        @else
                            <span class="text-muted small">Aucune affiche disponible.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
