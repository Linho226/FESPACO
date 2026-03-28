<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .actualite-img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
            background: #eee;
        }
    </style>
</head>
<body>
    @include('public.navbar')
    <div class="container py-5">
        <h1 class="mb-4">Actualités</h1>
        <p>Les dernières nouvelles du festival FESPACO.</p>
        <div class="row g-4">
            @forelse($actualites as $actualite)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if($actualite->image)
                            <img src="{{ asset('storage/'.$actualite->image) }}" class="card-img-top actualite-img" alt="Image actualité">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $actualite->titre }}</h5>
                            <p class="card-text small text-muted mb-1">
                                Publié le {{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }}
                                par {{ $actualite->auteur->name ?? '-' }}
                            </p>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($actualite->contenu), 120) }}</p>
                            <a href="{{ route('public.actualites.show', $actualite) }}" class="btn btn-outline-primary btn-sm">Lire la suite</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">Aucune actualité pour le moment.</div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $actualites->links() }}
        </div>
    </div>
</body>
</html>
