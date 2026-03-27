@extends('admin.layout')

@section('title', 'Ajouter un film')

@section('content')
<style>
    label.form-label { color: #fff; }
</style>
<div class="container">
    <h2 class="mb-4">Ajouter un {{ isset($type) && $type == 'serie' ? 'série' : 'film' }}</h2>
    <form method="POST" action="{{ route('admin.films.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="{{ $type ?? old('type', 'film') }}">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="annee_production" class="form-label">Année de production</label>
            <input type="number" class="form-control" id="annee_production" name="annee_production" value="{{ old('annee_production') }}" required>
        </div>
        <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <input type="text" class="form-control" id="pays" name="pays" value="{{ old('pays') }}" required>
        </div>
        <div class="mb-3">
            <label for="duree" class="form-label">Durée (en minutes)</label>
            <input type="number" class="form-control" id="duree" name="duree" value="{{ old('duree') }}" required>
        </div>
        <div class="mb-3">
            <label for="affiche" class="form-label">Affiche du film</label>
            <input type="file" class="form-control" id="affiche" name="affiche" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="realisateur" class="form-label">Réalisateur</label>
            <input type="text" class="form-control" id="realisateur" name="realisateur" value="{{ old('realisateur') }}" required>
        </div>
        <div class="mb-3">
            <label for="acteurs" class="form-label">Acteurs</label>
            <input type="text" class="form-control" id="acteurs" name="acteurs" value="{{ old('acteurs') }}" required>
        </div>
        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <input type="text" class="form-control" id="categorie" name="categorie" value="{{ old('categorie') }}" required>
        </div>
        @if(($type ?? old('type')) == 'serie')
        <div class="mb-3">
            <label class="form-label">Liens vidéos de la série (un par ligne)</label>
            <textarea class="form-control" name="video_links" rows="3" placeholder="https://youtube.com/episode1\nhttps://vimeo.com/episode2">{{ old('video_links') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Ajouter des fichiers vidéos (optionnel, plusieurs fichiers possibles)</label>
            <input type="file" class="form-control" name="video_files[]" accept="video/*" multiple>
        </div>
        @else
        <div class="mb-3">
            <label for="video" class="form-label">Vidéo du film (optionnel)</label>
            <input type="file" class="form-control" id="video" name="video" accept="video/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Lien vidéo externe (optionnel)</label>
            <input type="url" class="form-control" name="video_link" value="{{ old('video_link') }}" placeholder="https://youtube.com/film">
        </div>
        @endif
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
