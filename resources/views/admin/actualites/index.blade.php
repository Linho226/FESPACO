@extends('admin.layout')

@section('title', 'Liste des actualités')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 mobile-page-header">
        <h2>Actualités</h2>
        <a href="{{ route('admin.actualites.create') }}" class="btn btn-primary">Nouvelle actualité</a>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.actualites.index') }}" class="d-flex gap-2 flex-wrap align-items-center mobile-filter-actions">
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    class="form-control"
                    style="max-width: 420px;"
                    placeholder="Rechercher par titre, contenu, auteur..."
                >
                <button type="submit" class="btn btn-primary">Rechercher</button>
                @if(!empty($search))
                    <a href="{{ route('admin.actualites.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
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
                        <td class="mobile-table-actions">
                            <div class="d-inline-flex gap-1 flex-wrap">
                                <a href="{{ route('admin.actualites.show', $actualite) }}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('admin.actualites.edit', $actualite) }}" class="btn btn-sm btn-warning">Modifier</a>
                                <form action="{{ route('admin.actualites.destroy', $actualite) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette actualité ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Aucune actualité trouvée.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $actualites->links() }}
    </div>
</div>
@endsection
