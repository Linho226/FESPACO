@extends('public.layout')

@section('title', 'Films du festival')

@section('content')
<link rel="stylesheet" href="/css/public/films.css">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 films-header">
        <div>
            <h1 class="mb-2">Films du festival</h1>
            <p class="mb-0 films-subtitle">Explore la sélection officielle, découvre les pays représentés et ouvre les projections depuis le menu dédié.</p>
        </div>
        <span class="films-count">{{ $films->total() }} film(s)</span>
    </div>

    <div class="row g-4">
        @forelse($films as $film)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 d-flex align-items-stretch">
                <a href="{{ route('galerie.index', ['film_id' => $film->id]) }}" class="film-card-link">
                <article class="card film-card w-100 h-100">
                    @if($film->affiche)
                        <img src="{{ asset('storage/' . $film->affiche) }}" class="film-cover" alt="Affiche de {{ $film->titre }}">
                    @else
                        <div class="film-cover-fallback">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($film->titre, 0, 1)) }}</div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="film-title">{{ $film->titre }}</h5>
                        <p class="film-description mb-3">{{ \Illuminate\Support\Str::limit($film->description, 120) }}</p>

                        <div class="film-meta">
                            <span class="badge bg-secondary">{{ $film->annee_production ?: 'N/A' }}</span>
                            <span class="film-country">{{ $film->pays ?: 'Pays non renseigné' }}</span>
                        </div>
                    </div>
                </article>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-films">
                    Aucun film pour le moment.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $films->links() }}
    </div>
</div>
@endsection
