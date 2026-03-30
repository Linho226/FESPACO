@extends('admin.layout')

@section('title', $actualite->titre)

@section('content')
<div class="container py-4">
    <h2>{{ $actualite->titre }}</h2>
    <p class="text-muted">Publié le {{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }} par {{ $actualite->auteur->name ?? '-' }}</p>
    @if($actualite->image)
        <img src="{{ asset('storage/'.$actualite->image) }}" alt="Image" class="img-fluid mb-3" style="max-width: 400px;">
    @endif
    <div class="mb-4">{!! nl2br(e($actualite->contenu)) !!}</div>
    <a href="{{ route('admin.actualites.edit', $actualite) }}" class="btn btn-warning">Modifier</a>
    <form action="{{ route('admin.actualites.destroy', $actualite) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette actualité ?');">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Supprimer</button>
    </form>
    <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>
@endsection
