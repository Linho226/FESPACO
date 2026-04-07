@extends('public.layout')

@section('title', 'Actualites - FESPACO')

@section('content')
<link rel="stylesheet" href="/css/public/actualites.css">

<div class="news-wrap py-4 py-md-5">
    <header class="news-hero">
        <div>
            <h1>Actualites</h1>
            <p>Les dernieres nouvelles du festival FESPACO, en direct de la redaction.</p>
        </div>
        <span class="news-count">{{ $actualites->total() }} publication(s)</span>
    </header>

    <form method="GET" action="{{ route('public.actualites') }}" class="news-filters" aria-label="Filtrer les actualites par date">
        <a href="{{ route('public.actualites') }}" class="news-filter-chip{{ ($period ?? 'toutes') === 'toutes' ? ' is-active' : '' }}">Toutes</a>
        <button type="submit" name="periode" value="aujourdhui" class="news-filter-chip{{ ($period ?? 'toutes') === 'aujourdhui' ? ' is-active' : '' }}">Aujourd'hui</button>
        <button type="submit" name="periode" value="hier" class="news-filter-chip{{ ($period ?? 'toutes') === 'hier' ? ' is-active' : '' }}">Hier</button>
        <button type="submit" name="periode" value="recentes" class="news-filter-chip{{ ($period ?? 'toutes') === 'recentes' ? ' is-active' : '' }}">Récentes</button>
        <button type="submit" name="periode" value="semaine" class="news-filter-chip{{ ($period ?? 'toutes') === 'semaine' ? ' is-active' : '' }}">7 derniers jours</button>
    </form>

    @if($actualites->count() === 0)
        <div class="news-empty">{{ ($period ?? 'toutes') === 'toutes' ? 'Aucune actualite pour le moment.' : 'Aucune actualite ne correspond a cette periode.' }}</div>
    @else
        <section class="news-grid">
            @foreach($actualites as $index => $actualite)
                <article class="news-card">
                    @if($actualite->image)
                        <img src="{{ asset('storage/'.$actualite->image) }}" alt="Image actualite" class="news-card-image">
                    @else
                        <div class="news-card-image"></div>
                    @endif

                    <div class="news-card-body">
                        @if($index === 0)
                            <span class="news-topline">A la une</span>
                        @endif
 

                        <p class="news-meta">
                            {{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }}
                            • {{ $actualite->auteur->name ?? '-' }}
                        </p>
                        <h2 class="news-title h5">{{ $actualite->titre }}</h2>
                        <p class="news-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($actualite->contenu), 165) }}</p>
                        <a href="{{ route('public.actualites.show', $actualite) }}" class="news-btn">Lire la suite</a>
                    </div>
                </article>
            @endforeach
        </section>

        <div class="news-pagination">
            {{ $actualites->links() }}
        </div>
    @endif
</div>
@endsection
