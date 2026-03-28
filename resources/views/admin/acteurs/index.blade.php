@extends('admin.layout')

@section('title', 'Acteurs')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 class="mb-0">Liste des acteurs</h2>
        <a href="{{ route('admin.acteurs.create') }}" class="btn btn-success">+ Ajouter un acteur</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.acteurs.index') }}" class="row g-2 mb-4">
        <div class="col-md-10">
            <input type="text" name="search" class="form-control"
                   value="{{ request('search') }}"
                   placeholder="Rechercher par nom, prénom, nationalité ou type...">
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width:80px">Photo</th>
                    <th>Nom complet</th>
                    <th>Nationalité</th>
                    <th>Type</th>
                    <th style="width:260px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($acteurs as $acteur)
                    <tr>
                        <td>
                            @if($acteur->photo)
                                <img src="{{ asset('storage/'.$acteur->photo) }}"
                                     alt="Photo de {{ $acteur->prenom }}"
                                     class="img-thumbnail"
                                     style="width:60px;height:60px;object-fit:cover;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><strong>{{ $acteur->prenom }} {{ $acteur->nom }}</strong></td>
                        <td>{{ $acteur->nationalite ?: 'Non renseignée' }}</td>
                        <td>{{ $acteur->type ?: 'Non renseigné' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                <a href="{{ route('admin.acteurs.show', $acteur) }}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('admin.acteurs.edit', $acteur) }}" class="btn btn-sm btn-warning">Modifier</a>
                                <form action="{{ route('admin.acteurs.destroy', $acteur) }}" method="POST" style="display:inline-block; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Supprimer cet acteur ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucun acteur enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $acteurs->links() }}
</div>
@endsection