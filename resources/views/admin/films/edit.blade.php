@extends('admin.layout')

@section('title', 'Modifier le film')

@section('content')
<div class="container px-0 films-admin">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1">Modifier : {{ $film->titre }}</h2>
            <p class="text-muted mb-0">Mettez à jour les informations techniques. Les médias sont gérés via le module <strong>Galerie</strong>.</p>
        </div>
        <a href="{{ route('admin.films.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Veuillez corriger les erreurs du formulaire.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('admin.films.update', $film) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-12 col-md-8">
                    <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $film->titre) }}" required>
                    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="annee_production" class="form-label">Année de production <span class="text-danger">*</span></label>
                    <input type="number" min="1900" max="{{ date('Y') + 1 }}" class="form-control @error('annee_production') is-invalid @enderror" id="annee_production" name="annee_production" value="{{ old('annee_production', $film->annee_production) }}" required>
                    @error('annee_production') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $film->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="pays" class="form-label">Pays <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('pays') is-invalid @enderror" id="pays" name="pays" value="{{ old('pays', $film->pays) }}" required>
                    @error('pays') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-4">
                    @php
                        $mediaVideo = $film->galeries()->where('type_media', 'video')->whereNotNull('duree_secondes')->orderByDesc('created_at')->first();
                        $dureeMedia = $mediaVideo?->duree_secondes ?? 0;
                    @endphp
                    <label class="form-label">Durée</label>
                    @if($dureeMedia > 0)
                        @php
                            $h = intdiv($dureeMedia, 3600);
                            $m = intdiv($dureeMedia % 3600, 60);
                            $s = $dureeMedia % 60;
                        @endphp
                        <div class="form-control-plaintext text-success fw-semibold">
                            {{ $h > 0 ? $h.'h ' : '' }}{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}min {{ str_pad($s, 2, '0', STR_PAD_LEFT) }}s
                        </div>
                        <small class="text-muted">Calculée automatiquement depuis le média vidéo.</small>
                    @else
                        <div class="form-control-plaintext text-muted fst-italic">Non renseignée — sera définie lors de l'ajout d'une vidéo.</div>
                    @endif
                </div>

                <div class="col-12 col-md-4">
                    <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('categorie') is-invalid @enderror" id="categorie" name="categorie" value="{{ old('categorie', $film->categorie) }}" required>
                    @error('categorie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="realisateur" class="form-label">Réalisateur <span class="text-danger">*</span></label>
                    <select class="form-select @error('realisateur') is-invalid @enderror" id="realisateur" name="realisateur" required>
                        <option value="">-- Choisir un réalisateur --</option>
                        @foreach($realisateurs as $r)
                            @php $fullName = $r->prenom.' '.$r->nom; @endphp
                            <option value="{{ $fullName }}"
                                {{ old('realisateur', $film->realisateur) === $fullName ? 'selected' : '' }}>
                                {{ $fullName }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Tapez pour filtrer dans la liste.</div>
                    @error('realisateur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Acteurs <span class="text-danger">*</span></label>
                    <input type="hidden" name="acteurs" id="acteurs-value" value="{{ old('acteurs', $film->acteurs) }}">
                    <div id="acteurs-tags" class="d-flex flex-wrap gap-1 mb-2 p-2 border rounded bg-white" style="min-height:42px; cursor:text;"></div>
                    <div class="input-group">
                        <input type="text" id="acteurs-search" class="form-control @error('acteurs') is-invalid @enderror" placeholder="Rechercher un acteur...">
                    </div>
                    <div id="acteurs-dropdown" class="list-group mt-1" style="display:none; max-height:200px; overflow-y:auto; position:relative; z-index:100;"></div>
                    <div class="form-text">Sélectionnez plusieurs acteurs.</div>
                    @error('acteurs') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <hr class="my-2">
                </div>

                <div class="col-12 col-md-8">
                    <label for="affiche" class="form-label">Photo / Affiche du film</label>
                    <input type="file" class="form-control @error('affiche') is-invalid @enderror" id="affiche" name="affiche" accept="image/*">
                    <div class="form-text">JPEG, PNG, WebP — max 4 Mo. Laissez vide pour conserver l'affiche actuelle.</div>
                    @error('affiche') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-4 d-flex align-items-end">
                    <div class="w-100 border rounded p-2 bg-light text-center" style="min-height:110px;">
                        @if($film->affiche)
                            <img id="affiche-preview" src="{{ asset('storage/'.$film->affiche) }}" alt="Affiche actuelle" style="max-height:140px; width:auto; border-radius:6px;">
                            <p id="affiche-placeholder" class="text-muted mb-0 small mt-1" style="display:none;">Aperçu</p>
                        @else
                            <img id="affiche-preview" src="" alt="" style="max-height:140px; width:auto; display:none; border-radius:6px;">
                            <p id="affiche-placeholder" class="text-muted mb-0 small mt-2">Aperçu de l'affiche</p>
                        @endif
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.films.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // --- Prévisualisation affiche ---
    const afficheInput = document.getElementById('affiche');
    const affichePreview = document.getElementById('affiche-preview');
    const affichePlaceholder = document.getElementById('affiche-placeholder');
    if (afficheInput) {
        afficheInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => { affichePreview.src = e.target.result; affichePreview.style.display = 'inline-block'; if (affichePlaceholder) affichePlaceholder.style.display = 'none'; };
            reader.readAsDataURL(file);
        });
    }

    // --- Réalisateur : datalist avec recherche ---
    const realisateurSelect = document.getElementById('realisateur');
    if (realisateurSelect) {
        const currentValue = realisateurSelect.value;
        const options = Array.from(realisateurSelect.options).slice(1);
        const datalist = document.createElement('datalist');
        datalist.id = 'realisateurs-list';
        options.forEach(o => {
            const opt = document.createElement('option');
            opt.value = o.value;
            datalist.appendChild(opt);
        });
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'realisateur';
        input.id = 'realisateur';
        input.setAttribute('list', 'realisateurs-list');
        input.className = realisateurSelect.className;
        input.placeholder = 'Rechercher un réalisateur...';
        input.required = true;
        input.value = currentValue;
        realisateurSelect.parentNode.insertBefore(input, realisateurSelect);
        realisateurSelect.parentNode.insertBefore(datalist, realisateurSelect);
        realisateurSelect.remove();
    }

    // --- Acteurs : tags multi-sélection ---
    const acteursData = @json($acteurs->map(fn($a) => ['id' => $a->id, 'nom' => $a->prenom.' '.$a->nom]));
    const tagsContainer = document.getElementById('acteurs-tags');
    const hiddenInput    = document.getElementById('acteurs-value');
    const searchInput    = document.getElementById('acteurs-search');
    const dropdown       = document.getElementById('acteurs-dropdown');

    let selected = hiddenInput.value
        ? hiddenInput.value.split(',').map(s => s.trim()).filter(Boolean)
        : [];

    function renderTags() {
        tagsContainer.innerHTML = '';
        selected.forEach(name => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary d-flex align-items-center gap-1';
            badge.style.fontSize = '0.85rem';
            badge.innerHTML = name + ' <button type="button" class="btn-close btn-close-white" style="font-size:0.6rem;" aria-label="Retirer"></button>';
            badge.querySelector('button').addEventListener('click', () => {
                selected = selected.filter(s => s !== name);
                renderTags();
                syncHidden();
            });
            tagsContainer.appendChild(badge);
        });
    }

    function syncHidden() {
        hiddenInput.value = selected.join(', ');
    }

    function renderDropdown(query) {
        const q = query.toLowerCase();
        const filtered = acteursData.filter(a =>
            a.nom.toLowerCase().includes(q) && !selected.includes(a.nom)
        );
        dropdown.innerHTML = '';
        if (!filtered.length) { dropdown.style.display = 'none'; return; }
        filtered.forEach(a => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'list-group-item list-group-item-action';
            item.textContent = a.nom;
            item.addEventListener('click', () => {
                selected.push(a.nom);
                renderTags();
                syncHidden();
                searchInput.value = '';
                dropdown.style.display = 'none';
                searchInput.focus();
            });
            dropdown.appendChild(item);
        });
        dropdown.style.display = 'block';
    }

    searchInput.addEventListener('input', () => renderDropdown(searchInput.value));
    searchInput.addEventListener('focus', () => { if (searchInput.value) renderDropdown(searchInput.value); });
    document.addEventListener('click', e => {
        if (!dropdown.contains(e.target) && e.target !== searchInput) {
            dropdown.style.display = 'none';
        }
    });

    renderTags();
})();
</script>
@endpush
