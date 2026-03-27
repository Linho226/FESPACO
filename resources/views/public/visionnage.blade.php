<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visionnage - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .player-card { border: none; border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    </style>
</head>
<body>
@include('public.navbar')

<div class="container py-5">
    <a href="{{ route('public.projections') }}" class="btn btn-outline-secondary btn-sm mb-3">← Retour aux projections</a>

    <div class="card player-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <h2 class="mb-1">{{ $film->titre ?? 'Projection' }}</h2>
                    <p class="text-muted mb-0">
                        {{ $projection->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($projection->heure)->format('H\hi') }}
                        • {{ $projection->salle }} • {{ $projection->lieu }}
                    </p>
                </div>
                @php $etat = $projection->etat(); @endphp
                <span class="badge bg-{{ $etat['badge'] }}">{{ $etat['icon'] }} {{ $etat['label'] }}</span>
            </div>

            @if($videoUrl)
                @php
                    $isExternal = str_starts_with($videoUrl, 'http://') || str_starts_with($videoUrl, 'https://');
                @endphp

                @if($isExternal)
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $videoUrl }}" title="Lecteur vidéo" allowfullscreen></iframe>
                    </div>
                @else
                    <video class="w-100 rounded" controls autoplay preload="metadata">
                        <source src="{{ $videoUrl }}">
                        Votre navigateur ne prend pas en charge la lecture vidéo.
                    </video>
                @endif
            @else
                <div class="alert alert-warning mb-0">
                    Cette séance est sélectionnée, mais aucun média vidéo n'est encore disponible pour ce film.
                </div>
            @endif

            @if(!empty($projection->notes))
                <hr>
                <p class="mb-0"><strong>Note:</strong> {{ $projection->notes }}</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
