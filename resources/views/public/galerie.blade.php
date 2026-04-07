@extends('public.layout')
@section('content')
<link rel="stylesheet" href="/css/public/galerie.css">    
<div class="container gallery-shell">
    @php
        $pageVideos = $galeries->getCollection()->where('type_media', 'video')->count();
        $pageImages = $galeries->getCollection()->where('type_media', 'image')->count();
        $groupedGaleries = $galeries->getCollection()->groupBy(fn ($item) => $item->film_id ?: 'sans-film');
    @endphp

    <section class="gallery-hero">
        <h1>Galerie</h1>
        <p>Une vitrine vivante des meilleurs moments du festival, entre images et capsules video.</p>
        <div class="gallery-meta">
            <span class="gallery-pill">{{ $galeries->total() }} media(s)</span>
            <span class="gallery-pill">{{ $pageVideos }} video(s) sur cette page</span>
            <span class="gallery-pill">{{ $pageImages }} image(s) sur cette page</span>
        </div>
    </section>

    @guest
        <div class="alert gallery-guest-alert" role="alert">
            Vous pouvez parcourir la galerie. L'ouverture d'un media complet necessite une connexion.
        </div>
    @endguest

    <div class="gallery-filter-card">
        <form method="GET" action="{{ route('galerie.index') }}" class="row g-2 align-items-end">
            <div class="col-md-8 col-lg-6">
                <label for="film_id" class="form-label mb-1">Filtrer par film</label>
                <select name="film_id" id="film_id" class="form-select">
                    <option value="">Tous les films</option>
                    @foreach($films as $film)
                        <option value="{{ $film->id }}" {{ (string) $selectedFilmId === (string) $film->id ? 'selected' : '' }}>{{ $film->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-lg-2 d-grid">
                <button type="submit" class="btn btn-primary">Appliquer</button>
            </div>
            <div class="col-md-2 col-lg-2 d-grid">
                <a href="{{ route('galerie.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
        @if(!empty($selectedFilmId))
            <div class="gallery-filter-status mt-2">Filtre actif : {{ optional($films->firstWhere('id', (int) $selectedFilmId))->titre ?? 'Film inconnu' }}</div>
        @endif
    </div>

    @if($galeries->count() > 0)
        @foreach($groupedGaleries as $groupKey => $groupItems)
            @php
                $firstItem = $groupItems->first();
                $groupTitle = ($groupKey === 'sans-film' || !$firstItem?->film)
                    ? 'Médias sans film associé'
                    : 'Film : ' . $firstItem->film->titre;
            @endphp

            <section class="gallery-group">
                <div class="gallery-group-header">
                    <h2 class="gallery-group-title">{{ $groupTitle }}</h2>
                    <span class="gallery-group-count">{{ $groupItems->count() }} média(s)</span>
                </div>

                <div class="masonry">
                    @foreach($groupItems as $galerie)
                        <article class="masonry-item">
                            <div class="masonry-media-wrap">
                                <span class="masonry-badge">{{ $galerie->type_media === 'image' ? 'Image' : 'Video' }}</span>

                                @if($galerie->type_media === 'image')
                                    <img src="{{ asset('storage/'.$galerie->fichier) }}" alt="{{ $galerie->titre }}" class="masonry-img">
                                @else
                                    @auth
                                        @php $embedUrl = $galerie->embedUrl(); @endphp
                                        @if($embedUrl)
                                            <div class="ratio ratio-16x9">
                                                <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        @elseif($galerie->lien)
                                            <video controls class="masonry-video">
                                                <source src="{{ $galerie->lien }}" type="video/mp4">
                                            </video>
                                        @elseif($galerie->fichier)
                                            <video controls class="masonry-video">
                                                <source src="{{ asset('storage/'.$galerie->fichier) }}" type="video/mp4">
                                            </video>
                                        @endif
                                    @else
                                        <div class="masonry-locked-preview">
                                            <span class="masonry-locked-icon">🔒</span>
                                            <div class="masonry-locked-text">Connectez-vous pour lire cette video.</div>
                                        </div>
                                    @endauth
                                @endif
                                <div class="masonry-media-overlay">FESPACO Selection</div>
                            </div>

                            <h3 class="masonry-title">{{ $galerie->titre }}</h3>
                            <p class="masonry-date">{{ optional($galerie->date)->format('d/m/Y') ?? optional($galerie->created_at)->format('d/m/Y') }}</p>
                            <a href="{{ route('galerie.show', $galerie) }}" class="masonry-btn">Voir</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    @else
        <div class="gallery-empty">
            Aucun media dans la galerie pour le moment.
        </div>
    @endif

    <div class="mt-4 d-flex justify-content-center">
        {{ $galeries->links() }}
    </div>
</div>
@endsection
