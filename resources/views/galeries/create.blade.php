@extends('admin.layout')
@section('content')
<div class="container">
    <h1>Ajouter un média</h1>
    <form action="{{ route('admin.galeries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required>
        </div>
        <div class="mb-3">
            <label for="type_media" class="form-label">Type de média</label>
            <select name="type_media" id="type_media" class="form-select" required>
                <option value="image">Image</option>
                <option value="video">Vidéo</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fichier" class="form-label">Fichier (image ou vidéo)</label>
            <input type="file" name="fichier" id="fichier" class="form-control" accept="image/*,video/*" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.galeries.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
