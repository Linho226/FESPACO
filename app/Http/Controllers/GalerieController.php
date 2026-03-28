<?php

namespace App\Http\Controllers;

use App\Models\Galerie;
use Illuminate\Http\Request;

class GalerieController extends Controller
{
    /**
     * Affiche la liste des médias (admin).
     */
    public function index()
    {
        $galeries = Galerie::orderByDesc('date')->paginate(12);
        return view('galeries.index', compact('galeries'));
    }

    /**
     * Affiche le formulaire de création (admin).
     */
    public function create()
    {
        return view('galeries.create');
    }

    /**
     * Enregistre un nouveau média (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type_media' => 'required|in:image,video',
            'fichier' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,webm|max:51200',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);
        // Stockage du fichier
        $path = $request->file('fichier')->store('galerie', 'public');
        $validated['fichier'] = $path;
        Galerie::create($validated);
        return redirect()->route('admin.galeries.index')->with('success', 'Média ajouté avec succès.');
    }

    /**
     * Affiche le formulaire d’édition (admin).
     */
    public function edit(Galerie $galerie)
    {
        return view('galeries.edit', compact('galerie'));
    }

    /**
     * Met à jour un média (admin).
     */
    public function update(Request $request, Galerie $galerie)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'type_media' => 'required|in:image,video',
            'fichier' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,webm|max:51200',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);
        // Si un nouveau fichier est uploadé, on le stocke
        if ($request->hasFile('fichier')) {
            $path = $request->file('fichier')->store('galerie', 'public');
            $validated['fichier'] = $path;
        } else {
            unset($validated['fichier']);
        }
        $galerie->update($validated);
        return redirect()->route('admin.galeries.index')->with('success', 'Média modifié avec succès.');
    }

    /**
     * Supprime un média (admin).
     */
    public function destroy(Galerie $galerie)
    {
        $galerie->delete();
        return redirect()->route('admin.galeries.index')->with('success', 'Média supprimé.');
    }

    /**
     * Affiche la galerie publique.
     */
    public function publicIndex()
    {
        $galeries = Galerie::orderByDesc('date')->paginate(20);
        return view('public.galerie', compact('galeries'));
    }

    /**
     * Affiche le détail d’un média public.
     */
    public function show(Galerie $galerie)
    {
        return view('public.galerie_show', compact('galerie'));
    }
}