@extends('admin.layout')

@section('title', 'Créer une actualité')

@section('content')
<div class="container py-4">
    <h2>Nouvelle actualité</h2>
    <form action="{{ route('admin.actualites.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required>
        </div>
        <div class="mb-3">
            <label for="contenu" class="form-label">Contenu</label>
            <textarea name="contenu" id="contenu" class="form-control" rows="6" required>{{ old('contenu') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="date_publication" class="form-label">Date de publication</label>
            <input type="date" name="date_publication" id="date_publication" class="form-control" value="{{ old('date_publication') }}" required>
        </div>
        <button type="submit" class="btn btn-success">Publier</button>
        <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
