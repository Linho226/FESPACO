@extends('public.layout')
@section('content')
<style>
    .masonry {
        column-count: 4;
        column-gap: 1.5rem;
    }
    @media (max-width: 1200px) { .masonry { column-count: 3; } }
    @media (max-width: 900px) { .masonry { column-count: 2; } }
    @media (max-width: 600px) { .masonry { column-count: 1; } }
    .masonry-item {
        break-inside: avoid;
        margin-bottom: 1.5rem;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.08);
        background: #fff;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .masonry-item:hover {
        box-shadow: 0 4px 24px 0 rgba(0,0,0,0.16);
    }
    .masonry-img, .masonry-video {
        width: 100%;
        display: block;
        border-radius: 1.2rem 1.2rem 0 0;
        object-fit: cover;
        max-height: 340px;
        min-height: 220px;
        background: #eee;
    }
    .masonry-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0.7rem 1rem 0.2rem 1rem;
        color: #222;
        text-align: left;
    }
    .masonry-btn {
        margin: 0.5rem 1rem 1rem 1rem;
        display: block;
        width: fit-content;
        background: #f5f5f5;
        color: #007bff;
        border: none;
        border-radius: 0.7rem;
        padding: 0.3rem 1.1rem;
        font-size: 0.95rem;
        text-decoration: none;
        transition: background 0.2s;
    }
    .masonry-btn:hover {
        background: #e2e6ea;
        color: #0056b3;
    }
</style>
<div class="container">
    <h1 class="mb-4">Galerie</h1>
    <div class="masonry">
        @foreach($galeries as $galerie)
            <div class="masonry-item">
                @if($galerie->type_media === 'image')
                    <img src="{{ asset('storage/'.$galerie->fichier) }}" alt="{{ $galerie->titre }}" class="masonry-img">
                @else
                    <video controls class="masonry-video">
                        <source src="{{ asset('storage/'.$galerie->fichier) }}" type="video/mp4">
                    </video>
                @endif
                <div class="masonry-title">{{ $galerie->titre }}</div>
                <a href="{{ route('galerie.show', $galerie) }}" class="masonry-btn">Voir</a>
            </div>
        @endforeach
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $galeries->links() }}
    </div>
</div>
@endsection
