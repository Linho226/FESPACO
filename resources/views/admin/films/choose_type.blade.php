@extends('admin.layout')

@section('title', 'Choisir le type de film')

@section('content')
<div class="container px-0 films-admin">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1">Créer un nouveau contenu</h2>
            <p class="text-muted mb-0">Choisissez le format que vous souhaitez ajouter au catalogue FESPACO.</p>
        </div>
        <a href="{{ route('admin.films.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-body p-4 p-md-5">
            <h5 class="mb-3">Quel type de film souhaitez-vous ajouter ?</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded-3 p-4 h-100 bg-light">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fs-4">🎬</span>
                            <h6 class="mb-0">Film complet</h6>
                        </div>
                        <p class="text-muted mb-3">Pour un long métrage unique avec une vidéo principale ou un lien externe.</p>
                        <ul class="small text-muted ps-3 mb-3">
                            <li>1 titre principal</li>
                            <li>1 vidéo uploadée (optionnelle)</li>
                            <li>1 lien externe (optionnel)</li>
                        </ul>
                        <a href="{{ route('admin.films.create', ['type' => 'film']) }}" class="btn btn-primary w-100">Choisir Film complet</a>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-4 h-100 bg-light">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fs-4">📺</span>
                            <h6 class="mb-0">Série</h6>
                        </div>
                        <p class="text-muted mb-3">Pour une série composée de plusieurs épisodes, avec plusieurs liens/fichiers vidéo.</p>
                        <ul class="small text-muted ps-3 mb-3">
                            <li>Liens vidéos multiples</li>
                            <li>Fichiers vidéo multiples</li>
                            <li>Gestion flexible des épisodes</li>
                        </ul>
                        <a href="{{ route('admin.films.create', ['type' => 'serie']) }}" class="btn btn-secondary w-100">Choisir Série</a>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.films.index') }}" class="btn btn-link text-decoration-none">Annuler</a>
            </div>
        </div>
    </div>
</div>
@endsection
