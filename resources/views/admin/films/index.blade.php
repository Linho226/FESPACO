@extends('admin.layout')

@section('title', 'Liste des films')

@section('content')
<div class="container">
    <h2 class="mb-4">Liste des films</h2>
    <form method="GET" action="" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un film..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>
    <a href="{{ route('admin.films.create') }}" class="btn btn-success mb-3">Ajouter un film</a>
    <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Annuler</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Année</th>
                <th>Pays</th>
                <th>Durée</th>
                <th>Catégorie</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($films as $film)
            <tr>
                <td>{{ $film->titre }}</td>
                <td>{{ $film->annee_production }}</td>
                <td>{{ $film->pays }}</td>
                <td>{{ $film->duree }} min</td>
                <td>{{ $film->categorie }}</td>
                <td>{{ $film->type == 'serie' ? 'Série' : 'Film' }}</td>
                <td>
                    <a href="{{ route('admin.films.show', $film) }}" class="btn btn-sm btn-info">Voir</a>
                    <a href="{{ route('admin.films.edit', $film) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.films.destroy', $film) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce film ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Aucun film trouvé.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $films->links() }}
</div>
@endsection
