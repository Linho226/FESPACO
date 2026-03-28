<?php

namespace App\Http\Controllers;

use App\Models\Realisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealisateurController extends Controller
{
    public function index(Request $request)
    {
        $query = Realisateur::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('nationalite', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $realisateurs = $query->orderBy('nom')->orderBy('prenom')->paginate(12)->withQueryString();

        return view('admin.Realisateurs.index', compact('realisateurs'));
    }

    public function show(Realisateur $realisateur)
    {
        return view('admin.Realisateurs.show', compact('realisateur'));
    }

    public function create()
    {
        return view('admin.Realisateurs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('realisateurs', 'public');
        }

        Realisateur::create($data);

        return redirect()->route('admin.realisateurs.index')->with('success', 'Réalisateur ajouté avec succès.');
    }

    public function edit(Realisateur $realisateur)
    {
        return view('admin.Realisateurs.edit', compact('realisateur'));
    }

    public function update(Request $request, Realisateur $realisateur)
    {
        $data = $request->validate($this->rules(), $this->messages());

        if ($request->hasFile('photo')) {
            if ($realisateur->photo && Storage::disk('public')->exists($realisateur->photo)) {
                Storage::disk('public')->delete($realisateur->photo);
            }

            $data['photo'] = $request->file('photo')->store('realisateurs', 'public');
        }

        $realisateur->update($data);

        return redirect()->route('admin.realisateurs.index')->with('success', 'Réalisateur mis à jour avec succès.');
    }

    public function destroy(Realisateur $realisateur)
    {
        if ($realisateur->photo && Storage::disk('public')->exists($realisateur->photo)) {
            Storage::disk('public')->delete($realisateur->photo);
        }

        $realisateur->delete();

        return redirect()->route('admin.realisateurs.index')->with('success', 'Réalisateur supprimé avec succès.');
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