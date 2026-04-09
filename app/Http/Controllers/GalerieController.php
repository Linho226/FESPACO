<?php

namespace App\Http\Controllers;

use App\Models\Galerie;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GalerieController extends Controller
{
    public function index(Request $request)
    {
        $filmId = $request->input('film_id');
        $search = trim((string) $request->input('search', ''));

        // Charge le film lié pour l'affichage sans requêtes N+1.
        $query  = Galerie::with('film')->orderByDesc('created_at');

        if ($filmId) {
            $query->where('film_id', $filmId);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('type_media', 'like', "%{$search}%")
                    ->orWhereHas('film', function ($filmQuery) use ($search) {
                        $filmQuery->where('titre', 'like', "%{$search}%");
                    });
            });
        }

        $galeries = $query->paginate(12)->withQueryString();
        $films    = Film::orderBy('titre')->get();

        return view('admin.galeries.index', compact('galeries', 'films', 'filmId', 'search'));
    }

    public function create(Request $request)
    {
        $films        = Film::orderBy('titre')->get();
        $selectedFilm = $request->query('film_id');
        return view('admin.galeries.create', compact('films', 'selectedFilm'));
    }

    public function store(Request $request)
    {
        // Validation stricte : vidéo uniquement
        $validated = $request->validate([
            'film_id'     => 'nullable|exists:films,id',
            'titre'       => 'required|string|max:255',
            'type_media'  => 'required|in:video',
            'fichier'     => 'required_without:lien|nullable|file|mimes:jpeg,png,webp,mp4,avi,mov,webm|max:204800',
            'lien'        => 'required_without:fichier|nullable|url',
            'description' => 'nullable|string',
            'date'        => 'required|date',
        ]);

        $dureeSecondes = null;
        if ($request->hasFile('fichier')) {
            $validated['fichier'] = $request->file('fichier')->store('galerie', 'public');
            // Récupérer la durée de la vidéo avec getID3
            try {
                $getID3 = new \getID3;
                $filePath = Storage::disk('public')->path($validated['fichier']);
                $fileInfo = $getID3->analyze($filePath);
                if (!empty($fileInfo['playtime_seconds'])) {
                    $dureeSecondes = (int) round($fileInfo['playtime_seconds']);
                }
            } catch (\Exception $e) {
                // On ignore l'erreur, la durée ne sera pas mise à jour
            }
        }

        // Tenter de récupérer la durée depuis un lien YouTube ou Vimeo
        if (!$dureeSecondes && $request->filled('lien')) {
            $lien = $validated['lien'];

            // YouTube : youtu.be/ID ou youtube.com/watch?v=ID
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]{11})/', $lien, $m)) {
                try {
                    $body = Http::timeout(5)
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                        ->get('https://www.youtube.com/watch?v=' . $m[1])
                        ->body();
                    if (preg_match('/"lengthSeconds":"(\d+)"/', $body, $d)) {
                        $dureeSecondes = (int) $d[1];
                    }
                } catch (\Exception $e) {}
            }

            // Vimeo : vimeo.com/ID
            if (!$dureeSecondes && preg_match('/vimeo\.com\/(\d+)/', $lien, $m)) {
                try {
                    $data = Http::timeout(5)
                        ->get('https://vimeo.com/api/v2/video/' . $m[1] . '.json')
                        ->json();
                    if (!empty($data[0]['duration'])) {
                        $dureeSecondes = (int) $data[0]['duration'];
                    }
                } catch (\Exception $e) {}
            }
        }

        if ($dureeSecondes) {
            $validated['duree_secondes'] = $dureeSecondes;
        }

        $galerie = Galerie::create($validated);

        // Synchronise aussi la durée sur le film pour la rétrocompatibilité
        if ($galerie->film && $dureeSecondes) {
            $galerie->film->duree          = (int) round($dureeSecondes / 60);
            $galerie->film->duree_secondes = $dureeSecondes;
            $galerie->film->save();
        }

        $redirect = $request->filled('film_id')
            ? route('admin.films.show', $request->input('film_id'))
            : route('admin.galeries.index');

        return redirect($redirect)->with('success', 'Vidéo ajoutée avec succès.');
    }

    public function edit(Galerie $galerie)
    {
        $films = Film::orderBy('titre')->get();
        return view('admin.galeries.edit', compact('galerie', 'films'));
    }

    public function update(Request $request, Galerie $galerie)
    {
        // Même règles de validation que la création pour garder la cohérence.
        $validated = $request->validate([
            'film_id'     => 'nullable|exists:films,id',
            'titre'       => 'required|string|max:255',
            'type_media'  => 'required|in:image,video',
            'fichier'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,webm|max:204800',
            'lien'        => 'nullable|url',
            'description' => 'nullable|string',
            'date'        => 'required|date',
        ]);

        if ($request->hasFile('fichier')) {
            // Supprime l'ancien fichier pour éviter les médias orphelins.
            if ($galerie->fichier) {
                Storage::disk('public')->delete($galerie->fichier);
            }
            $validated['fichier'] = $request->file('fichier')->store('galerie', 'public');
        }

        $galerie->update($validated);

        return redirect()->route('admin.galeries.index')->with('success', 'Média modifié avec succès.');
    }

    public function destroy(Galerie $galerie)
    {
        // Nettoie le stockage avant suppression de l'entrée en base.
        if ($galerie->fichier) {
            Storage::disk('public')->delete($galerie->fichier);
        }
        $galerie->delete();
        return redirect()->back()->with('success', 'Média supprimé.');
    }

    public function publicIndex()
    {
        $selectedFilmId = request()->query('film_id');

        $query = Galerie::with('film')
            ->orderByRaw('film_id IS NULL')
            ->orderBy('film_id')
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        if (!empty($selectedFilmId)) {
            $query->where('film_id', $selectedFilmId);
        }

        $films = Film::query()
            ->orderBy('titre')
            ->get(['id', 'titre']);

        $galeries = $query->paginate(20)->withQueryString();

        return view('public.galerie', compact('galeries', 'films', 'selectedFilmId'));
    }

    public function show(Galerie $galerie)
    {
        if (!auth()->check()) {
            return redirect()->route('login', [
                'redirect' => route('galerie.show', $galerie, false),
            ])->with('status', 'Veuillez vous connecter pour ouvrir un element de la galerie.');
        }

        return view('public.galerie_show', compact('galerie'));
    }

    public function play(Galerie $galerie)
    {
        // Prépare un lien d'embed quand le fournisseur est supporté.
        $embedUrl = $this->getEmbedUrl($galerie->lien);

        return view('admin.galeries.play', compact('galerie', 'embedUrl'));
    }

    private function getEmbedUrl(?string $url): ?string
    {
        // Convertit des URL YouTube/Vimeo en URL intégrables iframe.
        if (!$url) {
            return null;
        }

        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        if (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        if (preg_match('/youtube\.com\/shorts\/([^?&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        return null;
    }
}