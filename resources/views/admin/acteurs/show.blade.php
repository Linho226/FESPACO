@extends('admin.layout')

@section('title', 'Détail acteur')

@section('content')
<div class="container" style="max-width: 900px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    @if($acteur->photo)
                        <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo de {{ $acteur->prenom }}"
                             class="img-thumbnail" style="width:230px;height:230px;object-fit:cover;">
                    @else
                        <div class="border rounded d-flex align-items-center justify-content-center text-muted"
                             style="width:230px;height:230px;margin:auto;">
                            Aucune photo
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h3>{{ $acteur->prenom }} {{ $acteur->nom }}</h3>
                    <hr>
                    <p><strong>Nationalité :</strong> {{ $acteur->nationalite ?: 'Non renseignée' }}</p>
                    <p><strong>Type :</strong> {{ $acteur->type ?: 'Non renseigné' }}</p>
                    <p class="mb-0"><strong>Biographie :</strong> {{ $acteur->biographie ?: 'Non renseignée' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex flex-wrap gap-2">
            <a href="{{ route('admin.acteurs.edit', $acteur) }}" class="btn btn-warning">Modifier</a>
            <form action="{{ route('admin.acteurs.destroy', $acteur) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cet acteur ?')">Supprimer</button>
            </form>
            <a href="{{ route('admin.acteurs.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection