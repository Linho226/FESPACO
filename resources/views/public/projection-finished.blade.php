<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projection terminée - FESPACO</title>
    <script>
        (() => {
            const mq = window.matchMedia('(prefers-color-scheme: dark)');
            const apply = () => document.documentElement.setAttribute('data-bs-theme', mq.matches ? 'dark' : 'light');
            apply();
            if (typeof mq.addEventListener === 'function') mq.addEventListener('change', apply);
            else mq.addListener(apply);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/public/projection-finished.css">
</head>
<body>
    @include('public.navbar')

    <main class="finished-shell">
        <div class="container">
            <section class="finished-card">
                <div class="finished-badge">✓ Projection terminée</div>
                <h1 class="finished-title">Merci d’avoir suivi cette projection</h1>
                <p class="finished-text">
                    La séance <strong>{{ $projection->getTitreAffiche() }}</strong> est maintenant terminée. Merci pour votre présence.
                    Vous pouvez revenir au programme pour découvrir les projections encore disponibles.
                </p>

                <div class="finished-meta">
                    <span>📍 {{ $projection->lieu }}</span>
                    <span>📅 {{ $projection->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($projection->heure)->format('H\hi:s') }}</span>
                    <span>🏁 Fin prévue {{ $projection->finPrevue()->format('H\hi:s') }}</span>
                </div>

                <div class="finished-actions">
                    <a href="{{ route('public.projections') }}" class="finished-primary">Voir les projections disponibles</a>
                    <a href="{{ route('public.home') }}" class="finished-secondary">Retour à l’accueil</a>
                </div>

                @if($availableProjections->isNotEmpty())
                    <h2 class="h5 mb-3">À découvrir ensuite</h2>
                    <div class="available-grid">
                        @foreach($availableProjections as $availableProjection)
                            <article class="available-card">
                                <div class="available-card__title">{{ $availableProjection->getTitreAffiche() }}</div>
                                <div class="available-card__meta">
                                    {{ $availableProjection->date->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($availableProjection->heure)->format('H\hi:s') }}<br>
                                    {{ $availableProjection->lieu }}
                                </div>
                                <a href="{{ route('public.projections') }}#projection-{{ $availableProjection->id }}" class="available-card__link">Voir cette séance</a>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </main>

    @include('public.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>