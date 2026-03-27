@extends('admin.layout')

@section('title', 'Programme des projections')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Programme des projections</h2>
        <a href="{{ route('admin.projections.create') }}" class="btn btn-success">
            + Ajouter une projection
        </a>
    </div>

    {{-- Messages de succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ====== ALERTES : En cours + Imminentes ====== --}}
    @if($alertes->isNotEmpty())
        <div class="mb-4">
            @foreach($alertes as $alerte)
                @if($alerte->estEnCours())
                    @php
                        $dureeFilm = (int) ($alerte->film->duree ?? 0);
                        $finPrevue = $dureeFilm > 0
                            ? $alerte->dateHeure()->copy()->addMinutes($dureeFilm)->format('H\hi')
                            : null;
                    @endphp
                    <div class="alert alert-success d-flex align-items-start gap-3 mb-2 shadow-sm" role="alert">
                        <span style="font-size:1.5rem">🟢</span>
                        <div>
                            <strong>Projection en cours !</strong>
                            <div>
                                <strong>{{ $alerte->film->titre ?? '—' }}</strong>
                                — {{ $alerte->salle }}, {{ $alerte->lieu }}
                                — débutée à {{ \Carbon\Carbon::parse($alerte->heure)->format('H\hi') }}
                                @if($dureeFilm > 0)
                                    — durée {{ $dureeFilm }} min — fin prévue {{ $finPrevue }}
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif($alerte->approcheImminente())
                    @php
                        $dureeFilm = (int) ($alerte->film->duree ?? 0);
                        $finPrevue = $dureeFilm > 0
                            ? $alerte->dateHeure()->copy()->addMinutes($dureeFilm)->format('H\hi')
                            : null;
                    @endphp
                    <div class="alert alert-warning d-flex align-items-start gap-3 mb-2 shadow-sm" role="alert">
                        <span style="font-size:1.5rem">⚠️</span>
                        <div>
                            @php
                                $diffTotal  = now()->diff($alerte->dateHeure());
                                $diffHeures = $diffTotal->h + ($diffTotal->days * 24);
                                $diffMins   = $diffTotal->i;
                            @endphp
                            <strong>Projection imminente dans {{ $diffHeures }}h{{ $diffMins > 0 ? $diffMins.'min' : '' }} !</strong>
                            <div>
                                <strong>{{ $alerte->film->titre ?? '—' }}</strong>
                                — {{ $alerte->salle }}, {{ $alerte->lieu }}
                                — le {{ $alerte->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($alerte->heure)->format('H\hi') }}
                                @if($dureeFilm > 0)
                                    — durée {{ $dureeFilm }} min — fin prévue {{ $finPrevue }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    {{-- Filtres --}}
    <form method="GET" action="" class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control"
                placeholder="Rechercher par film, salle, lieu..."
                value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <input type="date" name="date" class="form-control"
                value="{{ request('date') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary w-100" type="submit">Filtrer</button>
            <a href="{{ route('admin.projections.index') }}" class="btn btn-secondary w-100">Réinit.</a>
        </div>
    </form>

    {{-- Tableau --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Film</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Durée</th>
                    <th>Fin prévue</th>
                    <th>Salle</th>
                    <th>Lieu</th>
                    <th>Notes</th>
                    <th>État</th>
                    <th>Visibilité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($projections as $projection)
                @php $etat = $projection->etat(); @endphp
                <tr class="{{ $projection->estEnCours() ? 'table-success' : ($projection->approcheImminente() ? 'table-warning' : '') }}">
                    <td><strong>{{ $projection->film->titre ?? '—' }}</strong></td>
                    <td>{{ $projection->date->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($projection->heure)->format('H\hi') }}</td>
                    <td>
                        @if($projection->film && $projection->film->duree)
                            {{ $projection->film->duree }} min
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($projection->film && $projection->film->duree)
                            {{ $projection->dateHeure()->copy()->addMinutes((int) $projection->film->duree)->format('H\hi') }}
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $projection->salle }}</td>
                    <td>{{ $projection->lieu }}</td>
                    <td>{{ $projection->notes ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $etat['badge'] }}">
                            {{ $etat['icon'] }} {{ $etat['label'] }}
                        </span>
                        @if($projection->approcheImminente() && $projection->estAVenir())
                            @php
                                $diffTotal  = now()->diff($projection->dateHeure());
                                $diffHeures = $diffTotal->h + ($diffTotal->days * 24);
                                $diffMins   = $diffTotal->i;
                            @endphp
                            <br><small class="text-danger fw-bold">
                                ⚠️ Dans {{ $diffHeures }}h{{ $diffMins > 0 ? $diffMins.'min' : '' }}
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($projection->publie)
                            <span class="badge bg-success">Publié</span>
                        @else
                            <span class="badge bg-secondary">Non publié</span>
                        @endif
                    </td>
                    <td class="d-flex flex-wrap gap-1">
                        @if($projection->estAVenir() || $projection->estArreteeManuellement())
                            <form action="{{ route('admin.projections.demarrer', $projection) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Démarrer cette projection maintenant ?')">
                                    {{ $projection->estArreteeManuellement() ? '↻ Reprendre' : '▶ Démarrer' }}
                                </button>
                            </form>
                        @endif

                        @if($projection->estEnCours())
                            <form action="{{ route('admin.projections.arreter', $projection) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Mettre cette projection en pause ?')">⏸ Pause</button>
                            </form>
                        @endif

                        <a href="{{ route('admin.projections.edit', $projection) }}"
                           class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.projections.destroy', $projection) }}"
                              method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Supprimer cette projection ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">Aucune projection enregistrée.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $projections->withQueryString()->links() }}
</div>
@endsection
