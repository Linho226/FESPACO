<?php

namespace App\Http\Controllers;

use App\Models\Realisateur;
use Illuminate\Http\Request;

class RealisateurController extends Controller
{
    // Afficher tous les réalisateurs
    public function index()
    {
        $realisateurs = Realisateur::all();
        return view('admin.Realisateurs.index', compact('realisateurs'));
    }

    // Afficher un réalisateur spécifique
    public function show($id)
    {
        $realisateur = Realisateur::findOrFail($id);
        return view('admin.Realisateurs.show', compact('realisateur'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('admin.Realisateurs.create');
    }

    // Enregistrer un nouveau réalisateur
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'nationalite' => 'nullable|string|max:255',
            'biographie' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('realisateurs', 'public');
        }

        Realisateur::create($data);

        return redirect()->route('admin.realisateurs.index')->with('success', 'Réalisateur ajouté avec succès.');
    }

    // Afficher le formulaire d'édition
    public function edit($id)
    {
        $realisateur = Realisateur::findOrFail($id);
        return view('admin.Realisateurs.edit', compact('realisateur'));
    }

    // Mettre à jour un réalisateur
    public function update(Request $request, $id)
    {
        $realisateur = Realisateur::findOrFail($id);
        $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'nationalite' => 'sometimes|nullable|string|max:255',
            'biographie' => 'sometimes|nullable|string',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'sometimes|nullable|string|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('realisateurs', 'public');
        }

        $realisateur->update($data);

        return redirect()->route('admin.realisateurs.index')->with('success', 'Réalisateur mis à jour avec succès.');
    }

    // Supprimer un réalisateur
    public function destroy($id)
    {
        $realisateur = Realisateur::findOrFail($id);
        $realisateur->delete();
        return redirect()->route('admin.realisateurs.index')->with('success', 'Réalisateur supprimé avec succès.');
    }

    // Recherche de réalisateurs
    public function search(Request $request)
    {
        $query = $request->input('q');
        $result = Realisateur::where('nom', 'like', "%$query%")
            ->orWhere('prenom', 'like', "%$query%")
            ->get();
        return view('admin.Realisateurs.index', ['realisateurs' => $result]);
    }
}