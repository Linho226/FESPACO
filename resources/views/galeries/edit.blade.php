@extends('admin.layout')
@section('title', 'Modifier le média')

@section('content')
<div class="container px-0 films-admin">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1">Modifier le média</h2>
            <p class="text-muted mb-0">{{ $galerie->titre }}</p>
        </div>
        <a href="{{ route('admin.galeries.index') }}" class="btn btn-outline-secondary">Retour à la galerie</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Veuillez corriger les erreurs.</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius:14px;">
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('admin.galeries.update', ['galerie' => $galerie->id]) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')

                {{-- Film associé --}}
                <div class="col-12">
                    <label for="film_id" class="form-label">Film associé <span class="text-muted fw-normal">(optionnel)</span></label>
                    <select name="film_id" id="film_id" class="form-select @error('film_id') is-invalid @enderror">
                        <option value="">— Aucun (festival général) —</option>
                        @foreach($films as $film)
                            <option value="{{ $film->id }}"
                                {{ old('film_id', $galerie->film_id) == $film->id ? 'selected' : '' }}>
                                {{ $film->titre }} ({{ $film->annee_production }})
                            </option>
                        @endforeach
                    </select>
                    @error('film_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Titre --}}
                <div class="col-12 col-md-8">
                    <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror"
                           value="{{ old('titre', $galerie->titre) }}" required>
                    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Date --}}
                <div class="col-12 col-md-4">
                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', $galerie->date) }}" required>
                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Type de média --}}
                <div class="col-12 col-md-4">
                    <label for="type_media" class="form-label">Type de média <span class="text-danger">*</span></label>
                    <select name="type_media" id="type_media" class="form-select @error('type_media') is-invalid @enderror" required>
                        <option value="image" {{ old('type_media', $galerie->type_media) === 'image' ? 'selected' : '' }}>🖼 Image</option>
                        <option value="video" {{ old('type_media', $galerie->type_media) === 'video' ? 'selected' : '' }}>🎬 Vidéo</option>
                    </select>
                    @error('type_media') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Fichier actuel --}}
                @if($galerie->fichier)
                <div class="col-12 col-md-8">
                    <label class="form-label">Fichier actuel</label>
                    <div class="border rounded p-2 bg-light text-center">
                        @if($galerie->type_media === 'image')
                            <img src="{{ asset('storage/'.$galerie->fichier) }}" alt="{{ $galerie->titre }}"
                                 style="max-height:160px; border-radius:6px;">
                        @else
                            <video src="{{ asset('storage/'.$galerie->fichier) }}" controls
                                   style="max-height:160px; max-width:100%; border-radius:6px;"></video>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Nouveau fichier --}}
                <div class="col-12 col-md-8">
                    <label for="fichier" class="form-label">
                        {{ $galerie->fichier ? 'Remplacer le fichier' : 'Uploader un fichier' }}
                    </label>
                    <input type="file" name="fichier" id="fichier" class="form-control @error('fichier') is-invalid @enderror"
                           accept="image/*,video/*">
                    <div class="form-text">Laissez vide pour conserver le fichier existant.</div>
                    @error('fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Aperçu nouveau fichier --}}
                <div class="col-12">
                    <div id="preview-box" class="border rounded p-2 bg-light text-center" style="min-height:60px; display:none;">
                        <img id="img-preview" src="" alt="" style="max-height:200px; display:none; border-radius:6px;">
                        <video id="vid-preview" controls style="max-height:200px; max-width:100%; display:none; border-radius:6px;"></video>
                    </div>
                </div>

                {{-- Lien externe --}}
                <div class="col-12">
                    <label for="lien" class="form-label">Ou lien externe <span class="text-muted fw-normal">(YouTube, Vimeo…)</span></label>
                    <input type="url" name="lien" id="lien" class="form-control @error('lien') is-invalid @enderror"
                           value="{{ old('lien', $galerie->lien) }}" placeholder="https://youtube.com/watch?v=...">
                    <div class="form-text">Renseignez soit un fichier, soit un lien externe.</div>
                    @error('lien') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                              rows="3">{{ old('description', $galerie->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.galeries.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning text-white">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('fichier').addEventListener('change', function () {
    const file = this.files[0];
    const box = document.getElementById('preview-box');
    const img = document.getElementById('img-preview');
    const vid = document.getElementById('vid-preview');
    if (!file) { box.style.display = 'none'; return; }
    box.style.display = 'block';
    if (file.type.startsWith('image/')) {
        img.style.display = 'inline-block'; vid.style.display = 'none';
        const r = new FileReader();
        r.onload = e => img.src = e.target.result;
        r.readAsDataURL(file);
    } else {
        vid.style.display = 'inline-block'; img.style.display = 'none';
        vid.src = URL.createObjectURL(file);
    }
});
</script>
@endpush
