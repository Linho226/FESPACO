<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Realisateur;
use App\Models\Acteur;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', $request->input('search', '')));

        $query = Film::query();

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('titre', 'like', "%{$search}%")
                    ->orWhere('realisateur', 'like', "%{$search}%")
                    ->orWhere('acteurs', 'like', "%{$search}%")
                    ->orWhere('categorie', 'like', "%{$search}%")
                    ->orWhere('pays', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $films = $query->latest()->paginate(10)->withQueryString();

        return view('admin.films.index', compact('films', 'search'));
    }

    public function create()
    {
        $realisateurs = Realisateur::orderBy('nom')->orderBy('prenom')->get();
        $acteurs = Acteur::orderBy('nom')->orderBy('prenom')->get();
        return view('admin.films.create', compact('realisateurs', 'acteurs'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateFilm($request);

        if ($request->hasFile('affiche')) {
            $validated['affiche'] = $request->file('affiche')->store('affiches', 'public');
        }

        Film::create($validated);

        return redirect()->route('admin.films.index')->with('success', 'Film ajouté avec succès !');
    }

    public function show(Film $film)
    {
        return view('admin.films.show', compact('film'));
    }

    public function edit(Film $film)
    {
        $realisateurs = Realisateur::orderBy('nom')->orderBy('prenom')->get();
        $acteurs = Acteur::orderBy('nom')->orderBy('prenom')->get();
        return view('admin.films.edit', compact('film', 'realisateurs', 'acteurs'));
    }

    public function update(Request $request, Film $film)
    {
        $validated = $this->validateFilm($request);

        if ($request->hasFile('affiche')) {
            if ($film->affiche) {
                Storage::disk('public')->delete($film->affiche);
            }
            $validated['affiche'] = $request->file('affiche')->store('affiches', 'public');
        }

        $film->update($validated);

        return redirect()->route('admin.films.index')->with('success', 'Film modifié avec succès !');
    }

    public function destroy(Film $film)
    {
        if ($film->affiche) {
            Storage::disk('public')->delete($film->affiche);
        }

        $film->delete();

        return redirect()->route('admin.films.index')->with('success', 'Film supprimé avec succès !');
    }

    private function validateFilm(Request $request): array
    {
        return $request->validate([
            'titre'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'annee_production'  => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'pays'             => 'required|string|max:100',
            'duree'            => 'required|integer|min:1',
            'realisateur'      => 'required|string|max:255',
            'acteurs'          => 'required|string',
            'categorie'        => 'required|string|max:100',
            'affiche'          => 'nullable|image|max:4096',
        ]);
    }
}