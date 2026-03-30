<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Realisateur;
use App\Models\Acteur;
use App\Models\Projection;

class PublicController extends Controller
{
    private const WATCH_WINDOW_BEFORE_MINUTES = 15;
    private const WATCH_WINDOW_AFTER_MINUTES = 20;

    public function home()
    {
        return view('public.home');
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
                  ->orWhere('salle', 'like', "%{$search}%")
                  ->orWhere('lieu', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $baseQuery->whereDate('date', $request->input('date'));
        }

        if ($request->filled('salle')) {
            $baseQuery->where('salle', $request->input('salle'));
        }

        $projections = $baseQuery->get();

        $now = Carbon::now();
        // Propose d'abord les prochaines séances; fallback sur les plus récentes.
        $recommandees = $projections
            ->filter(fn ($projection) => $projection->dateHeure()->gte($now))
            ->sortBy(fn ($projection) => $projection->dateHeure()->timestamp)
            ->take(3)
            ->values();

        if ($recommandees->isEmpty()) {
            $recommandees = $projections
                ->sortByDesc(fn ($projection) => $projection->dateHeure()->timestamp)
                ->take(3)
                ->values();
        }

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

        $salles = Projection::where('publie', true)
            ->select('salle')
            ->distinct()
            ->orderBy('salle')
            ->pluck('salle');

        return view('public.projections', compact('projections', 'salles', 'etat', 'recommandees'));
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

        $medias = collect($film?->galeries ?? [])
            ->filter(fn ($media) => $media->type_media === 'video')
            ->sortByDesc(fn ($media) => $media->date?->timestamp ?? 0)
            ->values()
            ->map(function ($media) use ($playbackOffsetSeconds) {
                $fichierUrl = $media->fichier ? asset('storage/'.$media->fichier) : null;
                $embedUrl = $this->toEmbedUrl($media->lien, $playbackOffsetSeconds);

                return [
                    'id' => $media->id,
                    'titre' => $media->titre,
                    'description' => $media->description,
                    'date' => $media->date,
                    'kind' => 'video',
                    'fichier_url' => $fichierUrl,
                    'lien' => $media->lien,
                    'embed_url' => $embedUrl,
                    'has_video_source' => !empty($fichierUrl) || !empty($media->lien),
                ];
            })
            ->filter(fn ($media) => $media['has_video_source'])
            ->values();

        $selectedMediaId = (int) $request->query('media');
        $selectedMedia = $medias->firstWhere('id', $selectedMediaId);

        $defaultMedia = $medias->first();

        $activeMedia = $selectedMedia ?? $defaultMedia;

        return view('public.visionnage', compact('projection', 'film', 'medias', 'activeMedia', 'playbackOffsetSeconds'));
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

        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&rel=0'.$youtubeStartParam;
        }

        if (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&rel=0'.$youtubeStartParam;
        }

        if (preg_match('/youtube\.com\/shorts\/([^?&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1&rel=0'.$youtubeStartParam;
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1&muted=1'.$vimeoStartFragment;
        }

        return null;
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
}