<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Film;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Film::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('titre', 'like', "%$search%");
        }
        $films = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.films.index', compact('films'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type');
        if (!$type) {
            // Affiche la vue de choix du type de film
            return view('admin.films.choose_type');
        }
        return view('admin.films.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'annee_production' => 'required|integer|min:1900|max:' . (date('Y')+1),
            'pays' => 'required|string|max:100',
            'duree' => 'required|integer|min:1',
            'affiche' => 'nullable|image|max:2048',
            'realisateur' => 'required|string|max:255',
            'acteurs' => 'required|string',
            'categorie' => 'required|string|max:100',
            'type' => 'required|in:film,serie',
            'video' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-matroska|max:51200', // max 50 Mo
            'video_files.*' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-matroska|max:51200',
            'video_link' => 'nullable|url',
            'video_links' => 'nullable|string',
        ]);

        // Gestion de l'upload de l'affiche
        if ($request->hasFile('affiche')) {
            $path = $request->file('affiche')->store('affiches', 'public');
            $validated['affiche'] = $path;
        }

        // Gestion des vidéos et liens selon le type
        if ($request->input('type') === 'serie') {
            // Liens vidéos (un par ligne)
            $videoLinks = array_filter(array_map('trim', preg_split('/\r?\n/', $request->input('video_links', ''))));
            $validated['video_links'] = $videoLinks ? json_encode($videoLinks) : null;

            // Upload de plusieurs fichiers vidéos
            $videoFiles = [];
            if ($request->hasFile('video_files')) {
                foreach ($request->file('video_files') as $file) {
                    if ($file && $file->isValid()) {
                        $videoFiles[] = $file->store('videos', 'public');
                    }
                }
            }
            $validated['video_files'] = $videoFiles ? json_encode($videoFiles) : null;
            // On ne gère pas le champ 'video' simple pour les séries
            unset($validated['video']);
        } else {
            // Film complet : un seul fichier vidéo et/ou un lien externe
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('videos', 'public');
                $validated['video'] = $videoPath;
            }
            $validated['video_link'] = $request->input('video_link');
            // On ne gère pas les champs multiples pour un film
            $validated['video_links'] = null;
            $validated['video_files'] = null;
        }

        Film::create($validated);
        return redirect()->route('admin.films.index')->with('success', 'Film ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $film = Film::findOrFail($id);
        return view('admin.films.show', compact('film'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $film = Film::findOrFail($id);
        return view('admin.films.edit', compact('film'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'annee_production' => 'required|integer|min:1900|max:' . (date('Y')+1),
            'pays' => 'required|string|max:100',
            'duree' => 'required|integer|min:1',
            'affiche' => 'nullable|image|max:2048',
            'realisateur' => 'required|string|max:255',
            'acteurs' => 'required|string',
            'categorie' => 'required|string|max:100',
            'type' => 'required|in:film,serie',
            'video' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-matroska|max:51200',
            'video_files.*' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-matroska|max:51200',
            'video_link' => 'nullable|url',
            'video_links' => 'nullable|string',
        ]);

        if ($request->hasFile('affiche')) {
            $path = $request->file('affiche')->store('affiches', 'public');
            $validated['affiche'] = $path;
        }

        if ($request->input('type') === 'serie') {
            // Liens vidéos (un par ligne)
            $videoLinks = array_filter(array_map('trim', preg_split('/\r?\n/', $request->input('video_links', ''))));
            $validated['video_links'] = $videoLinks ? json_encode($videoLinks) : null;

            // Upload de plusieurs fichiers vidéos (ajout aux existants)
            $videoFiles = $film->video_files ? json_decode($film->video_files, true) : [];
            if ($request->hasFile('video_files')) {
                foreach ($request->file('video_files') as $file) {
                    if ($file && $file->isValid()) {
                        $videoFiles[] = $file->store('videos', 'public');
                    }
                }
            }
            $validated['video_files'] = $videoFiles ? json_encode($videoFiles) : null;
            // On ne gère pas le champ 'video' simple pour les séries
            unset($validated['video']);
            $validated['video_link'] = null;
        } else {
            // Film complet : un seul fichier vidéo et/ou un lien externe
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('videos', 'public');
                $validated['video'] = $videoPath;
            }
            $validated['video_link'] = $request->input('video_link');
            // On ne gère pas les champs multiples pour un film
            $validated['video_links'] = null;
            $validated['video_files'] = null;
        }

        $film->update($validated);
        return redirect()->route('admin.films.index')->with('success', 'Film modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $film = Film::findOrFail($id);
        $film->delete();
        return redirect()->route('admin.films.index')->with('success', 'Film supprimé avec succès !');
    }
}