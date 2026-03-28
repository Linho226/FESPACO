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

    public function realisateursActeurs()
    {
        $realisateurs = Realisateur::all();
        $acteurs = Acteur::all();
        return view('public.realisateurs-acteurs', compact('realisateurs', 'acteurs'));
    }

    public function projections(Request $request)
    {
        $baseQuery = Projection::with('film')
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

    public function visionner(Projection $projection)
    {
        if (!$projection->publie) {
            abort(404);
        }

        $projection->load('film');
        $film = $projection->film;

        $now = Carbon::now();
        $debutAutorise = $projection->dateHeure()->copy()->subMinutes(self::WATCH_WINDOW_BEFORE_MINUTES);
        $finAutorisee = $projection->finPrevue()->copy()->addMinutes(self::WATCH_WINDOW_AFTER_MINUTES);

        $accesAutorise = $projection->estEnCours() || $now->between($debutAutorise, $finAutorisee);

        if (!$accesAutorise) {
            return redirect()->route('public.projections')->with(
                'warning',
                "Le visionnage de « {$film->titre} » est disponible de "
                .$debutAutorise->format('d/m/Y H\\hi')." à "
                .$finAutorisee->format('d/m/Y H\\hi').'.'
            );
        }

        $videoUrl = null;

        if (!empty($film?->video)) {
            $videoUrl = asset('storage/'.$film->video);
        }

        if ($videoUrl === null && !empty($film?->video_files)) {
            $files = is_array($film->video_files) ? $film->video_files : json_decode($film->video_files, true);
            if (is_array($files) && !empty($files[0])) {
                $videoUrl = asset('storage/'.$files[0]);
            }
        }

        if ($videoUrl === null && !empty($film?->video_link)) {
            $videoUrl = $film->video_link;
        }

        if ($videoUrl === null && !empty($film?->video_links)) {
            $links = is_array($film->video_links) ? $film->video_links : json_decode($film->video_links, true);
            if (is_array($links) && !empty($links[0])) {
                $videoUrl = $links[0];
            }
        }

        return view('public.visionnage', compact('projection', 'film', 'videoUrl'));
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