@extends('admin.layout')

@section('title', 'Liste des actualités')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Actualités</h2>
        <a href="{{ route('admin.actualites.create') }}" class="btn btn-primary">Nouvelle actualité</a>
    </div>
    <table class="table table-bordered table-hover bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Date de publication</th>
                <th>Auteur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($actualites as $actualite)
            <tr>
                <td>{{ $actualite->id }}</td>
                <td>{{ $actualite->titre }}</td>
                <td>{{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }}</td>
                <td>{{ $actualite->auteur->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.actualites.show', $actualite) }}" class="btn btn-sm btn-info">Voir</a>
                    <a href="{{ route('admin.actualites.edit', $actualite) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.actualites.destroy', $actualite) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette actualité ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center">Aucune actualité trouvée.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
