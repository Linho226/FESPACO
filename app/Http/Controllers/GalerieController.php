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
        // Validation commune des métadonnées du média.
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
            // Stocke le média uploadé sur le disque public.
            $validated['fichier'] = $request->file('fichier')->store('galerie', 'public');
        }

        Galerie::create($validated);

        // Si le média est rattaché à un film, on revient sur sa fiche.
        $redirect = $request->filled('film_id')
            ? route('admin.films.show', $request->input('film_id'))
            : route('admin.galeries.index');

        return redirect($redirect)->with('success', 'Média ajouté avec succès.');
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
