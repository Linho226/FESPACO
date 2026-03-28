@extends('admin.layout')

@section('title', 'Réalisateurs')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 class="mb-0">Liste des réalisateurs</h2>
        <a href="{{ route('admin.realisateurs.create') }}" class="btn btn-success">+ Ajouter un réalisateur</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.realisateurs.index') }}" class="row g-2 mb-4">
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
                @forelse($realisateurs as $realisateur)
                    <tr>
                        <td>
                            @if($realisateur->photo)
                                <img src="{{ asset('storage/'.$realisateur->photo) }}"
                                     alt="Photo de {{ $realisateur->prenom }}"
                                     class="img-thumbnail"
                                     style="width:60px;height:60px;object-fit:cover;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><strong>{{ $realisateur->prenom }} {{ $realisateur->nom }}</strong></td>
                        <td>{{ $realisateur->nationalite ?: 'Non renseignée' }}</td>
                        <td>{{ $realisateur->type ?: 'Non renseigné' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                <a href="{{ route('admin.realisateurs.show', $realisateur) }}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('admin.realisateurs.edit', $realisateur) }}" class="btn btn-sm btn-warning">Modifier</a>
                                <form action="{{ route('admin.realisateurs.destroy', $realisateur) }}" method="POST" style="display:inline-block; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Supprimer ce réalisateur ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucun réalisateur enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $realisateurs->links() }}
</div>
@endsection