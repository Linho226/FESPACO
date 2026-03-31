<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Realisateur;
use App\Models\Acteur;
use App\Models\Projection;
use App\Models\Actualite;
use App\Models\ContactMessage;

class PublicController extends Controller
{
    private const WATCH_WINDOW_BEFORE_MINUTES = 15;
    private const WATCH_WINDOW_AFTER_MINUTES = 20;

    public function home()
    {
        $filmsCount = Film::count();
        return view('public.home', compact('filmsCount'));
    }

    public function films()
    {
        $films = Film::orderBy('created_at', 'desc')->paginate(12); // récupère tous les films
        return view('public.films', compact('films')); // envoie à la vue
    }

    public function realisateursActeurs(Request $request)
    {
        $search = trim((string) $request->input('q', ''));
        $nationalite = trim((string) $request->input('nationalite', ''));
        $type = trim((string) $request->input('type', ''));
        $profil = $request->input('profil', 'tous');

        $showRealisateurs = in_array($profil, ['tous', 'realisateurs'], true);
        $showActeurs = in_array($profil, ['tous', 'acteurs'], true);

        $realisateursQuery = Realisateur::query()->orderBy('nom')->orderBy('prenom');
        $acteursQuery = Acteur::query()->orderBy('nom')->orderBy('prenom');

        if ($search !== '') {
            $realisateursQuery->where(function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('nationalite', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });

            $acteursQuery->where(function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('nationalite', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($nationalite !== '') {
            $realisateursQuery->where('nationalite', $nationalite);
            $acteursQuery->where('nationalite', $nationalite);
        }

        if ($type !== '') {
            $realisateursQuery->where('type', $type);
            $acteursQuery->where('type', $type);
        }

        if (!$showRealisateurs) {
            // Force une requête vide pour désactiver complètement ce bloc.
            $realisateursQuery->whereRaw('1 = 0');
        }

        if (!$showActeurs) {
            // Même stratégie côté acteurs selon le filtre de profil.
            $acteursQuery->whereRaw('1 = 0');
        }

        $nationalites = Realisateur::query()
            ->select('nationalite')
            ->whereNotNull('nationalite')
            ->where('nationalite', '!=', '')
            ->pluck('nationalite')
            ->merge(
                Acteur::query()
                    ->select('nationalite')
                    ->whereNotNull('nationalite')
                    ->where('nationalite', '!=', '')
                    ->pluck('nationalite')
            )
            ->unique()
            ->sort()
            ->values();

        $types = Realisateur::query()
            ->select('type')
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->pluck('type')
            ->merge(
                Acteur::query()
                    ->select('type')
                    ->whereNotNull('type')
                    ->where('type', '!=', '')
                    ->pluck('type')
            )
            ->unique()
            ->sort()
            ->values();

        $realisateurs = $realisateursQuery->paginate(8, ['*'], 'page_realisateurs')->withQueryString();
        $acteurs = $acteursQuery->paginate(8, ['*'], 'page_acteurs')->withQueryString();

        return view('public.realisateurs-acteurs', compact(
            'realisateurs',
            'acteurs',
            'search',
            'nationalite',
            'type',
            'profil',
            'nationalites',
            'types',
            'showRealisateurs',
            'showActeurs'
        ));
    }

    public function realisateur(Realisateur $realisateur)
    {
        return view('public.realisateur', compact('realisateur'));
    }

    public function acteur(Acteur $acteur)
    {
        return view('public.acteur', compact('acteur'));
    }

    public function projections(Request $request)
    {
        // Précharge uniquement les médias vidéo exploitables pour la page projections.
        $baseQuery = Projection::with(['film.galeries' => function ($query) {
                $query->where('type_media', 'video')
                    ->where(function ($q) {
                        $q->whereNotNull('fichier')
                            ->orWhere(function ($q2) {
                                $q2->whereNotNull('lien')
                                    ->where('lien', '!=', '');
                            });
                    });
            }])
            ->where('publie', true)
            ->orderBy('date')
            ->orderBy('heure');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('film', fn($f) => $f->where('titre', 'like', "%{$search}%"))
                  ->orWhere('lieu', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $baseQuery->whereDate('date', $request->input('date'));
        }

        $projections = $baseQuery->get();

        $etat = $request->input('etat', 'tous');
        // Filtre final en mémoire selon l'état calculé de chaque projection.
        $projections = $projections->filter(function ($projection) use ($etat) {
            return match ($etat) {
                'en_cours' => $projection->estEnCours(),
                'a_venir' => $projection->estAVenir(),
                'arretee' => $projection->estArreteeManuellement(),
                'terminee' => $projection->estTerminee(),
                default => true,
            };
        })->values();

        // La section du haut reste standard dès qu'il existe au moins une projection.
        // On y affiche jusqu'à 3 séances prioritaires puis on retire ces séances du programme du bas.
        $topProjections = $projections
            ->sortBy(function ($projection) {
                $priority = match (true) {
                    $projection->estEnCours() => 0,
                    $projection->estAVenir() => 1,
                    $projection->estArreteeManuellement() => 2,
                    default => 3,
                };

                return [$priority, $projection->dateHeure()->timestamp];
            })
            ->take(3)
            ->values();

        $topIds = $topProjections->pluck('id')->all();
        $remainingForProgram = $projections
            ->reject(fn ($projection) => in_array($projection->id, $topIds, true))
            ->values();

        $projectionsProgramme = $remainingForProgram
            ->sortBy(fn ($projection) => $projection->dateHeure()->timestamp)
            ->values();

        return view('public.projections', compact('projections', 'etat', 'topProjections', 'projectionsProgramme'));
    }

    public function visionner(Request $request, Projection $projection)
    {
        if (!$projection->publie) {
            abort(404);
        }

        $projection->load('film.galeries');
        $film = $projection->film;

        $watchState = $this->getProjectionWatchState($projection);

        if (!$watchState['can_watch']) {
            return redirect()->route('public.projections')->with('warning', $watchState['message']);
        }

        // Décalage utilisé pour synchroniser la lecture média avec l'avancement réel.
        $playbackOffsetSeconds = max(0, (int) $projection->tempsEcouleSecondes());

        // Filtrer les médias selon la sélection de la projection
        $allMedias = collect($film?->galeries ?? [])
            ->filter(fn ($media) => $media->type_media === 'video')
            ->sortBy(fn ($media) => $media->created_at?->timestamp ?? $media->id)
            ->values();

        // Si la projection a une sélection spécifique, utiliser cette sélection
        if ($projection->media_selection_mode === 'specific' && !empty($projection->selected_media_ids)) {
            $selectedIds = $projection->selected_media_ids;
            $medias = $allMedias
                ->whereIn('id', $selectedIds)
                ->values()
                // Respecter l'ordre de la sélection
                ->sort(function ($a, $b) use ($selectedIds) {
                    return array_search($a->id, $selectedIds) <=> array_search($b->id, $selectedIds);
                })
                ->values();
        } else {
            // Mode 'all' ou pas de sélection: utiliser tous les médias
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

        // Passage automatique interne: quand la vidéo finit, on force la suivante
        // sans exposer une sélection manuelle côté interface publique.
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

                // Si la durée n'est pas connue, on ne peut pas découper précisément la timeline.
                // On prend alors ce média comme média courant.
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
        ]);
    }

    public function projectionStatus(Projection $projection)
    {
        if (!$projection->publie) {
            abort(404);
        }

        $state = $this->getProjectionWatchState($projection);
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
            'paused_since' => $pausedSince?->toIso8601String(),
            'paused_elapsed_seconds' => $pausedSince ? $pausedSince->diffInSeconds(now()) : null,
        ]);
    }

    private function toEmbedUrl(?string $url, int $startAtSeconds = 0): ?string
    {
        if (!$url) {
            return null;
        }

        // Ajoute un point de départ pour aligner l'embed sur la timeline de projection.
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

        // Priorité des états: en cours > pause > terminée > à venir.
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
                'message' => "La projection « {$filmTitle} » est terminée. Merci d’avoir suivi cette séance.",
            ];
        }

        return [
            'can_watch' => false,
            'message' => "La projection « {$filmTitle} » n’a pas encore commencé. Merci de patienter encore un moment.",
        ];
    }

    public function actualites()
    {
        $actualites = \App\Models\Actualite::with('auteur')->orderByDesc('date_publication')->paginate(10);
        return view('public.actualites', compact('actualites'));
    }

        public function actualite(\App\Models\Actualite $actualite)
    {
        $actualite->load('auteur');
        return view('public.actualite', compact('actualite'));
    }

    public function galerie()
    {
        return view('public.galerie');
    }

    public function aPropos()
    {
        return view('public.a-propos');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:120',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        return redirect()->route('public.contact')->with('success', 'Votre message a bien ete envoye. L\'equipe du FESPACO vous repondra rapidement.');
    }
}