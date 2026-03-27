<?php

namespace App\Http\Controllers;

use App\Models\Acteur;
use Illuminate\Http\Request;

class ActeurController extends Controller
{
    // Afficher tous les acteurs
    public function index()
    {
        $acteurs = Acteur::all();
        return view('admin.acteurs.index', compact('acteurs'));
    }

    // Afficher un acteur spécifique
    public function show($id)
    {
        $acteur = Acteur::findOrFail($id);
        return view('admin.acteurs.show', compact('acteur'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('admin.acteurs.create');
    }

    // Enregistrer un nouvel acteur
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
            $data['photo'] = $request->file('photo')->store('acteurs', 'public');
        }

        Acteur::create($data);

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur ajouté avec succès.');
    }

    // Afficher le formulaire d'édition
    public function edit($id)
    {
        $acteur = Acteur::findOrFail($id);
        return view('admin.acteurs.edit', compact('acteur'));
    }

    // Mettre à jour un acteur
    public function update(Request $request, $id)
    {
        $acteur = Acteur::findOrFail($id);
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
            $data['photo'] = $request->file('photo')->store('acteurs', 'public');
        }

        $acteur->update($data);

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur mis à jour avec succès.');
    }

    // Supprimer un acteur
    public function destroy($id)
    {
        $acteur = Acteur::findOrFail($id);
        $acteur->delete();
        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur supprimé avec succès.');
    }

    // Méthode de recherche (optionnelle)
    public function search(Request $request)
    {
        $query = $request->input('q');
        $result = Acteur::where('nom', 'like', "%$query%")
            ->orWhere('prenom', 'like', "%$query%")
            ->get();
        return view('admin.acteurs.index', ['acteurs' => $result]);
    }
}