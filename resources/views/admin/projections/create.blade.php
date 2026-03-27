@extends('admin.layout')

@section('title', 'Ajouter une projection')

@section('content')
<div class="container" style="max-width: 700px;">
    <h2 class="mb-4">Ajouter une projection</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.projections.store') }}">
        @csrf

        <div class="mb-3">
            <label for="film_id" class="form-label fw-bold">Film projeté <span class="text-danger">*</span></label>
            <select name="film_id" id="film_id" class="form-select @error('film_id') is-invalid @enderror" required>
                <option value="">— Sélectionner un film —</option>
                @foreach($films as $film)
                    <option value="{{ $film->id }}" data-duree="{{ $film->duree ?? '' }}" {{ old('film_id') == $film->id ? 'selected' : '' }}>
                        {{ $film->titre }} ({{ $film->annee_production }})
                    </option>
                @endforeach
            </select>
            @error('film_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <small id="film_duree_info" class="text-muted d-block mt-2"></small>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" id="date"
                    class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date') }}" required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="heure" class="form-label fw-bold">Heure <span class="text-danger">*</span></label>
                <input type="time" name="heure" id="heure"
                    class="form-control @error('heure') is-invalid @enderror"
                    value="{{ old('heure') }}" required>
                @error('heure')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="salle" class="form-label fw-bold">Salle <span class="text-danger">*</span></label>
            <input type="text" name="salle" id="salle"
                class="form-control @error('salle') is-invalid @enderror"
                value="{{ old('salle') }}" placeholder="Ex: Salle 1, Cinéma Burkina..." required>
            @error('salle')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label fw-bold">Lieu <span class="text-danger">*</span></label>
            <input type="text" name="lieu" id="lieu"
                class="form-control @error('lieu') is-invalid @enderror"
                value="{{ old('lieu') }}" placeholder="Ex: Ouagadougou, Bobo-Dioulasso..." required>
            @error('lieu')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label fw-bold">Notes <small class="text-muted">(optionnel)</small></label>
            <textarea name="notes" id="notes" rows="3"
                class="form-control @error('notes') is-invalid @enderror"
                placeholder="Informations supplémentaires...">{{ old('notes') }}</textarea>
            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                    name="publie" id="publie" value="1"
                    {{ old('publie') ? 'checked' : '' }}>
                <label class="form-check-label fw-bold" for="publie">
                    Publier cette projection
                    <small class="text-muted d-block">Si activé, la projection sera visible sur le site public.</small>
                </label>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Enregistrer</button>
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

        function updateDureeInfo() {
            if (!filmSelect || !dureeInfo) return;
            const selected = filmSelect.options[filmSelect.selectedIndex];
            const duree = selected ? selected.getAttribute('data-duree') : null;

            if (duree) {
                dureeInfo.textContent = `Durée du film sélectionné : ${duree} min`;
            } else {
                dureeInfo.textContent = 'Durée du film non renseignée.';
            }
        }

        filmSelect?.addEventListener('change', updateDureeInfo);
        updateDureeInfo();
    })();
</script>
@endpush
