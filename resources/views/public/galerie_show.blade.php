@extends('public.layout')
@section('content')
<div class="container">
    <h1>{{ $galerie->titre }}</h1>
    <div class="mb-4">
        @if($galerie->type_media === 'image')
            <img src="{{ asset('storage/'.$galerie->fichier) }}" class="img-fluid" alt="{{ $galerie->titre }}">
        @else
            <video controls class="w-100">
                <source src="{{ asset('storage/'.$galerie->fichier) }}" type="video/mp4">
            </video>
        @endif
    </div>
    <p>{{ $galerie->description }}</p>
    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($galerie->date)->format('d/m/Y') }}</p>
    <a href="{{ route('galerie.index') }}" class="btn btn-secondary">Retour à la galerie</a>
</div>
@endsection
