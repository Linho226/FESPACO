<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $actualite->titre }} - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('public.navbar')

    <div class="container py-4 py-md-5" style="max-width: 900px;">
        <h1 class="mb-3">{{ $actualite->titre }}</h1>

        <p class="text-muted mb-3">
            Publie le {{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }}
            par {{ $actualite->auteur->name ?? '-' }}
        </p>

        @if($actualite->image)
            <img
                src="{{ asset('storage/'.$actualite->image) }}"
                alt="Image actualite"
                class="img-fluid rounded mb-4"
                style="max-height: 460px; width: 100%; object-fit: cover;"
            >
        @endif

        <div class="mb-4" style="line-height: 1.8; font-size: 1.05rem;">
            {!! nl2br(e($actualite->contenu)) !!}
        </div>

        <a href="{{ route('public.actualites') }}" class="btn btn-outline-secondary">Retour aux actualites</a>
    </div>
</body>
</html>
