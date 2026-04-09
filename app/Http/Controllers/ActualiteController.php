<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActualiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        // Charge l'auteur en eager loading pour éviter les requêtes N+1 dans la liste.
        $query = Actualite::with('auteur')->orderByDesc('date_publication');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('titre', 'like', "%{$search}%")
                    ->orWhere('contenu', 'like', "%{$search}%")
                    ->orWhereHas('auteur', function ($authorQuery) use ($search) {
                        $authorQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $actualites = $query->paginate(10)->withQueryString();

        return view('admin.actualites.index', compact('actualites', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.actualites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation centralisée des champs du formulaire de publication.
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'date_publication' => 'required|date',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Stocke l'image sur le disque public pour l'affichage côté front.
            $imagePath = $request->file('image')->store('actualites', 'public');
        }

        $actualite = Actualite::create([
            'titre' => $validated['titre'],
            'contenu' => $validated['contenu'],
            'image' => $imagePath,
            'date_publication' => $validated['date_publication'],
            // Associe automatiquement l'actualité à l'utilisateur connecté.
            'auteur_id' => auth()->id(),
        ]);

        return redirect()->route('admin.actualites.index')->with('success', 'Actualité publiée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Actualite $actualite)
    {
        $actualite->load('auteur');
        return view('admin.actualites.show', compact('actualite'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actualite $actualite)
    {
        return view('admin.actualites.edit', compact('actualite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Actualite $actualite)
    {
        // Même règles de validation que la création pour garder la cohérence.
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'date_publication' => 'required|date',
        ]);

        $data = [
            'titre' => $validated['titre'],
            'contenu' => $validated['contenu'],
            'date_publication' => $validated['date_publication'],
        ];

        if ($request->hasFile('image')) {
            // Remplace uniquement l'image quand un nouveau fichier est fourni.
            $data['image'] = $request->file('image')->store('actualites', 'public');
        }

        $actualite->update($data);

        return redirect()->route('admin.actualites.index')->with('success', 'Actualité modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actualite $actualite)
    {
        // Nettoie le fichier image avant suppression de l'enregistrement.
        if ($actualite->image) {
            Storage::disk('public')->delete($actualite->image);
        }
        $actualite->delete();
        return redirect()->route('admin.actualites.index')->with('success', 'Actualité supprimée.');
    }
}