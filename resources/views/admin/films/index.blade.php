@extends('admin.layout')

@section('title', 'Liste des films')

@section('content')
<div class="container px-0 films-admin">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mobile-page-header">
        <div>
            <h2 class="mb-1">Gestion des films</h2>
            <p class="text-muted mb-0">Ajoutez, recherchez et gérez tout le catalogue du festival.</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap mobile-actions-stack">
            <span class="badge bg-dark fs-6">{{ $films->total() }} film(s)</span>
            <a href="{{ route('admin.films.create') }}" class="btn btn-success">+ Ajouter un film</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('admin.films.index') }}" class="row g-2 align-items-center">
                <div class="col-md-10">
                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        placeholder="Rechercher par titre, réalisateur, acteur, pays, catégorie..."
                        value="{{ $search ?? request('q') }}"
                    >
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                </div>
                @if(($search ?? request('q')))
                    <div class="col-12">
                        <a href="{{ route('admin.films.index') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser la recherche</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 220px;">Film</th>
                        <th>Année</th>
                        <th>Pays</th>
                        <th>Durée</th>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th class="text-end" style="min-width: 230px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($films as $film)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($film->affiche)
                                    <img src="{{ asset('storage/' . $film->affiche) }}" alt="Affiche" class="rounded" style="width: 40px; height: 54px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-light border d-flex align-items-center justify-content-center" style="width: 40px; height: 54px;">
                                        <small class="text-muted">N/A</small>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $film->titre }}</strong>
                                    <div class="text-muted small">{{ $film->realisateur }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $film->annee_production }}</td>
                        <td>{{ $film->pays }}</td>
                        <td>{{ $film->duree }} min</td>
                        <td><span class="badge bg-light text-dark border">{{ $film->categorie }}</span></td>
                        <td>
                            <span class="badge {{ $film->type === 'serie' ? 'bg-secondary' : 'bg-primary' }}">
                                {{ $film->type === 'serie' ? 'Série' : 'Film' }}
                            </span>
                        </td>
                        <td class="text-end mobile-table-actions">
                            <div class="d-inline-flex gap-1 flex-wrap justify-content-end">
                                <a href="{{ route('admin.films.show', $film) }}" class="btn btn-sm btn-outline-info">Voir</a>
                                <a href="{{ route('admin.films.edit', $film) }}" class="btn btn-sm btn-outline-warning">Modifier</a>
                                <form action="{{ route('admin.films.destroy', $film) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce film ?')">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Aucun film trouvé.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $films->links() }}
    </div>
</div>
@endsection
