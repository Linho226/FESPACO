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
        $query  = Galerie::with('film')->orderByDesc('created_at');

        if ($filmId) {
            $query->where('film_id', $filmId);
        }

        $galeries = $query->paginate(12)->withQueryString();
        $films    = Film::orderBy('titre')->get();

        return view('galeries.index', compact('galeries', 'films', 'filmId'));
    }

    public function create(Request $request)
    {
        $films        = Film::orderBy('titre')->get();
        $selectedFilm = $request->query('film_id');
        return view('galeries.create', compact('films', 'selectedFilm'));
    }

    public function store(Request $request)
    {
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
            $validated['fichier'] = $request->file('fichier')->store('galerie', 'public');
        }

        Galerie::create($validated);

        $redirect = $request->filled('film_id')
            ? route('admin.films.show', $request->input('film_id'))
            : route('admin.galeries.index');

        return redirect($redirect)->with('success', 'Média ajouté avec succès.');
    }

    public function edit(Galerie $galerie)
    {
        $films = Film::orderBy('titre')->get();
        return view('galeries.edit', compact('galerie', 'films'));
    }

    public function update(Request $request, Galerie $galerie)
    {
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
        $embedUrl = $this->getEmbedUrl($galerie->lien);

        return view('galeries.play', compact('galerie', 'embedUrl'));
    }

    private function getEmbedUrl(?string $url): ?string
    {
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
