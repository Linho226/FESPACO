@extends('admin.layout')
@section('title', 'Lecture média')

@section('content')
<div class="container px-0 films-admin">
    <div class="media-play-page mx-auto">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 media-header">
            <div>
                <h2 class="mb-1 fw-semibold text-white">Lecture média</h2>
                <p class="mb-0 media-subtitle">{{ $galerie->titre }}</p>
            </div>
            <a href="{{ route('admin.galeries.index') }}" class="btn btn-sm media-back-btn">Retour à la galerie</a>
        </div>

        <div class="card border-0 shadow-sm media-card text-white">
            <div class="card-body p-4 p-md-5 media-card-body">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge media-chip media-chip-type">
                        {{ $galerie->type_media === 'image' ? '🖼 Image' : '🎬 Vidéo' }}
                    </span>
                    @if($galerie->film)
                        <span class="badge media-chip media-chip-film">🎞 {{ $galerie->film->titre }}</span>
                    @endif
                    <span class="small ms-md-auto media-date">{{ $galerie->date?->format('d/m/Y') }}</span>
                </div>

                <div class="media-frame mx-auto">
                    @if($galerie->type_media === 'video' && $galerie->fichier)
                        <div class="ratio ratio-16x9 rounded overflow-hidden media-screen">
                            <video controls style="width:100%; height:100%; object-fit:contain; background:#000;">
                                <source src="{{ asset('storage/'.$galerie->fichier) }}">
                                Votre navigateur ne supporte pas la lecture vidéo.
                            </video>
                        </div>
                    @elseif($embedUrl)
                        <div class="ratio ratio-16x9 rounded overflow-hidden media-screen">
                            <iframe
                                src="{{ $embedUrl }}"
                                title="{{ $galerie->titre }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @elseif($galerie->lien)
                        <div class="ratio ratio-16x9 rounded overflow-hidden media-screen">
                            <iframe src="{{ $galerie->lien }}" title="{{ $galerie->titre }}" frameborder="0"></iframe>
                        </div>
                        <p class="small mt-2 mb-0 text-center media-note">Certains sites peuvent bloquer l’affichage intégré.</p>
                    @elseif($galerie->type_media === 'image' && $galerie->fichier)
                        <div class="text-center rounded p-3 media-screen media-image-wrap">
                            <img src="{{ asset('storage/'.$galerie->fichier) }}" alt="{{ $galerie->titre }}" class="img-fluid rounded" style="max-height:65vh; object-fit:contain;">
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">Aucun média lisible disponible.</div>
                    @endif
                </div>

                @if($galerie->description)
                    <hr class="media-divider">
                    <h6 class="mb-1 media-label">Description</h6>
                    <p class="mb-0 media-description">{{ $galerie->description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .media-play-page {
        max-width: 1020px;
    }

    .media-header {
        padding: 4px 2px;
    }

    .media-subtitle {
        color: #c5d0dd;
        font-size: 0.95rem;
    }

    .media-back-btn {
        color: #e8eef7;
        border: 1px solid rgba(189, 206, 226, 0.45);
        background: rgba(17, 24, 39, 0.35);
        border-radius: 999px;
        padding: 0.4rem 1rem;
    }

    .media-back-btn:hover {
        color: #fff;
        background: rgba(26, 36, 53, 0.8);
        border-color: rgba(210, 225, 244, 0.8);
    }

    .media-card {
        border-radius: 18px;
        background: linear-gradient(165deg, #0f1724 0%, #121c2d 52%, #1a2536 100%);
        border: 1px solid rgba(120, 146, 177, 0.25);
        box-shadow: 0 14px 35px rgba(7, 11, 18, 0.45);
    }

    .media-card-body {
        backdrop-filter: blur(2px);
    }

    .media-frame {
        max-width: 860px;
    }

    .media-screen {
        border: 1px solid rgba(157, 179, 206, 0.2);
        background: #05070b;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02);
    }

    .media-image-wrap {
        background: radial-gradient(circle at top, rgba(66, 81, 102, 0.22), rgba(7, 10, 16, 0.94));
    }

    .media-chip {
        border-radius: 999px;
        padding: 0.42rem 0.75rem;
        font-weight: 600;
        letter-spacing: 0.1px;
    }

    .media-chip-type {
        background: linear-gradient(120deg, #17a2b8, #2f9fff);
        color: #fff;
    }

    .media-chip-film {
        color: #e7edf8;
        background: rgba(69, 88, 113, 0.38);
        border: 1px solid rgba(151, 176, 207, 0.34);
    }

    .media-date {
        color: #c0cde0;
    }

    .media-note {
        color: #b0c1da;
    }

    .media-divider {
        border-color: rgba(171, 193, 220, 0.24);
        margin-top: 1.25rem;
        margin-bottom: 1rem;
    }

    .media-label {
        color: #b6c7de;
        text-transform: uppercase;
        font-size: 0.77rem;
        letter-spacing: 0.7px;
    }

    .media-description {
        color: #e5edf8;
        line-height: 1.65;
    }
</style>
@endpush
