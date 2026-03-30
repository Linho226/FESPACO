@extends('admin.layout')

@section('title', 'Modifier une actualité')

@section('content')
<div class="container py-4">
    <h2>Modifier l'actualité</h2>
    <form action="{{ route('admin.actualites.update', $actualite) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $actualite->titre) }}" required>
        </div>
        <div class="mb-3">
            <label for="contenu" class="form-label">Contenu</label>
            <textarea name="contenu" id="contenu" class="form-control" rows="6" required>{{ old('contenu', $actualite->contenu) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image (laisser vide pour ne pas changer)</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($actualite->image)
                <img src="{{ asset('storage/'.$actualite->image) }}" alt="Image actuelle" class="img-thumbnail mt-2" style="max-width: 200px;">
            @endif
        </div>
        <div class="mb-3">
            <label for="date_publication" class="form-label">Date de publication</label>
            <input type="date" name="date_publication" id="date_publication" class="form-control" value="{{ old('date_publication', \Carbon\Carbon::parse($actualite->date_publication)->format('Y-m-d')) }}" required>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
