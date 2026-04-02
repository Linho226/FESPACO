@extends('public.layout')

@section('title', 'Films du festival')

@section('content')
<style>
    [data-bs-theme="light"] {
        --films-surface: #ffffff;
        --films-border: rgba(15, 23, 42, 0.1);
        --films-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
        --films-muted: #64748b;
        --films-title: #0f172a;
    }

    [data-bs-theme="dark"] {
        --films-surface: #111b2a;
        --films-border: rgba(148, 163, 184, 0.2);
        --films-shadow: 0 14px 30px rgba(0, 0, 0, 0.35);
        --films-muted: #94a3b8;
        --films-title: #e2e8f0;
    }

    .films-header h1 {
        color: var(--films-title);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .films-subtitle {
        color: var(--films-muted);
        max-width: 700px;
    }

    .films-count {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        border: 1px solid var(--films-border);
        background: var(--films-surface);
        color: var(--films-title);
        font-weight: 700;
        font-size: 0.84rem;
        padding: 0.38rem 0.82rem;
    }

    .film-card {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--films-border);
        background: var(--films-surface);
        box-shadow: var(--films-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .film-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.22);
    }

    .film-card-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        width: 100%;
        height: 100%;
    }

    .film-cover {
        height: 260px;
        width: 100%;
        object-fit: cover;
        display: block;
    }

    .film-cover-fallback {
        height: 260px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #f5a623;
        background: linear-gradient(135deg, rgba(245, 166, 35, 0.14), rgba(59, 130, 246, 0.13));
    }

    .film-title {
        color: var(--films-title);
        font-weight: 700;
        margin-bottom: 0.45rem;
    }

    .film-description {
        color: var(--films-muted);
        min-height: 3.9em;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .film-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        gap: 0.4rem;
    }

    .film-country {
        color: var(--films-muted);
        font-size: 0.86rem;
        font-weight: 600;
    }

    .empty-films {
        border: 1px dashed var(--films-border);
        border-radius: 14px;
        background: var(--films-surface);
        color: var(--films-muted);
        text-align: center;
        padding: 2rem 1rem;
    }
</style>

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
