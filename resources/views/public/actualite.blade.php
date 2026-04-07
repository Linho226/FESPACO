@extends('public.layout')

@section('title', ($actualite->titre ?? 'Actualite').' - FESPACO')

@section('content')
<link rel="stylesheet" href="/css/public/actualite.css">

<div class="post-wrap py-4 py-md-5">
    <header class="post-hero">
        <h1 class="post-title">{{ $actualite->titre }}</h1>
        <div class="post-meta">
            <span class="post-chip">Actualite</span>
            <span>Publie le {{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }}</span>
            <span>•</span>
            <span>{{ $actualite->auteur->name ?? '-' }}</span>
        </div>
    </header>

    <article class="post-card">
        @if($actualite->image)
            <img
                src="{{ asset('storage/'.$actualite->image) }}"
                alt="Image actualite"
                class="post-image"
            >
        @endif

        <div class="post-content">
            {!! nl2br(e($actualite->contenu)) !!}

            <div class="post-actions">
                <a href="{{ route('public.actualites') }}" class="post-back">Retour aux actualites</a>
            </div>
        </div>
    </article>
</div>
@endsection
