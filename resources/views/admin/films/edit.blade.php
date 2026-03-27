@extends('admin.layout')

@section('title', 'Modifier le film')

@section('content')
<div class="container">
    <h2 class="mb-4">Modifier le film</h2>
    <form method="POST" action="{{ route('admin.films.update', $film) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" class="form-control" id="titre" name="titre" value="{{ old('titre', $film->titre) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $film->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="annee_production" class="form-label">Année de production</label>
            <input type="number" class="form-control" id="annee_production" name="annee_production" value="{{ old('annee_production', $film->annee_production) }}" required>
        </div>
        <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <input type="text" class="form-control" id="pays" name="pays" value="{{ old('pays', $film->pays) }}" required>
        </div>
        <div class="mb-3">
            <label for="duree" class="form-label">Durée (en minutes)</label>
            <input type="number" class="form-control" id="duree" name="duree" value="{{ old('duree', $film->duree) }}" required>
        </div>
        <div class="mb-3">
            <label for="affiche" class="form-label">Affiche du film</label>
            @if($film->affiche)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $film->affiche) }}" alt="Affiche" style="max-height:100px;">
                </div>
            @endif
            <input type="file" class="form-control" id="affiche" name="affiche" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="realisateur" class="form-label">Réalisateur</label>
            <input type="text" class="form-control" id="realisateur" name="realisateur" value="{{ old('realisateur', $film->realisateur) }}" required>
        </div>
        <div class="mb-3">
            <label for="acteurs" class="form-label">Acteurs</label>
            <input type="text" class="form-control" id="acteurs" name="acteurs" value="{{ old('acteurs', $film->acteurs) }}" required>
        </div>
        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <input type="text" class="form-control" id="categorie" name="categorie" value="{{ old('categorie', $film->categorie) }}" required>
        </div>
        <input type="hidden" name="type" value="{{ old('type', $film->type) }}">

        @if($film->type == 'serie')
            @php
                $videoLinks = $film->video_links ? json_decode($film->video_links, true) : [];
                $videoFiles = $film->video_files ? json_decode($film->video_files, true) : [];
            @endphp
            <div class="mb-3">
                <label class="form-label">Liens vidéos de la série (un par ligne)</label>
                <textarea class="form-control" name="video_links" rows="3" placeholder="https://youtube.com/episode1\nhttps://vimeo.com/episode2">{{ old('video_links', $videoLinks ? implode("\n", $videoLinks) : '') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Ajouter des fichiers vidéos (optionnel, plusieurs fichiers possibles)</label>
                <input type="file" class="form-control" name="video_files[]" accept="video/*" multiple>
            </div>
            @if(count($videoFiles))
                <div class="mb-3">
                    <label class="form-label">Vidéos existantes :</label>
                    <ul>
                        @foreach($videoFiles as $file)
                            <li>
                                <video src="{{ asset('storage/' . $file) }}" controls style="max-width: 100%; height: 80px;"></video>
                                <a href="{{ asset('storage/' . $file) }}" target="_blank">Télécharger</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @else
            <div class="mb-3">
                <label for="video" class="form-label">Vidéo du film (optionnel)</label>
                <input type="file" class="form-control" id="video" name="video" accept="video/*">
            </div>
            @if($film->video)
                <div class="mb-3">
                    <label class="form-label">Vidéo existante :</label><br>
                    <video src="{{ asset('storage/' . $film->video) }}" controls style="max-width: 100%; height: 120px;"></video>
                    <a href="{{ asset('storage/' . $film->video) }}" target="_blank">Télécharger</a>
                </div>
            @endif
            <div class="mb-3">
                <label class="form-label">Lien vidéo externe (optionnel)</label>
                <input type="url" class="form-control" name="video_link" value="{{ old('video_link', $film->video_link ?? '') }}" placeholder="https://youtube.com/film">
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
