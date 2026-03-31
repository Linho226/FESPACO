<?php

namespace App\Http\Controllers;

use App\Models\Galerie;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalerieController extends Controller
{
    public function index(Request $request)
    {
        $filmId = $request->input('film_id');
        // Charge le film lié pour l'affichage sans requêtes N+1.
        $query  = Galerie::with('film')->orderByDesc('created_at');

        if ($filmId) {
            $query->where('film_id', $filmId);
        }

        $galeries = $query->paginate(12)->withQueryString();
        $films    = Film::orderBy('titre')->get();

        return view('admin.galeries.index', compact('galeries', 'films', 'filmId'));
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
            'fichier'     => 'required|file|mimes:mp4,avi,mov,webm|max:204800',
            'lien'        => 'nullable|url',
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

        $galerie = Galerie::create($validated);

        // Si le média est rattaché à un film et qu'on a la durée, on met à jour la durée du film (toujours, même si déjà renseignée)
        if ($galerie->film && $dureeSecondes) {
            $galerie->film->duree = (int) ceil($dureeSecondes / 60); // en minutes
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
        $galeries = Galerie::with('film')->orderByDesc('date')->paginate(20);
        return view('public.galerie', compact('galeries'));
    }

    public function show(Galerie $galerie)
    {
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