@extends('admin.layout')

@section('title', 'Modifier la projection')

@section('content')
<style>
    [data-bs-theme="light"] {
        --projection-media-bg: #f8f9fa;
        --projection-media-border: #dee2e6;
        --projection-media-text: #1f2937;
        --projection-media-muted: #6b7280;
        --projection-media-item-bg: #ffffff;
        --projection-media-item-border: rgba(15, 23, 42, 0.08);
    }

    [data-bs-theme="dark"] {
        --projection-media-bg: #1b2435;
        --projection-media-border: rgba(148, 163, 184, 0.24);
        --projection-media-text: #e5edf8;
        --projection-media-muted: #a8b6ca;
        --projection-media-item-bg: #111827;
        --projection-media-item-border: rgba(148, 163, 184, 0.2);
    }

    .projection-media-panel {
        display: none;
        border: 1px solid var(--projection-media-border);
        border-radius: 0.375rem;
        padding: 1rem;
        background-color: var(--projection-media-bg);
        color: var(--projection-media-text);
    }

    .projection-media-panel .form-label,
    .projection-media-panel .form-check-label {
        color: var(--projection-media-text);
    }

    .projection-media-divider {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--projection-media-border);
    }

    .projection-media-note {
        color: var(--projection-media-muted) !important;
    }

    .projection-media-list .list-group-item {
        background: var(--projection-media-item-bg);
        color: var(--projection-media-text);
        border-color: var(--projection-media-item-border);
    }
</style>
<div class="container" style="max-width: 700px;">
    <h2 class="mb-4">Modifier la projection</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.projections.update', $projection) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="film_id" class="form-label fw-bold">Film projeté <span class="text-danger">*</span></label>
            <select name="film_id" id="film_id" class="form-select @error('film_id') is-invalid @enderror" required>
                <option value="">— Sélectionner un film —</option>
                @foreach($films as $film)
                    <option value="{{ $film->id }}" data-duree-secondes="{{ $film->dureeSecondesReelle() }}"
                        {{ old('film_id', $projection->film_id) == $film->id ? 'selected' : '' }}>
                        {{ $film->titre }} ({{ $film->annee_production }})
                    </option>
                @endforeach
            </select>
            @error('film_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <small id="film_duree_info" class="text-muted d-block mt-2"></small>
        </div>

        <!-- Section de sélection des médias (apparaît après sélection d'un film avec 2+ médias) -->
        <div id="media_selection_section" class="mb-4 projection-media-panel">
            <label class="form-label fw-bold mb-3">Mode de diffusion des vidéos</label>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="media_selection_mode" 
                        id="media_mode_all" value="all" 
                        {{ old('media_selection_mode', $projection->media_selection_mode ?? 'all') === 'all' ? 'checked' : '' }}>
                    <label class="form-check-label" for="media_mode_all">
                        Diffuser tous les médias dans l'ordre d'ajout
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="media_selection_mode" 
                        id="media_mode_specific" value="specific"
                        {{ old('media_selection_mode', $projection->media_selection_mode ?? 'all') === 'specific' ? 'checked' : '' }}>
                    <label class="form-check-label" for="media_mode_specific">
                        Sélectionner des médias spécifiques
                    </label>
                </div>
            </div>

            <!-- Checkboxes pour mode 'specific' -->
            <div id="media_checkboxes_container" class="projection-media-divider" style="display: none;">
                <label class="form-label fw-bold d-block mb-2">Choisir les vidéos à diffuser :</label>
                <div id="media_list" class="list-group projection-media-list"></div>
            </div>

            <small class="projection-media-note d-block mt-3" id="media_count_info"></small>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" id="date"
                    class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date', $projection->date->format('Y-m-d')) }}" required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="heure" class="form-label fw-bold">Heure <span class="text-danger">*</span></label>
                <input type="time" name="heure" id="heure"
                    class="form-control @error('heure') is-invalid @enderror"
                    value="{{ old('heure', \Carbon\Carbon::parse($projection->heure)->format('H:i')) }}" required>
                @error('heure')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label fw-bold">Lieu <span class="text-danger">*</span></label>
            <input type="text" name="lieu" id="lieu"
                class="form-control @error('lieu') is-invalid @enderror"
                value="{{ old('lieu', $projection->lieu) }}" required>
            @error('lieu')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label fw-bold">Notes <small class="text-muted">(optionnel)</small></label>
            <textarea name="notes" id="notes" rows="3"
                class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $projection->notes) }}</textarea>
            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                    name="publie" id="publie" value="1"
                    {{ old('publie', $projection->publie) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="publie">
                    Publier cette projection
                    <small class="text-muted d-block">Si activé, la projection sera visible sur le site public.</small>
                </label>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning">Enregistrer les modifications</button>
            <a href="{{ route('admin.projections.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const filmSelect = document.getElementById('film_id');
        const dureeInfo = document.getElementById('film_duree_info');
        const mediaSelectionSection = document.getElementById('media_selection_section');
        const mediaModeRadios = document.querySelectorAll('input[name="media_selection_mode"]');
        const mediaCheckboxesContainer = document.getElementById('media_checkboxes_container');
        const mediaList = document.getElementById('media_list');
        const mediaCountInfo = document.getElementById('media_count_info');

        // Get the current selection state if editing (from old() helper or model)
        const currentMode = document.querySelector('input[name="media_selection_mode"]:checked')?.value || 'all';
        const selectedMediaIds = {!! json_encode($projection->selected_media_ids ?? []) !!};

        function formatDuree(secondes) {
            const total = Number(secondes || 0);
            if (!total || total <= 0) return null;

            const h = Math.floor(total / 3600);
            const m = Math.floor((total % 3600) / 60);
            const s = total % 60;

            if (h > 0) {
                return s > 0 ? `${h}h${m}min${s}s` : `${h}h${m}`;
            }

            return s > 0 ? `${m}min${s}s` : `${m}min`;
        }

        function updateDureeInfo() {
            if (!filmSelect || !dureeInfo) return;
            const selected = filmSelect.options[filmSelect.selectedIndex];
            const dureeSecondes = selected ? selected.getAttribute('data-duree-secondes') : null;
            const label = formatDuree(dureeSecondes);

            if (label) {
                dureeInfo.textContent = `Durée du film sélectionné : ${label}`;
            } else {
                dureeInfo.textContent = 'Durée du film non renseignée.';
            }
        }

        // Fetch media list for the selected film
        async function loadMediaForFilm(filmId) {
            if (!filmId) {
                mediaSelectionSection.style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`/admin/api/films/${filmId}/medias`);
                const medias = await response.json();

                // Si moins de 2 médias, ne pas afficher la section
                if (medias.length < 2) {
                    mediaSelectionSection.style.display = 'none';
                    mediaCountInfo.textContent = '';
                    return;
                }

                // Afficher la section
                mediaSelectionSection.style.display = 'block';
                mediaCountInfo.textContent = `Ce film a ${medias.length} vidéos disponibles.`;

                // Remplir la liste des checkboxes
                mediaList.innerHTML = '';
                medias.forEach(media => {
                    const checkboxId = `media_${media.id}`;
                    const durationLabel = media.duree_secondes ? ` (${formatDuree(media.duree_secondes)})` : '';
                    const isSelected = selectedMediaIds.includes(media.id);
                    
                    const div = document.createElement('div');
                    div.className = 'list-group-item';
                    div.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input media-checkbox" type="checkbox" 
                                name="selected_media_ids[]" value="${media.id}" id="${checkboxId}"
                                ${isSelected ? 'checked' : ''}>
                            <label class="form-check-label" for="${checkboxId}">
                                ${media.titre}${durationLabel}
                            </label>
                        </div>
                    `;
                    mediaList.appendChild(div);
                });

                // Show/hide checkboxes based on current mode
                if (currentMode === 'specific') {
                    mediaCheckboxesContainer.style.display = 'block';
                }
            } catch (err) {
                console.error('Error loading media:', err);
                mediaSelectionSection.style.display = 'none';
            }
        }

        // Toggle checkboxes visibility based on mode selection
        mediaModeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'specific') {
                    mediaCheckboxesContainer.style.display = 'block';
                } else {
                    mediaCheckboxesContainer.style.display = 'none';
                }
            });
        });

        filmSelect?.addEventListener('change', function() {
            updateDureeInfo();
            // Reset the media mode when changing film
            document.getElementById('media_mode_all').checked = true;
            mediaCheckboxesContainer.style.display = 'none';
            loadMediaForFilm(this.value);
        });

        // Initialize on page load
        updateDureeInfo();
        // Load media for currently selected film if any
        if (filmSelect && filmSelect.value) {
            loadMediaForFilm(filmSelect.value);
        }
    })();
</script>
@endpush
