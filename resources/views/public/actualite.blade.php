@extends('admin.layout')

@section('title', $actualite->titre)

@section('content')
<div class="container py-4">
    <h1>{{ $actualite->titre }}</h1>
    <p class="text-muted">Publié le {{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }} par {{ $actualite->auteur->name ?? '-' }}</p>
    @if($actualite->image)
        <img src="{{ asset('storage/'.$actualite->image) }}" alt="Image" class="img-fluid mb-3" style="max-width: 400px;">
    @endif
    <div class="mb-4">{!! nl2br(e($actualite->contenu)) !!}</div>
    <a href="{{ route('public.actualites') }}" class="btn btn-secondary">Retour à la liste</a>
</div>
@endsection
