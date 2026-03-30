@extends('admin.layout')

@section('title', 'Modifier un acteur')

@section('content')
<div class="container" style="max-width: 760px;">
    <h2 class="mb-4">Modifier l'acteur</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.acteurs.update', $acteur) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="prenom" class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                    <input type="text" id="prenom" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                           value="{{ old('prenom', $acteur->prenom) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nom" class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                    <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom', $acteur->nom) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nationalite" class="form-label fw-bold">Nationalité</label>
                    <input type="text" id="nationalite" name="nationalite" class="form-control"
                           value="{{ old('nationalite', $acteur->nationalite) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label fw-bold">Type</label>
                    <select id="type" name="type" class="form-select">
                        <option value="">-- Choisir un type --</option>
                        <option value="Principal" {{ old('type', $acteur->type) == 'Principal' ? 'selected' : '' }}>Principal</option>
                        <option value="Second rôle" {{ old('type', $acteur->type) == 'Second rôle' ? 'selected' : '' }}>Second rôle</option>
                        <option value="Caméo" {{ old('type', $acteur->type) == 'Caméo' ? 'selected' : '' }}>Caméo</option>
                        <option value="Voix" {{ old('type', $acteur->type) == 'Voix' ? 'selected' : '' }}>Voix</option>
                        <option value="Figurant" {{ old('type', $acteur->type) == 'Figurant' ? 'selected' : '' }}>Figurant</option>
                        <option value="Autre" {{ old('type', $acteur->type) == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label fw-bold">Photo</label>
                @if($acteur->photo)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo actuelle"
                             class="img-thumbnail" style="width:120px;height:120px;object-fit:cover;">
                    </div>
                @endif
                <input type="file" id="photo" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                <small class="text-muted">Laisser vide pour conserver la photo actuelle.</small>
            </div>

            <div class="mb-3">
                <label for="biographie" class="form-label fw-bold">Biographie</label>
                <textarea id="biographie" name="biographie" rows="5" class="form-control">{{ old('biographie', $acteur->biographie) }}</textarea>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button type="submit" class="btn btn-warning">Enregistrer</button>
            <a href="{{ route('admin.acteurs.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection