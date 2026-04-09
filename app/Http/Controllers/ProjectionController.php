<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Film;
use App\Models\Projection;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProjectionController extends Controller
{
    /**
     * Afficher la liste des projections (programme).
     */
    public function index(Request $request): View
    {
        // Charge le film et ses galeries (médias) pour éviter les N+1 queries.
        $query = Projection::with('film.galeries', 'attendanceRecord')->orderBy('date')->orderBy('heure');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('film', fn($q) => $q->where('titre', 'like', "%$search%"))
                  ->orWhere('lieu', 'like', "%$search%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        $projections = $query->paginate(15);

        // Projections imminentes (dans moins de 24h) et en cours
        $alertes = Projection::with('film.galeries')
            ->get()
            ->filter(fn($p) => $p->approcheImminente() || $p->estEnCours());

        return view('admin.projections.index', compact('projections', 'alertes'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create(): View
    {
        $films = Film::orderBy('titre')->get();
        return view('admin.projections.create', compact('films'));
    }

    /**
     * Enregistrer une nouvelle projection.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'date'    => 'required|date',
            'heure'   => 'required|date_format:H:i',
            'lieu'    => 'required|string|max:255',
            'notes'   => 'nullable|string',
            'publie'  => 'boolean',
            'media_selection_mode' => 'nullable|in:all,specific',
            'selected_media_ids'   => 'nullable|array',
            'selected_media_ids.*' => 'integer|exists:galeries,id',
        ]);

        // Normalise la checkbox en booléen fiable (true/false).
        $validated['publie'] = $request->boolean('publie');
        $validated['salle'] = 'Direct';

        // Traite la sélection des médias
        $this->processMediaSelection($validated);

        // Empêche deux projections qui se chevauchent dans la même salle.
        if ($conflit = $this->detectConflitSalle($validated)) {
            return back()
                ->withInput()
                ->withErrors([
                    'heure' => "Conflit de programmation : une diffusion « {$conflit->film->titre} » occupe déjà ce créneau.",
                ]);
        }

        Projection::create($validated);

        return redirect()->route('admin.projections.index')
            ->with('success', 'Projection ajoutée avec succès !');
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Projection $projection): View
    {
        $films = Film::orderBy('titre')->get();
        return view('admin.projections.edit', compact('projection', 'films'));
    }

    /**
     * Mettre à jour une projection.
     */
    public function update(Request $request, Projection $projection): RedirectResponse
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'date'    => 'required|date',
            'heure'   => 'required|date_format:H:i',
            'lieu'    => 'required|string|max:255',
            'notes'   => 'nullable|string',
            'publie'  => 'boolean',
            'media_selection_mode' => 'nullable|in:all,specific',
            'selected_media_ids'   => 'nullable|array',
            'selected_media_ids.*' => 'integer|exists:galeries,id',
        ]);

        $validated['publie'] = $request->boolean('publie');
        $validated['salle'] = 'Direct';

        // Traite la sélection des médias
        $this->processMediaSelection($validated);

        // Ignore la projection courante lors du contrôle de conflit en édition.
        if ($conflit = $this->detectConflitSalle($validated, $projection->id)) {
            return back()
                ->withInput()
                ->withErrors([
                    'heure' => "Conflit de programmation : une diffusion « {$conflit->film->titre} » occupe déjà ce créneau.",
                ]);
        }

        $projection->update($validated);

        return redirect()->route('admin.projections.index')
            ->with('success', 'Projection modifiée avec succès !');
    }

    /**
     * Supprimer une projection.
     */
    public function destroy(Projection $projection): RedirectResponse
    {
        $projection->delete();

        return redirect()->route('admin.projections.index')
            ->with('success', 'Projection supprimée avec succès !');
    }

    /**
     * Démarrer une projection manuellement.
     */
    public function demarrer(Projection $projection): RedirectResponse
    {
        if ($projection->estTerminee()) {
            return redirect()->route('admin.projections.index')
                ->with('success', "La projection « {$projection->film->titre} » est déjà terminée.");
        }

        $now = now();

        $debutAt = $projection->debut_at;
        if ($debutAt === null) {
            // Si l'horaire prévu est déjà passé, on cale le départ sur l'horaire officiel.
            $debutAt = $now->copy()->gte($projection->dateHeure())
                ? $projection->dateHeure()
                : $now->copy();
        }

        $pauseTotal = (int) $projection->pause_total_seconds;
        if ($projection->fin_at !== null) {
            // Reprise après pause: cumule la durée de pause écoulée.
            $pauseTotal += max(0, $projection->fin_at->diffInSeconds($now, false));
        }

        $projection->update([
            'debut_at' => $debutAt,
            'fin_at' => null,
            'pause_total_seconds' => $pauseTotal,
        ]);

        return redirect()->route('admin.projections.index')
            ->with('success', "Projection « {$projection->film->titre} » démarrée !");
    }

    /**
     * Arrêter une projection manuellement.
     */
    public function arreter(Projection $projection): RedirectResponse
    {
        $validated = request()->validate([
            'spectators_count' => ['nullable', 'integer', 'min:0'],
            'available_seats' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($projection->estTerminee()) {
            return redirect()->route('admin.projections.index')
                ->with('success', "La projection « {$projection->film->titre} » est déjà terminée.");
        }

        if ($projection->fin_at !== null) {
            return redirect()->route('admin.projections.index')
                ->with('success', "La projection « {$projection->film->titre} » est déjà en pause.");
        }

        if ($projection->debut_at === null) {
            // Définit un début implicite si on met en pause sans démarrage explicite.
            $projection->debut_at = now()->gte($projection->dateHeure())
                ? $projection->dateHeure()
                : now();
        }

        $projection->update(['fin_at' => now()]);

        $record = AttendanceRecord::firstOrNew([
            'projection_id' => $projection->id,
        ]);

        $spectators = (int) ($validated['spectators_count'] ?? $record->spectators_count ?? 0);
        $availableSeats = (int) ($validated['available_seats'] ?? $record->available_seats ?? 0);
        $totalSeats = $spectators + $availableSeats;
        $occupancyRate = $totalSeats > 0 ? round(($spectators / $totalSeats) * 100, 2) : 0;

        $record->spectators_count = $spectators;
        $record->available_seats = $availableSeats;
        $record->occupancy_rate = $occupancyRate;
        $record->save();

        return redirect()->route('admin.projections.index')
            ->with('success', "Projection « {$projection->film->titre} » mise en pause. Fréquentation enregistrée.");
    }

    /**
     * Visionnage privé d'une projection pour l'admin.
     */
    public function visionner(Request $request, Projection $projection): View|RedirectResponse
    {
        $projection->load('film.galeries');

        if (!$this->projectionHasWatchableMedia($projection)) {
            return redirect()->route('admin.projections.index')
                ->with('warning', 'Aucun média vidéo exploitable n’est disponible pour cette projection.');
        }

        if ($projection->estTerminee()) {
            return redirect()->route('admin.projections.index')
                ->with('warning', "La projection « {$projection->getTitreAffiche()} » est déjà terminée.");
        }

        if (!$projection->estEnCours() && !$projection->estArreteeManuellement()) {
            return redirect()->route('admin.projections.index')
                ->with('warning', "La projection « {$projection->getTitreAffiche()} » n’a pas encore commencé.");
        }

        $projection->registerActiveViewer((int) $request->user()->id);

        $film = $projection->film;
        $playbackOffsetSeconds = max(0, (int) $projection->tempsEcouleSecondes());

        $allMedias = collect($film?->galeries ?? [])
            ->filter(fn ($media) => $media->type_media === 'video')
            ->sortBy(fn ($media) => $media->created_at?->timestamp ?? $media->id)
            ->values();

        if ($projection->media_selection_mode === 'specific' && !empty($projection->selected_media_ids)) {
            $selectedIds = $projection->selected_media_ids;
            $medias = $allMedias
                ->whereIn('id', $selectedIds)
                ->values()
                ->sort(function ($a, $b) use ($selectedIds) {
                    return array_search($a->id, $selectedIds) <=> array_search($b->id, $selectedIds);
                })
                ->values();
        } else {
            $medias = $allMedias;
        }

        $medias = $medias
            ->map(function ($media) {
                $fichierUrl = $media->fichier ? asset('storage/'.$media->fichier) : null;
                $embedUrl = $this->toEmbedUrl($media->lien, 0);
                $embedProvider = $this->detectEmbedProvider($media->lien);

                return [
                    'id' => $media->id,
                    'titre' => $media->titre,
                    'description' => $media->description,
                    'date' => $media->date,
                    'duree_secondes' => (int) ($media->duree_secondes ?? 0),
                    'kind' => 'video',
                    'fichier_url' => $fichierUrl,
                    'lien' => $media->lien,
                    'embed_url' => $embedUrl,
                    'embed_provider' => $embedProvider,
                    'has_video_source' => !empty($fichierUrl) || !empty($media->lien),
                ];
            })
            ->filter(fn ($media) => $media['has_video_source'])
            ->values();

        $activeMedia = null;
        $playbackOffsetInActiveMedia = 0;
        $autoNext = $request->boolean('autonext');
        $fromMediaId = (int) $request->query('from_media', 0);

        if ($medias->isNotEmpty() && $autoNext && $fromMediaId > 0) {
            $fromIndex = $medias->search(fn ($media) => (int) ($media['id'] ?? 0) === $fromMediaId);

            if ($fromIndex !== false) {
                $nextMedia = $medias->get($fromIndex + 1);
                if ($nextMedia) {
                    $activeMedia = $nextMedia;
                    $playbackOffsetInActiveMedia = 0;
                }
            }
        }

        if (!$activeMedia && $medias->isNotEmpty()) {
            $elapsed = max(0, $playbackOffsetSeconds);
            $cursor = 0;

            foreach ($medias as $media) {
                $duration = max(0, (int) ($media['duree_secondes'] ?? 0));

                if ($duration <= 0) {
                    $activeMedia = $media;
                    $playbackOffsetInActiveMedia = 0;
                    break;
                }

                if ($elapsed < ($cursor + $duration)) {
                    $activeMedia = $media;
                    $playbackOffsetInActiveMedia = max(0, $elapsed - $cursor);
                    break;
                }

                $cursor += $duration;
            }

            if (!$activeMedia) {
                $activeMedia = $medias->last();
                $lastDuration = max(0, (int) ($activeMedia['duree_secondes'] ?? 0));
                $playbackOffsetInActiveMedia = $lastDuration > 0 ? max(0, $lastDuration - 1) : 0;
            }
        }

        if ($activeMedia && !empty($activeMedia['lien'])) {
            $activeMedia['embed_url'] = $this->toEmbedUrl($activeMedia['lien'], $playbackOffsetInActiveMedia);
        }

        return view('public.visionnage', [
            'projection' => $projection,
            'film' => $film,
            'medias' => $medias,
            'activeMedia' => $activeMedia,
            'playbackOffsetSeconds' => $playbackOffsetInActiveMedia,
            'showPublicChrome' => false,
            'backUrl' => route('admin.projections.index'),
            'statusUrl' => route('admin.projections.status', $projection),
            'finishedProjectionUrl' => route('admin.projections.index'),
            'projectionWatchUrl' => route('admin.projections.visionner', $projection),
        ]);
    }

    /**
     * Statut temps réel d'une projection pour le lecteur admin.
     */
    public function projectionStatus(Request $request, Projection $projection): JsonResponse
    {
        $state = $this->getProjectionWatchState($projection);

        if ($state['can_watch'] && $request->user()) {
            $projection->registerActiveViewer((int) $request->user()->id);
        }

        $etat = $projection->etat();
        $pausedSince = $projection->estArreteeManuellement() && $projection->fin_at
            ? $projection->fin_at->copy()
            : null;

        return response()->json([
            'can_watch' => $state['can_watch'],
            'message' => $state['message'],
            'status' => $etat['label'],
            'badge' => $etat['badge'],
            'icon' => $etat['icon'],
            'finished_redirect_url' => $projection->estTerminee()
                ? route('admin.projections.index')
                : null,
            'paused_since' => $pausedSince?->toIso8601String(),
            'paused_elapsed_seconds' => $pausedSince ? $pausedSince->diffInSeconds(now()) : null,
        ]);
    }

    private function detectConflitSalle(array $data, ?int $excludeId = null): ?Projection
    {
        // Calcule l'intervalle horaire de la projection à créer/modifier.
        /** @var Film|null $film */
        $film = Film::find($data['film_id']);
        $dureeCouranteSecondes = $film ? $film->dureeSecondesReelle() : 60;

        $debutCourant = Carbon::parse($data['date'].' '.$data['heure']);
        $finCourante = $debutCourant->copy()->addSeconds($dureeCouranteSecondes);

        $query = Projection::with('film')
            ->whereDate('date', $data['date']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        /** @var \Illuminate\Support\Collection<int, Projection> $existantes */
        $existantes = $query->get();

        foreach ($existantes as $projection) {
            $dureeExistanteSecondes = $projection->film ? $projection->film->dureeSecondesReelle() : 60;
            $debutExistant = $projection->dateHeure();
            $finExistante = $debutExistant->copy()->addSeconds($dureeExistanteSecondes);

            // Chevauchement strict entre deux intervalles [debut, fin).
            $overlap = $debutCourant->lt($finExistante) && $finCourante->gt($debutExistant);

            if ($overlap) {
                return $projection;
            }
        }

        return null;
    }

    /**
     * Process media selection: convert from form data to database format.
     * If film has multiple media:
     *   - Mode 'all': stores all media in order (selected_media_ids = null, mode = 'all')
     *   - Mode 'specific': stores only selected media (selected_media_ids = array, mode = 'specific')
     * If film has 1 media: defaults to 'all' (no selection needed)
     */
    private function processMediaSelection(array &$data): void
    {
        /** @var Film|null $film */
        $film = Film::find($data['film_id']);
        if (!$film) {
            $data['media_selection_mode'] = 'all';
            $data['selected_media_ids'] = null;
            return;
        }

        $mediaCount = $film->galeries()->count();

        // If only 1 media or none, default to 'all'
        if ($mediaCount < 2) {
            $data['media_selection_mode'] = 'all';
            $data['selected_media_ids'] = null;
            return;
        }

        // Multiple media exist
        $mode = $data['media_selection_mode'] ?? 'all';
        $data['media_selection_mode'] = in_array($mode, ['all', 'specific']) ? $mode : 'all';

        if ($mode === 'all') {
            // Store all media IDs in order  
            $allMediaIds = $film->galeries()
                ->orderBy('created_at', 'desc')
                ->pluck('id')
                ->toArray();
            $data['selected_media_ids'] = $allMediaIds;
        } else {
            // Store selected media IDs as provided
            $data['selected_media_ids'] = $data['selected_media_ids'] ?? [];
        }
    }

    private function toEmbedUrl(?string $url, int $startAtSeconds = 0): ?string
    {
        if (!$url) {
            return null;
        }

        $startAtSeconds = max(0, $startAtSeconds);
        $youtubeStartParam = $startAtSeconds > 0 ? '&start='.$startAtSeconds : '';
        $vimeoStartFragment = $startAtSeconds > 0 ? '#t='.$startAtSeconds.'s' : '';
        $origin = urlencode(request()->getSchemeAndHttpHost());
        $youtubeBaseParams = 'autoplay=1&mute=1&rel=0&controls=0&disablekb=1&modestbranding=1&playsinline=1&fs=0&enablejsapi=1&origin='.$origin;
        $vimeoBaseParams = 'autoplay=1&muted=1&controls=0&keyboard=0&api=1&player_id=projection-embed-player';

        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?'.$youtubeBaseParams.$youtubeStartParam;
        }

        if (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?'.$youtubeBaseParams.$youtubeStartParam;
        }

        if (preg_match('/youtube\.com\/shorts\/([^?&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?'.$youtubeBaseParams.$youtubeStartParam;
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1] . '?'.$vimeoBaseParams.$vimeoStartFragment;
        }

        return null;
    }

    private function detectEmbedProvider(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url)
            || preg_match('/youtu\.be\/([^?&]+)/', $url)
            || preg_match('/youtube\.com\/shorts\/([^?&]+)/', $url)) {
            return 'youtube';
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $url)) {
            return 'vimeo';
        }

        return 'other';
    }

    private function getProjectionWatchState(Projection $projection): array
    {
        $filmTitle = $projection->film?->titre ?? 'cette projection';

        if ($projection->estEnCours()) {
            return [
                'can_watch' => true,
                'message' => null,
            ];
        }

        if ($projection->estArreteeManuellement()) {
            return [
                'can_watch' => false,
                'message' => "La projection « {$filmTitle} » est actuellement en pause et reprendra dans quelques minutes.",
            ];
        }

        if ($projection->estTerminee()) {
            return [
                'can_watch' => false,
                'message' => "La projection « {$filmTitle} » est terminée.",
            ];
        }

        return [
            'can_watch' => false,
            'message' => "La projection « {$filmTitle} » n’a pas encore commencé.",
        ];
    }

    private function projectionHasWatchableMedia(Projection $projection): bool
    {
        $film = $projection->film;

        if (!$film) {
            return false;
        }

        $allMedias = collect($film->galeries ?? [])
            ->filter(fn ($media) => $media->type_media === 'video')
            ->filter(fn ($media) => !empty($media->fichier) || !empty($media->lien))
            ->values();

        if ($projection->media_selection_mode === 'specific' && !empty($projection->selected_media_ids)) {
            return $allMedias->whereIn('id', $projection->selected_media_ids)->isNotEmpty();
        }

        return $allMedias->isNotEmpty();
    }
}

