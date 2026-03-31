<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visionnage - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #006241;
            --danger: #e63946;
        }
        
        * { box-sizing: border-box; }
        body { 
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 100%);
            color: #f0f0f0;
            min-height: 100vh;
        }
        
        .player-card { 
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,.3);
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.1);
            backdrop-filter: blur(10px);
        }
        
        .player-header {
            border-bottom: 1px solid rgba(255,255,255,.1);
            padding-bottom: 1.5rem !important;
        }
        
        .player-title {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #d0d0d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .subtitle-text {
            color: rgba(255,255,255,.7) !important;
            font-size: 0.95rem;
        }
        
        .status-badge {
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 0.6rem 1.2rem !important;
            border-radius: 50px !important;
            font-size: 0.85rem;
        }
        
        .pause-counter {
            border-radius: 12px;
            border: 1px solid rgba(255, 193, 7, .3);
            background: rgba(255, 193, 7, .08);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .pause-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ffc107;
            display: inline-block;
            margin-right: 0.5rem;
            animation: pulsePause 1.2s infinite;
        }
        
        @keyframes pulsePause {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.5; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .readonly-iframe-wrap {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
        }
        
        .readonly-iframe-wrap iframe {
            pointer-events: none;
        }
        
        .readonly-iframe-shield {
            position: absolute;
            inset: 0;
            z-index: 2;
            background: transparent;
            cursor: not-allowed;
        }
        
        .media-info {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.5rem;
            backdrop-filter: blur(10px);
        }
        
        .media-info h6 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .sound-btn {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sound-btn:hover:not(:disabled) {
            background: #00582f;
            border-color: #00582f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 98, 65, 0.3);
        }
        
        .sound-btn:disabled {
            opacity: 0.6;
            cursor: default;
        }
        
        .medias-section {
            border-top: 1px solid rgba(255,255,255,.1);
            padding-top: 2rem;
            margin-top: 2rem;
        }
        
        .medias-section h6 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.25rem;
            font-size: 1.1rem;
        }
        
        .sequence-note {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            color: rgba(255,255,255,.85);
        }
        
        .next-video-alert {
            background: rgba(0, 98, 65, .1);
            border: 1px solid rgba(0, 98, 65, .3);
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }
        
        .countdown-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            color: white;
        }
        
        .back-link {
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: white;
            margin-left: -4px;
        }
    </style>
</head>
<body>
@include('public.navbar')

<div class="container py-4 py-md-5">
    <a href="{{ route('public.projections') }}" class="back-link">
        ← Retour aux projections
    </a>

    <div class="card player-card">
        <div class="card-body p-4 p-md-5">
            <div class="player-header">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h2 class="player-title">{{ $projection->getTitreAffiche() }}</h2>
                        <p class="subtitle-text mb-0">
                            <strong>{{ $projection->date->format('d/m/Y') }}</strong> à <strong>{{ \Carbon\Carbon::parse($projection->heure)->format('H\hi') }}</strong>
                            • {{ $projection->lieu }}
                        </p>
                    </div>
                    @php $etat = $projection->etat(); @endphp
                    <span id="projection-status-badge" class="badge status-badge bg-{{ $etat['badge'] }}">{{ $etat['icon'] }} {{ $etat['label'] }}</span>
                </div>
            </div>

            <div id="projection-status-alert" class="alert alert-warning d-none" role="alert"></div>
            <div id="projection-pause-counter" class="pause-counter d-none">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="small fw-semibold"><span class="pause-dot"></span>Projection en pause · reprise imminente</div>
                    <div class="small">Pause depuis <strong id="pause-elapsed">00:00</strong></div>
                </div>
            </div>

            <div id="projection-player-zone">
            @if($activeMedia)
                @if(!empty($activeMedia['fichier_url']))
                    <video id="projection-video-player" class="w-100 rounded" controls autoplay preload="metadata" data-projection-etat="{{ $projection->estEnCours() ? 'en_cours' : 'autre' }}" style="background: #000;">
                        <source src="{{ $activeMedia['fichier_url'] }}">
                        Votre navigateur ne prend pas en charge la lecture vidéo.
                    </video>
                @elseif(!empty($activeMedia['embed_url']))
                    <div class="ratio ratio-16x9 rounded overflow-hidden readonly-iframe-wrap" id="projection-iframe-wrapper">
                        <iframe id="projection-embed-player" src="{{ $activeMedia['embed_url'] }}" title="Lecteur vidéo" allow="autoplay; encrypted-media; picture-in-picture" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        <div class="readonly-iframe-shield" aria-hidden="true"></div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Mode diffusion: lecture seule.</p>
                @elseif(!empty($activeMedia['lien']))
                    <div class="ratio ratio-16x9 rounded overflow-hidden border readonly-iframe-wrap" id="projection-iframe-wrapper">
                        <iframe src="{{ $activeMedia['lien'] }}" title="Lecteur vidéo" allow="autoplay; encrypted-media; picture-in-picture" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        <div class="readonly-iframe-shield" aria-hidden="true"></div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Mode diffusion: lecture seule. Certains sites peuvent bloquer l'affichage intégré.</p>
                @else
                    <div class="alert alert-warning mb-0">Le média sélectionné n'est pas lisible.</div>
                @endif

                <div class="media-info">
                    <h6>📹 Média en cours</h6>
                    <p class="mb-2"><strong>{{ $activeMedia['titre'] }}</strong></p>
                    @if(!empty($activeMedia['fichier_url']) || !empty($activeMedia['embed_url']))
                        <button id="enable-sound-btn" type="button" class="sound-btn">🔊 Activer le son</button>
                        <small id="sound-help" class="text-muted d-block mt-2"></small>
                    @endif
                    @if(!empty($activeMedia['description']))
                        <p class="text-muted small mb-0 mt-2">{{ $activeMedia['description'] }}</p>
                    @endif
                </div>

                @if($medias->count() > 1)
                    <div id="next-video-container" class="next-video-alert d-none">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <small class="text-muted d-block mb-1">⏭️ Prochaine vidéo</small>
                                <strong id="next-video-title" class="text-white"></strong>
                            </div>
                            <div class="countdown-circle" id="countdown-seconds">10</div>
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-warning mb-0">
                    Cette séance est sélectionnée, mais aucune vidéo n'est disponible pour votre sélection.
                </div>
            @endif
            </div>

            @if($medias->isNotEmpty())
                @php
                    $activeIndex = $medias->search(fn($media) => ($activeMedia['id'] ?? null) === $media['id']);
                    $nextMedia = ($activeIndex !== false && $activeIndex !== null) ? $medias->get($activeIndex + 1) : null;
                @endphp
                <div class="medias-section">
                    <h6>Diffusion automatique</h6>
                    <div class="sequence-note">
                        <p class="mb-2"><strong>Lecture en cours :</strong> {{ $activeMedia['titre'] ?? 'Aucun média en cours' }}</p>
                        @if($nextMedia)
                            <p class="mb-0"><strong>Programme suivant :</strong> {{ $nextMedia['titre'] }}<br><small class="text-muted">Cette vidéo sera disponible automatiquement après celle en cours.</small></p>
                        @else
                            <p class="mb-0"><strong>Programme suivant :</strong> Aucun média suivant pour cette séance.</p>
                        @endif
                    </div>
                </div>
            @endif

            @if(!empty($projection->notes))
                <hr style="border-color: rgba(255,255,255,.1); margin-top: 2rem; margin-bottom: 1.5rem;">
                <p class="mb-0" style="color: rgba(255,255,255,.8);"><strong>📝 Note :</strong> {{ $projection->notes }}</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Empêche la pause et le seek si projection en cours
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('projection-video-player');
        if (video && video.dataset.projectionEtat === 'en_cours') {
            video.addEventListener('pause', function(e) {
                if (!video.ended && !video.seeking) {
                    video.play();
                }
            });
            let lastTime = 0;
            video.addEventListener('timeupdate', function() {
                lastTime = video.currentTime;
            });
            video.addEventListener('seeking', function(e) {
                if (Math.abs(video.currentTime - lastTime) > 1) {
                    video.currentTime = lastTime;
                }
            });
            video.addEventListener('keydown', function(e) {
                if (e.code === 'Space') {
                    e.preventDefault();
                }
            });
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
    const medias = @json($medias);
    const activeMediaId = @json($activeMedia['id'] ?? null);
    
    const statusBadge = document.getElementById('projection-status-badge');
    const statusAlert = document.getElementById('projection-status-alert');
    const pauseCounter = document.getElementById('projection-pause-counter');
    const pauseElapsedEl = document.getElementById('pause-elapsed');
    const playerZone = document.getElementById('projection-player-zone');
    const html5Video = document.getElementById('projection-video-player');
    const embedPlayer = document.getElementById('projection-embed-player');
    const enableSoundBtn = document.getElementById('enable-sound-btn');
    const soundHelp = document.getElementById('sound-help');
    const nextVideoContainer = document.getElementById('next-video-container');
    const countdownCircle = document.getElementById('countdown-seconds');
    const nextVideoTitle = document.getElementById('next-video-title');
    
    const embedProvider = @json($activeMedia['embed_provider'] ?? null);
    let playerStopped = false;
    let projectionForcedStop = false;
    let pauseTimerInterval = null;
    let nextVideoCountdown = null;
    let nextVideoStartTimer = null;

    function enableEmbedSound() {
        if (!embedPlayer || !embedProvider) return false;
        try {
            if (embedProvider === 'youtube') {
                embedPlayer.contentWindow?.postMessage('{"event":"command","func":"unMute","args":""}', '*');
                embedPlayer.contentWindow?.postMessage('{"event":"command","func":"setVolume","args":[100]}', '*');
                embedPlayer.contentWindow?.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                return true;
            }
            if (embedProvider === 'vimeo') {
                embedPlayer.contentWindow?.postMessage({ method: 'setMuted', value: false }, '*');
                embedPlayer.contentWindow?.postMessage({ method: 'setVolume', value: 1 }, '*');
                embedPlayer.contentWindow?.postMessage({ method: 'play' }, '*');
                return true;
            }
        } catch (e) {
            console.warn('Activation du son impossible', e);
        }
        return false;
    }

    enableSoundBtn?.addEventListener('click', () => {
        if (html5Video) {
            html5Video.muted = false;
            html5Video.volume = 1;
            html5Video.play().catch(() => {});
            enableSoundBtn.textContent = '✓ Son activé';
            enableSoundBtn.disabled = true;
            return;
        }
        if (enableEmbedSound()) {
            enableSoundBtn.textContent = '✓ Son activé';
            enableSoundBtn.disabled = true;
            if (soundHelp) soundHelp.textContent = '';
            return;
        }
        if (soundHelp) soundHelp.textContent = 'Impossible d\'activer le son automatiquement pour cette source.';
    });

    function applyPlaybackOffset() {
        if (!html5Video || playbackOffsetSeconds <= 0) return;
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

    function startNextVideoCountdown(nextMedia) {
        if (!nextMedia || playerStopped) return;

        if (nextVideoContainer) {
            nextVideoContainer.classList.remove('d-none');
            nextVideoTitle.textContent = nextMedia.titre;
        }

        let countdown = 10;
        if (countdownCircle) countdownCircle.textContent = countdown;

        if (nextVideoCountdown) clearInterval(nextVideoCountdown);

        nextVideoCountdown = setInterval(() => {
            if (playerStopped) {
                clearInterval(nextVideoCountdown);
                return;
            }

            countdown--;
            if (countdownCircle) countdownCircle.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(nextVideoCountdown);
                const nextUrl = new URL(@json(route('public.projections.visionner', ['projection' => $projection->id])), window.location.origin);
                nextUrl.searchParams.set('autonext', '1');
                nextUrl.searchParams.set('from_media', String(activeMediaId || '0'));
                window.location.href = nextUrl.toString();
            }
        }, 1000);
    }

    // Auto-avance à la prochaine vidéo
    function setupAutoAdvance() {
        if (medias.length <= 1 || !activeMediaId) return;

        const currentIndex = medias.findIndex(m => m.id === activeMediaId);
        if (currentIndex === -1 || currentIndex === medias.length - 1) return;

        const nextMedia = medias[currentIndex + 1];
        const currentMedia = medias[currentIndex];
        const currentMediaDuration = Math.max(0, Number(currentMedia?.duree_secondes || 0));
        const safeOffset = Math.max(0, Number(playbackOffsetSeconds || 0));

        if (html5Video) {
            html5Video.addEventListener('ended', () => startNextVideoCountdown(nextMedia));
            return;
        }

        // Fallback pour les embeds (YouTube/Vimeo): on bascule selon la durée connue.
        if (currentMediaDuration > 0) {
            const remainingSeconds = Math.max(2, currentMediaDuration - safeOffset);
            if (nextVideoStartTimer) clearTimeout(nextVideoStartTimer);
            nextVideoStartTimer = setTimeout(() => {
                startNextVideoCountdown(nextMedia);
            }, remainingSeconds * 1000);
        }
    }

    setupAutoAdvance();

    function getAlertClass(status) {
        if (status === 'Arrêtée') return 'alert-warning';
        if (status === 'Terminée') return 'alert-secondary';
        return 'alert-info';
    }

    function formatDuration(totalSeconds) {
        const safe = Math.max(0, Number(totalSeconds) || 0);
        const min = Math.floor(safe / 60).toString().padStart(2, '0');
        const sec = Math.floor(safe % 60).toString().padStart(2, '0');
        return `${min}:${sec}`;
    }

    function startPauseCounter(initialSeconds) {
        if (!pauseCounter || !pauseElapsedEl) return;
        let elapsed = Math.max(0, Number(initialSeconds) || 0);
        pauseElapsedEl.textContent = formatDuration(elapsed);
        pauseCounter.classList.remove('d-none');
        if (pauseTimerInterval) clearInterval(pauseTimerInterval);
        pauseTimerInterval = setInterval(() => {
            elapsed += 1;
            pauseElapsedEl.textContent = formatDuration(elapsed);
        }, 1000);
    }

    function stopPauseCounter() {
        if (pauseTimerInterval) clearInterval(pauseTimerInterval);
        if (pauseCounter) pauseCounter.classList.add('d-none');
    }

    function stopPlayerAndShowMessage(message, status) {
        if (html5Video && !html5Video.paused) html5Video.pause();
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
            if (!response.ok) return;
            const data = await response.json();
            if (statusBadge) {
                statusBadge.className = `badge status-badge bg-${data.badge}`;
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
                if (statusAlert) statusAlert.classList.add('d-none');
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
            console.error('Erreur de vérification du statut', error);
        }
    }

    window.projectionStatusInterval = setInterval(refreshProjectionStatus, 10000);
</script>
</body>
</html>
