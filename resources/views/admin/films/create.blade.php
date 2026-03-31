@extends('admin.layout')

@section('title', 'Ajouter un film')

@section('content')
<div class="container px-0 films-admin">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1">Ajouter un film</h2>
            <p class="text-muted mb-0">Renseignez les informations techniques du film. Les médias sont gérés via le module <strong>Galerie</strong>.</p>
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
            <form method="POST" action="{{ route('admin.films.store') }}" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-12 col-md-8">
                    <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" required>
                    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="annee_production" class="form-label">Année de production <span class="text-danger">*</span></label>
                    <input type="number" min="1900" max="{{ date('Y') + 1 }}" class="form-control @error('annee_production') is-invalid @enderror" id="annee_production" name="annee_production" value="{{ old('annee_production') }}" required>
                    @error('annee_production') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="pays" class="form-label">Pays <span class="text-danger">*</span></label>
                    <select class="form-select @error('pays') is-invalid @enderror" id="pays" name="pays" required>
                        <option value="">-- Choisir un pays --</option>
                        <option value="Algérie" {{ old('pays') == 'Algérie' ? 'selected' : '' }}>Algérie</option>
                        <option value="Angola" {{ old('pays') == 'Angola' ? 'selected' : '' }}>Angola</option>
                        <option value="Bénin" {{ old('pays') == 'Bénin' ? 'selected' : '' }}>Bénin</option>
                        <option value="Botswana" {{ old('pays') == 'Botswana' ? 'selected' : '' }}>Botswana</option>
                        <option value="Burkina Faso" {{ old('pays') == 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
                        <option value="Burundi" {{ old('pays') == 'Burundi' ? 'selected' : '' }}>Burundi</option>
                        <option value="Cameroun" {{ old('pays') == 'Cameroun' ? 'selected' : '' }}>Cameroun</option>
                        <option value="Cap-Vert" {{ old('pays') == 'Cap-Vert' ? 'selected' : '' }}>Cap-Vert</option>
                        <option value="Comores" {{ old('pays') == 'Comores' ? 'selected' : '' }}>Comores</option>
                        <option value="Congo" {{ old('pays') == 'Congo' ? 'selected' : '' }}>Congo</option>
                        <option value="Congo (RDC)" {{ old('pays') == 'Congo (RDC)' ? 'selected' : '' }}>Congo (RDC)</option>
                        <option value="Côte d'Ivoire" {{ old('pays') == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                        <option value="Djibouti" {{ old('pays') == 'Djibouti' ? 'selected' : '' }}>Djibouti</option>
                        <option value="Égypte" {{ old('pays') == 'Égypte' ? 'selected' : '' }}>Égypte</option>
                        <option value="Érythrée" {{ old('pays') == 'Érythrée' ? 'selected' : '' }}>Érythrée</option>
                        <option value="Eswatini" {{ old('pays') == 'Eswatini' ? 'selected' : '' }}>Eswatini</option>
                        <option value="Éthiopie" {{ old('pays') == 'Éthiopie' ? 'selected' : '' }}>Éthiopie</option>
                        <option value="Gabon" {{ old('pays') == 'Gabon' ? 'selected' : '' }}>Gabon</option>
                        <option value="Gambie" {{ old('pays') == 'Gambie' ? 'selected' : '' }}>Gambie</option>
                        <option value="Ghana" {{ old('pays') == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                        <option value="Guinée" {{ old('pays') == 'Guinée' ? 'selected' : '' }}>Guinée</option>
                        <option value="Guinée-Bissau" {{ old('pays') == 'Guinée-Bissau' ? 'selected' : '' }}>Guinée-Bissau</option>
                        <option value="Guinée équatoriale" {{ old('pays') == 'Guinée équatoriale' ? 'selected' : '' }}>Guinée équatoriale</option>
                        <option value="Kenya" {{ old('pays') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                        <option value="Lesotho" {{ old('pays') == 'Lesotho' ? 'selected' : '' }}>Lesotho</option>
                        <option value="Libéria" {{ old('pays') == 'Libéria' ? 'selected' : '' }}>Libéria</option>
                        <option value="Libye" {{ old('pays') == 'Libye' ? 'selected' : '' }}>Libye</option>
                        <option value="Madagascar" {{ old('pays') == 'Madagascar' ? 'selected' : '' }}>Madagascar</option>
                        <option value="Malawi" {{ old('pays') == 'Malawi' ? 'selected' : '' }}>Malawi</option>
                        <option value="Mali" {{ old('pays') == 'Mali' ? 'selected' : '' }}>Mali</option>
                        <option value="Maroc" {{ old('pays') == 'Maroc' ? 'selected' : '' }}>Maroc</option>
                        <option value="Maurice" {{ old('pays') == 'Maurice' ? 'selected' : '' }}>Maurice</option>
                        <option value="Mauritanie" {{ old('pays') == 'Mauritanie' ? 'selected' : '' }}>Mauritanie</option>
                        <option value="Mozambique" {{ old('pays') == 'Mozambique' ? 'selected' : '' }}>Mozambique</option>
                        <option value="Namibie" {{ old('pays') == 'Namibie' ? 'selected' : '' }}>Namibie</option>
                        <option value="Niger" {{ old('pays') == 'Niger' ? 'selected' : '' }}>Niger</option>
                        <option value="Nigéria" {{ old('pays') == 'Nigéria' ? 'selected' : '' }}>Nigéria</option>
                        <option value="Ouganda" {{ old('pays') == 'Ouganda' ? 'selected' : '' }}>Ouganda</option>
                        <option value="Rwanda" {{ old('pays') == 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                        <option value="Sao Tomé-et-Principe" {{ old('pays') == 'Sao Tomé-et-Principe' ? 'selected' : '' }}>Sao Tomé-et-Principe</option>
                        <option value="Sénégal" {{ old('pays') == 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                        <option value="Seychelles" {{ old('pays') == 'Seychelles' ? 'selected' : '' }}>Seychelles</option>
                        <option value="Sierra Leone" {{ old('pays') == 'Sierra Leone' ? 'selected' : '' }}>Sierra Leone</option>
                        <option value="Somalie" {{ old('pays') == 'Somalie' ? 'selected' : '' }}>Somalie</option>
                        <option value="Soudan" {{ old('pays') == 'Soudan' ? 'selected' : '' }}>Soudan</option>
                        <option value="Soudan du Sud" {{ old('pays') == 'Soudan du Sud' ? 'selected' : '' }}>Soudan du Sud</option>
                        <option value="Tanzanie" {{ old('pays') == 'Tanzanie' ? 'selected' : '' }}>Tanzanie</option>
                        <option value="Tchad" {{ old('pays') == 'Tchad' ? 'selected' : '' }}>Tchad</option>
                        <option value="Togo" {{ old('pays') == 'Togo' ? 'selected' : '' }}>Togo</option>
                        <option value="Tunisie" {{ old('pays') == 'Tunisie' ? 'selected' : '' }}>Tunisie</option>
                        <option value="Zambie" {{ old('pays') == 'Zambie' ? 'selected' : '' }}>Zambie</option>
                        <option value="Zimbabwe" {{ old('pays') == 'Zimbabwe' ? 'selected' : '' }}>Zimbabwe</option>
                    </select>
                    @error('pays') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>



                <div class="col-12 col-md-4">
                    <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                    <select class="form-select @error('categorie') is-invalid @enderror" id="categorie" name="categorie" required>
                        <option value="">-- Choisir une catégorie --</option>
                        <option value="Animation" {{ old('categorie') == 'Animation' ? 'selected' : '' }}>Animation</option>
                        <option value="Drame" {{ old('categorie') == 'Drame' ? 'selected' : '' }}>Drame</option>
                        <option value="Comédie" {{ old('categorie') == 'Comédie' ? 'selected' : '' }}>Comédie</option>
                        <option value="Documentaire" {{ old('categorie') == 'Documentaire' ? 'selected' : '' }}>Documentaire</option>
                        <option value="Action" {{ old('categorie') == 'Action' ? 'selected' : '' }}>Action</option>
                        <option value="Thriller" {{ old('categorie') == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                        <option value="Aventure" {{ old('categorie') == 'Aventure' ? 'selected' : '' }}>Aventure</option>
                        <option value="Science-fiction" {{ old('categorie') == 'Science-fiction' ? 'selected' : '' }}>Science-fiction</option>
                        <option value="Fantastique" {{ old('categorie') == 'Fantastique' ? 'selected' : '' }}>Fantastique</option>
                        <option value="Horreur" {{ old('categorie') == 'Horreur' ? 'selected' : '' }}>Horreur</option>
                        <option value="Romance" {{ old('categorie') == 'Romance' ? 'selected' : '' }}>Romance</option>
                        <option value="Autre" {{ old('categorie') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('categorie') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="realisateur" class="form-label">Réalisateur <span class="text-danger">*</span></label>
                    <select class="form-select @error('realisateur') is-invalid @enderror" id="realisateur" name="realisateur" required>
                        <option value="">-- Choisir un réalisateur --</option>
                        @foreach($realisateurs as $r)
                            <option value="{{ $r->prenom }} {{ $r->nom }}"
                                {{ old('realisateur') === $r->prenom.' '.$r->nom ? 'selected' : '' }}>
                                {{ $r->prenom }} {{ $r->nom }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Tapez pour filtrer dans la liste.</div>
                    @error('realisateur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Acteurs <span class="text-danger">*</span></label>
                    <input type="hidden" name="acteurs" id="acteurs-value" value="{{ old('acteurs') }}">
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
                    <div class="form-text">JPEG, PNG, WebP — max 4 Mo.</div>
                    @error('affiche') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 col-md-4 d-flex align-items-end">
                    <div class="w-100 border rounded p-2 bg-light text-center" style="min-height:110px;">
                        <img id="affiche-preview" src="" alt="" style="max-height:140px; width:auto; display:none; border-radius:6px;">
                        <p id="affiche-placeholder" class="text-muted mb-0 small mt-2">Aperçu de l'affiche</p>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('admin.films.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer le film</button>
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
            if (!file) { affichePreview.style.display = 'none'; affichePlaceholder.style.display = 'block'; return; }
            const reader = new FileReader();
            reader.onload = e => { affichePreview.src = e.target.result; affichePreview.style.display = 'inline-block'; affichePlaceholder.style.display = 'none'; };
            reader.readAsDataURL(file);
        });
    }

    // --- Réalisateur : filtre natif avec datalist ---
    const realisateurSelect = document.getElementById('realisateur');
    if (realisateurSelect) {
        // Convertir le select en input+datalist pour la recherche
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
        input.value = realisateurSelect.value;
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

    // Validation : forcer acteurs non vide
    document.querySelector('form').addEventListener('submit', function() {
        if (!hiddenInput.value.trim()) {
            searchInput.classList.add('is-invalid');
        }
    });

    renderTags();
})();
</script>
@endpush
