@if(!auth()->check() || !auth()->user()->isAdmin())
<div id="projection-resume-notice" class="projection-resume-notice" hidden>
    <div class="projection-resume-notice__card" role="status" aria-live="polite" aria-atomic="true">
        <div class="projection-resume-notice__content">
            <p id="projection-resume-notice-eyebrow" class="projection-resume-notice__eyebrow">Projection reprise</p>
            <p id="projection-resume-notice-text" class="projection-resume-notice__text"></p>
        </div>
        <div class="projection-resume-notice__actions">
            <a id="projection-resume-notice-link" class="projection-resume-notice__primary" href="#">Rejoindre</a>
            <button id="projection-resume-notice-dismiss" type="button" class="projection-resume-notice__secondary">Plus tard</button>
        </div>
    </div>
</div>

<button id="projection-start-alert-toggle" class="projection-alert-toggle" type="button" aria-pressed="true">
    Alertes projections: activées
</button>

<style>
    .projection-resume-notice {
        position: fixed;
        top: 5.25rem;
        right: 1rem;
        left: 1rem;
        display: flex;
        justify-content: center;
        z-index: 1200;
        pointer-events: none;
    }

    .projection-resume-notice__card {
        width: min(100%, 38rem);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.95rem 1rem;
        border-radius: 1rem;
        border: 1px solid rgba(245, 166, 35, 0.35);
        background: rgba(17, 24, 39, 0.96);
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.28);
        color: #f8fafc;
        pointer-events: auto;
    }

    .projection-resume-notice__content {
        min-width: 0;
    }

    .projection-resume-notice__eyebrow {
        margin: 0 0 0.2rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #f5a623;
    }

    .projection-resume-notice__text {
        margin: 0;
        font-size: 0.96rem;
        line-height: 1.45;
        color: #f8fafc;
    }

    .projection-resume-notice__actions {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-shrink: 0;
    }

    .projection-resume-notice__primary,
    .projection-resume-notice__secondary {
        border-radius: 999px;
        padding: 0.62rem 0.95rem;
        font-size: 0.88rem;
        font-weight: 700;
        line-height: 1;
        text-decoration: none;
        border: 1px solid transparent;
        transition: transform 0.18s ease, background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease;
    }

    .projection-resume-notice__primary {
        background: linear-gradient(135deg, #f5a623, #d48810);
        color: #1f2937;
    }

    .projection-resume-notice__primary:hover {
        color: #111827;
        transform: translateY(-1px);
    }

    .projection-resume-notice__secondary {
        background: transparent;
        border-color: rgba(255, 255, 255, 0.16);
        color: rgba(248, 250, 252, 0.84);
        cursor: pointer;
    }

    .projection-resume-notice__secondary:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #fff;
    }

    .projection-alert-toggle {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 1190;
        border: 1px solid rgba(245, 166, 35, 0.28);
        border-radius: 999px;
        background: rgba(17, 24, 39, 0.94);
        color: #f8fafc;
        padding: 0.7rem 0.95rem;
        font-size: 0.82rem;
        font-weight: 700;
        line-height: 1;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.22);
        transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
    }

    .projection-alert-toggle:hover {
        transform: translateY(-1px);
    }

    .projection-alert-toggle.is-disabled {
        border-color: rgba(148, 163, 184, 0.24);
        color: rgba(248, 250, 252, 0.74);
    }

    [data-bs-theme="light"] .projection-resume-notice__card {
        background: rgba(255, 255, 255, 0.98);
        color: #111827;
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.16);
    }

    [data-bs-theme="light"] .projection-resume-notice__text {
        color: #111827;
    }

    [data-bs-theme="light"] .projection-resume-notice__secondary {
        border-color: rgba(15, 23, 42, 0.12);
        color: #374151;
    }

    [data-bs-theme="light"] .projection-alert-toggle {
        background: rgba(255, 255, 255, 0.98);
        color: #111827;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
    }

    [data-bs-theme="light"] .projection-alert-toggle.is-disabled {
        color: #6b7280;
        border-color: rgba(15, 23, 42, 0.12);
    }

    @media (max-width: 640px) {
        .projection-resume-notice {
            top: 4.5rem;
            left: 0.75rem;
            right: 0.75rem;
        }

        .projection-resume-notice__card {
            flex-direction: column;
            align-items: stretch;
        }

        .projection-resume-notice__actions {
            width: 100%;
            justify-content: stretch;
        }

        .projection-resume-notice__primary,
        .projection-resume-notice__secondary {
            flex: 1 1 0;
            text-align: center;
            justify-content: center;
        }

        .projection-alert-toggle {
            right: 0.75rem;
            left: 0.75rem;
            bottom: 0.75rem;
        }
    }
</style>

<script>
    (() => {
        const STORAGE_KEY = 'fespaco_projection_resume_notice';
        const ALERT_PREF_KEY = 'fespaco_projection_start_alert_enabled';
        const ALERT_LAST_SEEN_KEY = 'fespaco_projection_start_alert_last_seen';
        const LIVE_ALERT_LAST_SEEN_KEY = 'fespaco_projection_live_alert_last_seen';
        const IMMINENT_ALERT_URL = @json(route('public.projections.imminent-alert'));
        const LIVE_ALERT_URL = @json(route('public.projections.live-alert'));
        const notice = document.getElementById('projection-resume-notice');
        const eyebrow = document.getElementById('projection-resume-notice-eyebrow');
        const text = document.getElementById('projection-resume-notice-text');
        const link = document.getElementById('projection-resume-notice-link');
        const dismiss = document.getElementById('projection-resume-notice-dismiss');
        const toggle = document.getElementById('projection-start-alert-toggle');
        let visibleNoticeKind = null;
        let currentStartAlertFingerprint = null;
        let currentLiveAlertFingerprint = null;

        if (!notice || !eyebrow || !text || !link || !dismiss || !toggle) {
            return;
        }

        const normalizePath = (value) => {
            try {
                return new URL(value, window.location.origin).pathname;
            } catch (error) {
                return null;
            }
        };

        const readState = () => {
            try {
                const raw = window.localStorage.getItem(STORAGE_KEY);
                if (!raw) return null;
                const parsed = JSON.parse(raw);
                return parsed && typeof parsed === 'object' ? parsed : null;
            } catch (error) {
                return null;
            }
        };

        const writeState = (state) => {
            try {
                window.localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
            } catch (error) {
                console.warn('Impossible de sauvegarder la reprise de projection.', error);
            }
        };

        const clearState = () => {
            try {
                window.localStorage.removeItem(STORAGE_KEY);
            } catch (error) {
                console.warn('Impossible de nettoyer la reprise de projection.', error);
            }
            if (visibleNoticeKind === 'resume') {
                notice.hidden = true;
                visibleNoticeKind = null;
            }
        };

        const readBooleanPref = (key, defaultValue) => {
            try {
                const raw = window.localStorage.getItem(key);
                if (raw === null) return defaultValue;
                return raw === '1';
            } catch (error) {
                return defaultValue;
            }
        };

        const writeBooleanPref = (key, value) => {
            try {
                window.localStorage.setItem(key, value ? '1' : '0');
            } catch (error) {
                console.warn('Impossible de sauvegarder la préférence d\'alerte.', error);
            }
        };

        const readStringPref = (key) => {
            try {
                return window.localStorage.getItem(key);
            } catch (error) {
                return null;
            }
        };

        const writeStringPref = (key, value) => {
            try {
                window.localStorage.setItem(key, value);
            } catch (error) {
                console.warn('Impossible de sauvegarder l\'état de l\'alerte.', error);
            }
        };

        const isStartAlertEnabled = () => readBooleanPref(ALERT_PREF_KEY, true);

        const renderToggle = () => {
            const enabled = isStartAlertEnabled();
            toggle.setAttribute('aria-pressed', enabled ? 'true' : 'false');
            toggle.classList.toggle('is-disabled', !enabled);
            toggle.textContent = enabled
                ? 'Alertes projections: activées'
                : 'Alertes projections: désactivées';
        };

        const showResumeNotice = (state) => {
            if (!state?.watchUrl) {
                notice.hidden = true;
                visibleNoticeKind = null;
                return;
            }

            const projectionTitle = state.projectionTitle || 'Votre projection';
            eyebrow.textContent = 'Projection reprise';
            text.textContent = `${projectionTitle} a repris. Vous pouvez revenir directement au visionnage.`;
            link.textContent = 'Rejoindre';
            dismiss.textContent = 'Plus tard';
            link.href = state.watchUrl;
            notice.hidden = false;
            visibleNoticeKind = 'resume';
        };

        const showStartNotice = (projection) => {
            if (!projection?.watch_url) {
                return;
            }

            const totalSeconds = Math.max(0, Number(projection.starts_in_seconds) || 0);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = Math.floor(totalSeconds % 60).toString().padStart(2, '0');
            const countdown = `${minutes}min${seconds}s`;
            const title = projection.projection_title || 'Une projection';
            const location = projection.location ? ` à ${projection.location}` : '';
            currentStartAlertFingerprint = `${projection.projection_id}:${projection.starts_at}`;

            eyebrow.textContent = 'Projection imminente';
            text.textContent = `${title} commence dans ${countdown}${location}.`;
            link.textContent = 'Voir la projection';
            dismiss.textContent = 'Ignorer';
            link.href = projection.watch_url;
            notice.hidden = false;
            visibleNoticeKind = 'start';
        };

        const showLiveNotice = (projection) => {
            if (!projection?.watch_url) {
                return;
            }

            const totalSeconds = Math.max(0, Number(projection.started_since_seconds) || 0);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = Math.floor(totalSeconds % 60).toString().padStart(2, '0');
            const startedLabel = `${minutes}min${seconds}s`;
            const title = projection.projection_title || 'Une projection';
            const location = projection.location ? ` à ${projection.location}` : '';
            currentLiveAlertFingerprint = `${projection.projection_id}:${projection.started_at || 'live'}`;

            eyebrow.textContent = 'Projection en cours';
            text.textContent = `${title} est déjà en cours${location}. Débutée il y a ${startedLabel}. Voulez-vous rejoindre la diffusion ?`;
            link.textContent = 'Suivre maintenant';
            dismiss.textContent = 'Pas maintenant';
            link.href = projection.watch_url;
            notice.hidden = false;
            visibleNoticeKind = 'live';
        };

        const shouldHandleState = (state) => {
            if (!state?.statusUrl || !state?.watchUrl) {
                clearState();
                return false;
            }

            const currentPath = normalizePath(window.location.href);
            const watchPath = normalizePath(state.watchUrl);
            if (currentPath && watchPath && currentPath === watchPath && state.resumeReady) {
                clearState();
                return false;
            }

            return true;
        };

        const pollProjectionResume = async () => {
            const state = readState();
            if (!state || !shouldHandleState(state)) {
                return;
            }

            if (state.resumeReady) {
                showResumeNotice(state);
                return;
            }

            try {
                const response = await fetch(state.statusUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                if (data.can_watch) {
                    const nextState = {
                        ...state,
                        resumeReady: true,
                        resumedAt: new Date().toISOString(),
                    };
                    writeState(nextState);
                    showResumeNotice(nextState);
                    return;
                }

                if (data.status && data.status !== 'Arrêtée') {
                    clearState();
                    return;
                }

                notice.hidden = true;
                visibleNoticeKind = null;
            } catch (error) {
                console.warn('Erreur de vérification de reprise de projection.', error);
            }
        };

        const pollProjectionStartAlert = async () => {
            if (!isStartAlertEnabled()) {
                if (visibleNoticeKind === 'start' || visibleNoticeKind === 'live') {
                    notice.hidden = true;
                    visibleNoticeKind = null;
                }
                return;
            }

            const resumeState = readState();
            if (resumeState?.resumeReady) {
                return;
            }

            if (visibleNoticeKind === 'live') {
                return;
            }

            try {
                const response = await fetch(IMMINENT_ALERT_URL, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                if (!data.should_alert) {
                    if (visibleNoticeKind === 'start') {
                        notice.hidden = true;
                        visibleNoticeKind = null;
                    }
                    return;
                }

                const fingerprint = `${data.projection_id}:${data.starts_at}`;
                if (readStringPref(ALERT_LAST_SEEN_KEY) === fingerprint) {
                    if (visibleNoticeKind === 'start') {
                        notice.hidden = true;
                        visibleNoticeKind = null;
                    }
                    return;
                }

                showStartNotice(data);
            } catch (error) {
                console.warn('Erreur de vérification des projections imminentes.', error);
            }
        };

        const pollProjectionLiveAlert = async () => {
            if (!isStartAlertEnabled()) {
                if (visibleNoticeKind === 'live') {
                    notice.hidden = true;
                    visibleNoticeKind = null;
                }
                return;
            }

            const resumeState = readState();
            if (resumeState?.resumeReady) {
                return;
            }

            try {
                const response = await fetch(LIVE_ALERT_URL, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                if (!data.should_alert) {
                    if (visibleNoticeKind === 'live') {
                        notice.hidden = true;
                        visibleNoticeKind = null;
                    }
                    return;
                }

                const watchPath = normalizePath(data.watch_url);
                const currentPath = normalizePath(window.location.href);
                if (watchPath && currentPath && watchPath === currentPath) {
                    if (visibleNoticeKind === 'live') {
                        notice.hidden = true;
                        visibleNoticeKind = null;
                    }
                    return;
                }

                const fingerprint = `${data.projection_id}:${data.started_at || 'live'}`;
                if (readStringPref(LIVE_ALERT_LAST_SEEN_KEY) === fingerprint) {
                    if (visibleNoticeKind === 'live') {
                        notice.hidden = true;
                        visibleNoticeKind = null;
                    }
                    return;
                }

                showLiveNotice(data);
            } catch (error) {
                console.warn('Erreur de vérification des projections en cours.', error);
            }
        };

        dismiss.addEventListener('click', () => {
            if (visibleNoticeKind === 'resume') {
                clearState();
                return;
            }

            if (visibleNoticeKind === 'start') {
                if (currentStartAlertFingerprint) {
                    writeStringPref(ALERT_LAST_SEEN_KEY, currentStartAlertFingerprint);
                }
                notice.hidden = true;
                visibleNoticeKind = null;
                return;
            }

            if (visibleNoticeKind === 'live') {
                if (currentLiveAlertFingerprint) {
                    writeStringPref(LIVE_ALERT_LAST_SEEN_KEY, currentLiveAlertFingerprint);
                }
                notice.hidden = true;
                visibleNoticeKind = null;
            }
        });

        link.addEventListener('click', () => {
            if (visibleNoticeKind === 'resume') {
                clearState();
                return;
            }

            if (visibleNoticeKind === 'start') {
                if (currentStartAlertFingerprint) {
                    writeStringPref(ALERT_LAST_SEEN_KEY, currentStartAlertFingerprint);
                }
                return;
            }

            if (visibleNoticeKind === 'live') {
                if (currentLiveAlertFingerprint) {
                    writeStringPref(LIVE_ALERT_LAST_SEEN_KEY, currentLiveAlertFingerprint);
                }
            }
        });

        toggle.addEventListener('click', () => {
            const nextValue = !isStartAlertEnabled();
            writeBooleanPref(ALERT_PREF_KEY, nextValue);
            renderToggle();

            if (!nextValue && visibleNoticeKind === 'start') {
                notice.hidden = true;
                visibleNoticeKind = null;
            }

            if (!nextValue && visibleNoticeKind === 'live') {
                notice.hidden = true;
                visibleNoticeKind = null;
            }

            if (nextValue) {
                pollProjectionLiveAlert();
                pollProjectionStartAlert();
            }
        });

        window.addEventListener('storage', (event) => {
            if (event.key === STORAGE_KEY) {
                const state = readState();
                if (!state?.resumeReady) {
                    if (visibleNoticeKind === 'resume') {
                        notice.hidden = true;
                        visibleNoticeKind = null;
                    }
                    return;
                }

                showResumeNotice(state);
                return;
            }

            if (event.key === ALERT_PREF_KEY) {
                renderToggle();
                pollProjectionLiveAlert();
                pollProjectionStartAlert();
            }
        });

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                pollProjectionResume();
                pollProjectionLiveAlert();
                pollProjectionStartAlert();
            }
        });

        renderToggle();
        pollProjectionResume();
        pollProjectionLiveAlert();
        pollProjectionStartAlert();
        window.setInterval(pollProjectionResume, 15000);
        window.setInterval(pollProjectionLiveAlert, 15000);
        window.setInterval(pollProjectionStartAlert, 15000);
    })();
</script>
@endif