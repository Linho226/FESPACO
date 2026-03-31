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
        .pause-counter {
            border-radius: 10px;
            border: 1px solid rgba(255, 193, 7, .45);
            background: rgba(255, 193, 7, .14);
            padding: .6rem .85rem;
        }
        .pause-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ffc107;
            display: inline-block;
            margin-right: .4rem;
            animation: pulsePause 1.1s infinite;
        }
        @keyframes pulsePause {
            0% { transform: scale(1); opacity: .9; }
            50% { transform: scale(1.35); opacity: .45; }
            100% { transform: scale(1); opacity: .9; }
        }
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
                <span id="projection-status-badge" class="badge bg-{{ $etat['badge'] }}">{{ $etat['icon'] }} {{ $etat['label'] }}</span>
            </div>

            <div id="projection-status-alert" class="alert alert-warning d-none" role="alert"></div>
            <div id="projection-pause-counter" class="pause-counter d-none mb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="small text-dark fw-semibold"><span class="pause-dot"></span>Projection en pause · reprise imminente</div>
                    <div class="small text-dark">Pause depuis <strong id="pause-elapsed">00:00</strong></div>
                </div>
            </div>

            <div id="projection-player-zone">
            @if($activeMedia)
                @if(!empty($activeMedia['fichier_url']))
                    <video id="projection-video-player" class="w-100 rounded" controls autoplay preload="metadata" data-projection-etat="{{ $projection->estEnCours() ? 'en_cours' : 'autre' }}">
                        <source src="{{ $activeMedia['fichier_url'] }}">
                        Votre navigateur ne prend pas en charge la lecture vidéo.
                    </video>
                @elseif(!empty($activeMedia['embed_url']))
                    <div class="ratio ratio-16x9 rounded overflow-hidden" id="projection-iframe-wrapper">
                        <iframe src="{{ $activeMedia['embed_url'] }}" title="Lecteur vidéo" allowfullscreen></iframe>
                    </div>
                @elseif(!empty($activeMedia['lien']))
                    <div class="ratio ratio-16x9 rounded overflow-hidden border" id="projection-iframe-wrapper">
                        <iframe src="{{ $activeMedia['lien'] }}" title="Lecteur vidéo" allowfullscreen></iframe>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Certains sites peuvent bloquer l'affichage intégré.</p>
                @else
                    <div class="alert alert-warning mb-0">Le média sélectionné n'est pas lisible.</div>
                @endif

                <div class="mt-3">
                    <h6 class="mb-1">Média en cours : {{ $activeMedia['titre'] }}</h6>
                    @if(!empty($activeMedia['description']))
                        <p class="text-muted small mb-0">{{ $activeMedia['description'] }}</p>
                    @endif
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    Cette séance est sélectionnée, mais aucune vidéo n'est encore liée à ce film.
                </div>
            @endif
            </div>

            @if($medias->isNotEmpty())
                <hr>
                <h6 class="mb-3">Vidéos liées à ce film</h6>
                <div class="row g-2">
                    @foreach($medias as $media)
                        <div class="col-md-4 col-lg-3">
                            <a href="{{ route('public.projections.visionner', ['projection' => $projection->id, 'media' => $media['id']]) }}"
                               class="d-block text-decoration-none p-2 border rounded {{ ($activeMedia['id'] ?? null) === $media['id'] ? 'border-primary bg-primary-subtle' : 'border-light bg-white' }}">
                                <div class="small fw-semibold text-dark text-truncate">{{ $media['titre'] }}</div>
                                <div class="small text-muted">🎬 Vidéo</div>
                            </a>
                        </div>
                    @endforeach
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
<script>
        // Empêche la pause et le seek si projection en cours, mais laisse volume et plein écran
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('projection-video-player');
            if (video && video.dataset.projectionEtat === 'en_cours') {
                // Empêche la pause
                video.addEventListener('pause', function(e) {
                    if (!video.ended && !video.seeking) {
                        video.play();
                    }
                });
                // Empêche le seek
                let lastTime = 0;
                video.addEventListener('timeupdate', function() {
                    lastTime = video.currentTime;
                });
                video.addEventListener('seeking', function(e) {
                    if (Math.abs(video.currentTime - lastTime) > 1) {
                        video.currentTime = lastTime;
                    }
                });
                // Empêche le raccourci clavier espace
                video.addEventListener('keydown', function(e) {
                    if (e.code === 'Space') {
                        e.preventDefault();
                    }
                });
                // Empêche toute relance après la fin
                let ended = false;
                video.addEventListener('ended', function() {
                    ended = true;
                });
                video.addEventListener('play', function(e) {
                    if (ended) {
                        video.pause();
                        video.currentTime = video.duration;
                    }
                });
            }
        });
    const statusUrl = @json(route('public.projections.status', $projection));
    const projectionsUrl = @json(route('public.projections'));
    const playbackOffsetSeconds = Number(@json($playbackOffsetSeconds ?? 0)) || 0;
    const statusBadge = document.getElementById('projection-status-badge');
    const statusAlert = document.getElementById('projection-status-alert');
    const pauseCounter = document.getElementById('projection-pause-counter');
    const pauseElapsedEl = document.getElementById('pause-elapsed');
    const playerZone = document.getElementById('projection-player-zone');
    const html5Video = document.getElementById('projection-video-player');
    let playerStopped = false;
    let projectionForcedStop = false;
    let pauseTimerInterval = null;

    function applyPlaybackOffset() {
        if (!html5Video || playbackOffsetSeconds <= 0) {
            return;
        }

        const syncOffset = () => {
            try {
                const maxSeek = Math.max(0, (html5Video.duration || 0) - 1);
                const target = maxSeek > 0 ? Math.min(playbackOffsetSeconds, maxSeek) : playbackOffsetSeconds;
                if (target > 0 && Number.isFinite(target)) {
                    html5Video.currentTime = target;
                }
            } catch (e) {
                console.warn('Impossible de synchroniser la position vidéo', e);
            }
        };

        if (html5Video.readyState >= 1) {
            syncOffset();
        } else {
            html5Video.addEventListener('loadedmetadata', syncOffset, { once: true });
        }
    }

    applyPlaybackOffset();

    function getAlertClass(status) {
        if (status === 'Arrêtée') {
            return 'alert-warning';
        }

        if (status === 'Terminée') {
            return 'alert-secondary';
        }

        return 'alert-info';
    }

    function formatDuration(totalSeconds) {
        const safe = Math.max(0, Number(totalSeconds) || 0);
        const min = Math.floor(safe / 60).toString().padStart(2, '0');
        const sec = Math.floor(safe % 60).toString().padStart(2, '0');
        return `${min}:${sec}`;
    }

    function startPauseCounter(initialSeconds) {
        if (!pauseCounter || !pauseElapsedEl) {
            return;
        }

        let elapsed = Math.max(0, Number(initialSeconds) || 0);
        pauseElapsedEl.textContent = formatDuration(elapsed);
        pauseCounter.classList.remove('d-none');

        if (pauseTimerInterval) {
            clearInterval(pauseTimerInterval);
        }

        pauseTimerInterval = setInterval(() => {
            elapsed += 1;
            pauseElapsedEl.textContent = formatDuration(elapsed);
        }, 1000);
    }

    function stopPauseCounter() {
        if (pauseTimerInterval) {
            clearInterval(pauseTimerInterval);
            pauseTimerInterval = null;
        }

        if (pauseCounter) {
            pauseCounter.classList.add('d-none');
        }
    }

    function stopPlayerAndShowMessage(message, status) {
        if (html5Video && !html5Video.paused) {
            html5Video.pause();
        }

        playerStopped = true;
        projectionForcedStop = true;

        if (playerZone) {
            playerZone.innerHTML = `
                <div class="alert ${getAlertClass(status)} mb-0">
                    ${message}<br>
                    <a href="${projectionsUrl}" class="btn btn-sm btn-outline-secondary mt-3">Retour aux projections</a>
                </div>
            `;
        }
    }

    async function refreshProjectionStatus() {
        try {
            const response = await fetch(statusUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();

            if (statusBadge) {
                statusBadge.className = `badge bg-${data.badge}`;
                statusBadge.textContent = `${data.icon} ${data.status}`;
            }

            if (data.status === 'Arrêtée') {
                startPauseCounter(data.paused_elapsed_seconds ?? 0);
            } else {
                stopPauseCounter();
            }

            if (data.can_watch && playerStopped) {
                window.location.reload();
                return;
            }

            if (data.can_watch) {
                if (statusAlert) {
                    statusAlert.classList.add('d-none');
                }

                if (html5Video && html5Video.paused && projectionForcedStop) {
                    projectionForcedStop = false;
                    html5Video.play().catch(() => {});
                }
            }

            if (!data.can_watch) {
                if (statusAlert) {
                    statusAlert.textContent = data.message;
                    statusAlert.className = `alert ${getAlertClass(data.status)}`;
                    statusAlert.classList.remove('d-none');
                }

                stopPlayerAndShowMessage(data.message, data.status);
            }
        } catch (error) {
            console.error('Erreur de vérification du statut de projection', error);
        }
    }

    window.projectionStatusInterval = setInterval(refreshProjectionStatus, 10000);
</script>
</body>
</html>
