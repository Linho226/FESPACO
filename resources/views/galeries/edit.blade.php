@extends('admin.layout')
@section('content')
<div class="container">
    <h1>Modifier le média</h1>
    <form action="{{ route('admin.galeries.update', $galerie) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $galerie->titre) }}" required>
        </div>
        <div class="mb-3">
            <label for="type_media" class="form-label">Type de média</label>
            <select name="type_media" id="type_media" class="form-select" required>
                <option value="image" @if($galerie->type_media=='image') selected @endif>Image</option>
                <option value="video" @if($galerie->type_media=='video') selected @endif>Vidéo</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fichier" class="form-label">Fichier (image ou vidéo)</label>
            <input type="file" name="fichier" id="fichier" class="form-control" accept="image/*,video/*">
            @if($galerie->fichier)
                <div class="mt-2">
                    <small>Fichier actuel :</small><br>
                    @if($galerie->type_media === 'image')
                        <img src="{{ asset('storage/'.$galerie->fichier) }}" alt="" style="max-width:100px;">
                    @else
                        <video src="{{ asset('storage/'.$galerie->fichier) }}" style="max-width:100px;" controls></video>
                    @endif
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $galerie->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $galerie->date) }}" required>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('admin.galeries.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
