<?php

namespace App\Http\Controllers;

use App\Models\Acteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActeurController extends Controller
{
    public function index(Request $request)
    {
        $query = Acteur::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('nationalite', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $acteurs = $query->orderBy('nom')->orderBy('prenom')->paginate(12)->withQueryString();

        return view('admin.acteurs.index', compact('acteurs'));
    }

    public function show(Acteur $acteur)
    {
        return view('admin.acteurs.show', compact('acteur'));
    }

    public function create()
    {
        return view('admin.acteurs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('acteurs', 'public');
        }

        Acteur::create($data);

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur ajouté avec succès.');
    }

    public function edit(Acteur $acteur)
    {
        return view('admin.acteurs.edit', compact('acteur'));
    }

    public function update(Request $request, Acteur $acteur)
    {
        $data = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('photo')) {
            if ($acteur->photo && Storage::disk('public')->exists($acteur->photo)) {
                Storage::disk('public')->delete($acteur->photo);
            }

            $data['photo'] = $request->file('photo')->store('acteurs', 'public');
        }

        $acteur->update($data);

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur mis à jour avec succès.');
    }

    public function destroy(Acteur $acteur)
    {
        if ($acteur->photo && Storage::disk('public')->exists($acteur->photo)) {
            Storage::disk('public')->delete($acteur->photo);
        }

        $acteur->delete();

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur supprimé avec succès.');
    }

    private function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'nationalite' => 'nullable|string|max:255',
            'biographie' => 'nullable|string|max:5000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'type' => 'nullable|string|max:255',
        ];
    }

    private function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'photo.image' => 'Le fichier photo doit être une image valide.',
            'photo.mimes' => 'Formats acceptés: jpeg, png, jpg, webp.',
            'photo.max' => 'La photo ne doit pas dépasser 3 Mo.',
        ];
    }
}