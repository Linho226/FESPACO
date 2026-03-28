@extends('admin.layout')

@section('title', 'Ajouter un réalisateur')

@section('content')
<div class="container" style="max-width: 760px;">
    <h2 class="mb-4">Ajouter un réalisateur</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.realisateurs.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="prenom" class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                    <input type="text" id="prenom" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                           value="{{ old('prenom') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nom" class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                    <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nationalite" class="form-label fw-bold">Nationalité</label>
                    <input type="text" id="nationalite" name="nationalite" class="form-control"
                           value="{{ old('nationalite') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label fw-bold">Type</label>
                    <input type="text" id="type" name="type" class="form-control"
                           value="{{ old('type') }}" placeholder="Ex: Long métrage, documentaire...">
                </div>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label fw-bold">Photo</label>
                <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                <small class="text-muted">Formats: jpeg, png, jpg, webp — max 3 Mo</small>
            </div>

            <div class="mb-3">
                <label for="biographie" class="form-label fw-bold">Biographie</label>
                <textarea id="biographie" name="biographie" rows="5" class="form-control">{{ old('biographie') }}</textarea>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('admin.realisateurs.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection