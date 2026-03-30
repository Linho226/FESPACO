<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Film;
use App\Models\Projection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectionController extends Controller
{
    /**
     * Afficher la liste des projections (programme).
     */
    public function index(Request $request): View
    {
        // Charge le film lié pour lister le programme sans requêtes N+1.
        $query = Projection::with('film')->orderBy('date')->orderBy('heure');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('film', fn($q) => $q->where('titre', 'like', "%$search%"))
                  ->orWhere('lieu', 'like', "%$search%")
                  ->orWhere('salle', 'like', "%$search%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        $projections = $query->paginate(15);

        // Projections imminentes (dans moins de 24h) et en cours
        $alertes = Projection::with('film')
            ->get()
            ->filter(fn($p) => $p->approcheImminente() || $p->estEnCours());

        return view('admin.projections.index', compact('projections', 'alertes'));
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create(): View
    {
        $films = Film::orderBy('titre')->get();
        return view('admin.projections.create', compact('films'));
    }

    /**
     * Enregistrer une nouvelle projection.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'date'    => 'required|date',
            'heure'   => 'required|date_format:H:i',
            'salle'   => 'required|string|max:100',
            'lieu'    => 'required|string|max:255',
            'notes'   => 'nullable|string',
            'publie'  => 'boolean',
        ]);

        // Normalise la checkbox en booléen fiable (true/false).
        $validated['publie'] = $request->boolean('publie');

        // Empêche deux projections qui se chevauchent dans la même salle.
        if ($conflit = $this->detectConflitSalle($validated)) {
            return back()
                ->withInput()
                ->withErrors([
                    'salle' => "Conflit de programmation : la salle est déjà occupée par « {$conflit->film->titre} » sur ce créneau.",
                ]);
        }

        Projection::create($validated);

        return redirect()->route('admin.projections.index')
            ->with('success', 'Projection ajoutée avec succès !');
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Projection $projection): View
    {
        $films = Film::orderBy('titre')->get();
        return view('admin.projections.edit', compact('projection', 'films'));
    }

    /**
     * Mettre à jour une projection.
     */
    public function update(Request $request, Projection $projection): RedirectResponse
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'date'    => 'required|date',
            'heure'   => 'required|date_format:H:i',
            'salle'   => 'required|string|max:100',
            'lieu'    => 'required|string|max:255',
            'notes'   => 'nullable|string',
            'publie'  => 'boolean',
        ]);

        $validated['publie'] = $request->boolean('publie');

        // Ignore la projection courante lors du contrôle de conflit en édition.
        if ($conflit = $this->detectConflitSalle($validated, $projection->id)) {
            return back()
                ->withInput()
                ->withErrors([
                    'salle' => "Conflit de programmation : la salle est déjà occupée par « {$conflit->film->titre} » sur ce créneau.",
                ]);
        }

        $projection->update($validated);

        return redirect()->route('admin.projections.index')
            ->with('success', 'Projection modifiée avec succès !');
    }

    /**
     * Supprimer une projection.
     */
    public function destroy(Projection $projection): RedirectResponse
    {
        $projection->delete();

        return redirect()->route('admin.projections.index')
            ->with('success', 'Projection supprimée avec succès !');
    }

    /**
     * Démarrer une projection manuellement.
     */
    public function demarrer(Projection $projection): RedirectResponse
    {
        if ($projection->estTerminee()) {
            return redirect()->route('admin.projections.index')
                ->with('success', "La projection « {$projection->film->titre} » est déjà terminée.");
        }

        $now = now();

        $debutAt = $projection->debut_at;
        if ($debutAt === null) {
            // Si l'horaire prévu est déjà passé, on cale le départ sur l'horaire officiel.
            $debutAt = $now->copy()->gte($projection->dateHeure())
                ? $projection->dateHeure()
                : $now->copy();
        }

        $pauseTotal = (int) $projection->pause_total_seconds;
        if ($projection->fin_at !== null) {
            // Reprise après pause: cumule la durée de pause écoulée.
            $pauseTotal += max(0, $projection->fin_at->diffInSeconds($now, false));
        }

        $projection->update([
            'debut_at' => $debutAt,
            'fin_at' => null,
            'pause_total_seconds' => $pauseTotal,
        ]);

        return redirect()->route('admin.projections.index')
            ->with('success', "Projection « {$projection->film->titre} » démarrée !");
    }

    /**
     * Arrêter une projection manuellement.
     */
    public function arreter(Projection $projection): RedirectResponse
    {
        if ($projection->estTerminee()) {
            return redirect()->route('admin.projections.index')
                ->with('success', "La projection « {$projection->film->titre} » est déjà terminée.");
        }

        if ($projection->fin_at !== null) {
            return redirect()->route('admin.projections.index')
                ->with('success', "La projection « {$projection->film->titre} » est déjà en pause.");
        }

        if ($projection->debut_at === null) {
            // Définit un début implicite si on met en pause sans démarrage explicite.
            $projection->debut_at = now()->gte($projection->dateHeure())
                ? $projection->dateHeure()
                : now();
        }

        $projection->update(['fin_at' => now()]);

        return redirect()->route('admin.projections.index')
            ->with('success', "Projection « {$projection->film->titre} » mise en pause.");
    }

    private function detectConflitSalle(array $data, ?int $excludeId = null): ?Projection
    {
        // Calcule l'intervalle horaire de la projection à créer/modifier.
        $film = Film::find($data['film_id']);
        $dureeCourante = max((int) ($film?->duree ?? 0), 1);

        $debutCourant = Carbon::parse($data['date'].' '.$data['heure']);
        $finCourante = $debutCourant->copy()->addMinutes($dureeCourante);

        $query = Projection::with('film')
            ->whereDate('date', $data['date'])
            ->whereRaw('LOWER(salle) = ?', [mb_strtolower(trim($data['salle']))]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existantes = $query->get();

        foreach ($existantes as $projection) {
            $dureeExistante = max((int) ($projection->film->duree ?? 0), 1);
            $debutExistant = $projection->dateHeure();
            $finExistante = $debutExistant->copy()->addMinutes($dureeExistante);

            // Chevauchement strict entre deux intervalles [debut, fin).
            $overlap = $debutCourant->lt($finExistante) && $finCourante->gt($debutExistant);

            if ($overlap) {
                return $projection;
            }
        }

        return null;
    }
}
