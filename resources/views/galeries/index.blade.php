@extends('admin.layout')
@section('title', 'Galerie multimédia')

@section('content')
<div class="container px-0 films-admin">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1">Galerie multimédia</h2>
            <p class="text-muted mb-0">Images et vidéos du festival, liées ou non à un film.</p>
        </div>
        <a href="{{ route('admin.galeries.create') }}" class="btn btn-success">+ Ajouter un média</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filtre par film --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.galeries.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
                <select name="film_id" class="form-select" style="max-width:320px;">
                    <option value="">— Tous les médias —</option>
                    @foreach($films as $film)
                        <option value="{{ $film->id }}" {{ $filmId == $film->id ? 'selected' : '' }}>
                            {{ $film->titre }} ({{ $film->annee_production }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filtrer</button>
                @if($filmId)
                    <a href="{{ route('admin.galeries.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse($galeries as $media)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius:12px; overflow:hidden;">
                    {{-- Miniature --}}
                    <div class="bg-light" style="height:160px; overflow:hidden; position:relative;">
                        @if($media->type_media === 'image' && $media->fichier)
                            <img src="{{ asset('storage/'.$media->fichier) }}" alt="{{ $media->titre }}"
                                 style="width:100%; height:160px; object-fit:cover;">
                        @elseif($media->type_media === 'video' && $media->fichier)
                            <video style="width:100%; height:160px; object-fit:cover;">
                                <source src="{{ asset('storage/'.$media->fichier) }}">
                            </video>
                        @elseif($media->lien)
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <span style="font-size:2.5rem;">🔗</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted small">
                                Aucun aperçu
                            </div>
                        @endif
                        <span class="badge {{ $media->type_media === 'image' ? 'bg-info' : 'bg-warning text-dark' }} position-absolute top-0 end-0 m-2">
                            {{ $media->type_media === 'image' ? '🖼 Image' : '🎬 Vidéo' }}
                        </span>
                    </div>

                    <div class="card-body p-3">
                        <h6 class="mb-1 fw-semibold text-truncate">{{ $media->titre }}</h6>
                        @if($media->film)
                            <a href="{{ route('admin.films.show', $media->film) }}"
                               class="text-muted small text-decoration-none">
                                🎞 {{ $media->film->titre }}
                            </a>
                        @else
                            <span class="text-muted small">Festival général</span>
                        @endif
                        <p class="text-muted small mt-1 mb-2">{{ $media->date }}</p>

                        @if(($media->type_media === 'video' && $media->fichier) || $media->lien)
                            <a href="{{ route('admin.galeries.play', ['galerie' => $media->id]) }}" class="btn btn-sm btn-outline-primary w-100 mb-2">
                                Voir dans l'app
                            </a>
                        @endif

                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.galeries.edit', ['galerie' => $media->id]) }}" class="btn btn-sm btn-outline-warning flex-fill">Modifier</a>
                            <form action="{{ route('admin.galeries.destroy', ['galerie' => $media->id]) }}" method="POST" class="flex-fill"
                                  onsubmit="return confirm('Supprimer ce média ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <p class="mb-2" style="font-size:2rem;">📷</p>
                    <p>Aucun média trouvé.</p>
                    <a href="{{ route('admin.galeries.create') }}" class="btn btn-success">Ajouter un premier média</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $galeries->links() }}</div>
</div>
@endsection
