@extends('public.layout')
@section('content')
<div class="container">
    <h1>{{ $galerie->titre }}</h1>
    <div class="mb-4">
        @if($galerie->type_media === 'image')
            <img src="{{ asset('storage/'.$galerie->fichier) }}" class="img-fluid" alt="{{ $galerie->titre }}">
        @else
            @php $embedUrl = $galerie->embedUrl(); @endphp
            @if($embedUrl)
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                    <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            @elseif($galerie->lien)
                <video controls class="w-100 rounded-3">
                    <source src="{{ $galerie->lien }}" type="video/mp4">
                </video>
            @elseif($galerie->fichier)
                <video controls class="w-100 rounded-3">
                    <source src="{{ asset('storage/'.$galerie->fichier) }}" type="video/mp4">
                </video>
            @endif
        @endif
    </div>
    <p>{{ $galerie->description }}</p>
    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($galerie->date)->format('d/m/Y') }}</p>
    <a href="{{ route('galerie.index') }}" class="btn btn-secondary">Retour à la galerie</a>
</div>
@endsection
