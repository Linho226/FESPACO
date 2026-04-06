@extends('public.layout')
@section('content')
<style>
    [data-bs-theme="light"] {
        --gallery-page-overlay-a: rgba(245, 166, 35, 0.11);
        --gallery-page-overlay-b: rgba(59, 130, 246, 0.1);
        --gallery-surface: #ffffff;
        --gallery-surface-soft: #f8fbff;
        --gallery-border: rgba(15, 23, 42, 0.12);
        --gallery-text: #101a2b;
        --gallery-muted: #5f6f86;
        --gallery-shadow: 0 14px 28px rgba(15, 23, 42, 0.1);
        --gallery-hero-border: rgba(15, 23, 42, 0.1);
        --gallery-hero-bg: linear-gradient(135deg, rgba(245, 166, 35, 0.18), rgba(59, 130, 246, 0.08));
        --gallery-preview: linear-gradient(145deg, rgba(15, 23, 42, 0.78), rgba(30, 41, 59, 0.68));
        --gallery-btn-bg: #0f172a;
        --gallery-btn-text: #f8fafc;
        --gallery-btn-hover: #1e293b;
        --gallery-info-bg: rgba(13, 202, 240, 0.08);
        --gallery-info-border: rgba(13, 202, 240, 0.35);
        --gallery-info-text: #0c4a5a;
        --gallery-input-bg: #ffffff;
        --gallery-input-text: #101a2b;
        --gallery-input-border: rgba(15, 23, 42, 0.15);
    }

    [data-bs-theme="dark"] {
        --gallery-page-overlay-a: rgba(245, 166, 35, 0.12);
        --gallery-page-overlay-b: rgba(56, 189, 248, 0.08);
        --gallery-surface: #141f31;
        --gallery-surface-soft: #0f1726;
        --gallery-border: rgba(148, 163, 184, 0.22);
        --gallery-text: #e6edf9;
        --gallery-muted: #9ca9bc;
        --gallery-shadow: 0 16px 30px rgba(2, 6, 23, 0.45);
        --gallery-hero-border: rgba(148, 163, 184, 0.25);
        --gallery-hero-bg: linear-gradient(135deg, rgba(245, 166, 35, 0.15), rgba(30, 64, 175, 0.18));
        --gallery-preview: linear-gradient(145deg, rgba(15, 23, 42, 0.88), rgba(12, 21, 35, 0.8));
        --gallery-btn-bg: #f5a623;
        --gallery-btn-text: #151515;
        --gallery-btn-hover: #e19319;
        --gallery-info-bg: rgba(8, 145, 178, 0.14);
        --gallery-info-border: rgba(34, 211, 238, 0.38);
        --gallery-info-text: #d8f5fb;
        --gallery-input-bg: #172235;
        --gallery-input-text: #e6edf9;
        --gallery-input-border: rgba(148, 163, 184, 0.28);
    }

    .gallery-shell {
        position: relative;
        isolation: isolate;
        padding-top: 0.3rem;
    }

    .gallery-shell::before {
        content: "";
        position: absolute;
        inset: -2rem -1rem auto -1rem;
        height: 300px;
        z-index: -1;
        pointer-events: none;
        background:
            radial-gradient(circle at 15% 30%, var(--gallery-page-overlay-a), transparent 45%),
            radial-gradient(circle at 80% 15%, var(--gallery-page-overlay-b), transparent 50%);
    }

    .gallery-hero {
        background: var(--gallery-hero-bg);
        border: 1px solid var(--gallery-hero-border);
        border-radius: 18px;
        padding: 1.1rem 1.2rem;
        margin-bottom: 1.4rem;
    }

    .gallery-hero h1 {
        color: var(--gallery-text);
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0.35rem;
    }

    .gallery-hero p {
        color: var(--gallery-muted);
        margin-bottom: 0;
    }

    .gallery-meta {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.75rem;
    }

    .gallery-pill {
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.28rem 0.72rem;
        border: 1px solid var(--gallery-border);
        background: var(--gallery-surface);
        color: var(--gallery-text);
    }

    .masonry {
        column-count: 3;
        column-gap: 1.2rem;
    }

    @media (max-width: 1200px) { .masonry { column-count: 2; } }
    @media (max-width: 700px) { .masonry { column-count: 1; } }

    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.2rem;
        border-radius: 1rem;
        box-shadow: var(--gallery-shadow);
        background: var(--gallery-surface);
        border: 1px solid var(--gallery-border);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .masonry-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.24);
    }

    .masonry-img, .masonry-video {
        width: 100%;
        display: block;
        border-radius: 1rem 1rem 0 0;
        object-fit: cover;
        max-height: 330px;
        min-height: 210px;
        background: var(--gallery-surface-soft);
    }

    .masonry-media-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 1rem 1rem 0 0;
        background: var(--gallery-surface-soft);
    }

    .masonry-badge {
        position: absolute;
        top: 0.7rem;
        left: 0.7rem;
        z-index: 2;
        border-radius: 999px;
        padding: 0.25rem 0.62rem;
        font-size: 0.74rem;
        font-weight: 700;
        color: #f8fafc;
        background: rgba(15, 23, 42, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }

    .masonry-media-overlay {
        position: absolute;
        inset: auto 0 0 0;
        z-index: 1;
        padding: 1.6rem 0.8rem 0.7rem;
        color: #fff;
        font-size: 0.76rem;
        font-weight: 600;
        letter-spacing: 0.2px;
        background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.68) 85%);
    }

    .masonry-title {
        font-size: 1.04rem;
        font-weight: 700;
        margin: 0.8rem 0.9rem 0.2rem 0.9rem;
        color: var(--gallery-text);
        text-align: left;
    }

    .masonry-date {
        margin: 0 0.9rem 0.5rem;
        color: var(--gallery-muted);
        font-size: 0.8rem;
    }

    .masonry-locked-preview {
        min-height: 210px;
        max-height: 330px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        padding: 1.25rem;
        color: #fff;
        text-align: center;
        background: var(--gallery-preview);
    }
    .masonry-locked-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        background: rgba(245, 166, 35, 0.18);
        border: 1px solid rgba(245, 166, 35, 0.35);
    }
    .masonry-locked-text {
        font-size: 0.92rem;
        line-height: 1.45;
        opacity: 0.92;
    }

    .masonry-btn {
        margin: 0.5rem 0.9rem 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--gallery-btn-bg);
        color: var(--gallery-btn-text);
        border: 1px solid transparent;
        border-radius: 0.72rem;
        padding: 0.36rem 0.95rem;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .masonry-btn:hover {
        background: var(--gallery-btn-hover);
        color: var(--gallery-btn-text);
        transform: translateY(-1px);
    }

    .gallery-empty {
        border-radius: 14px;
        border: 1px dashed var(--gallery-border);
        background: var(--gallery-surface);
        color: var(--gallery-muted);
        text-align: center;
        padding: 2rem 1rem;
    }

    .gallery-group {
        margin-bottom: 1.55rem;
    }

    .gallery-group-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.7rem;
    }

    .gallery-group-title {
        margin: 0;
        color: var(--gallery-text);
        font-size: 1.1rem;
        font-weight: 800;
    }

    .gallery-group-count {
        border-radius: 999px;
        padding: 0.2rem 0.62rem;
        font-size: 0.76rem;
        font-weight: 700;
        border: 1px solid var(--gallery-border);
        color: var(--gallery-muted);
        background: var(--gallery-surface);
    }

    .gallery-filter-card {
        background: var(--gallery-surface);
        border: 1px solid var(--gallery-border);
        border-radius: 14px;
        padding: 0.9rem;
        margin-bottom: 1rem;
    }

    .gallery-guest-alert {
        border-radius: 12px;
        border: 1px solid var(--gallery-info-border);
        background: var(--gallery-info-bg);
        color: var(--gallery-info-text);
    }

    .gallery-filter-card .form-label {
        color: var(--gallery-text);
        font-weight: 600;
    }

    .gallery-filter-card .form-select {
        background-color: var(--gallery-input-bg);
        color: var(--gallery-input-text);
        border-color: var(--gallery-input-border);
    }

    .gallery-filter-card .form-select:focus {
        border-color: rgba(245, 166, 35, 0.55);
        box-shadow: 0 0 0 0.2rem rgba(245, 166, 35, 0.18);
    }

    .gallery-filter-card .btn-outline-secondary {
        border-color: var(--gallery-input-border);
        color: var(--gallery-text);
    }

    .gallery-filter-card .btn-outline-secondary:hover {
        background: var(--gallery-surface-soft);
        color: var(--gallery-text);
        border-color: var(--gallery-input-border);
    }

    .gallery-filter-status {
        color: var(--gallery-muted);
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
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
