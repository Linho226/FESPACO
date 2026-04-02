<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Film;
use App\Models\Projection;
use App\Models\AttendanceRecord;
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
        // Charge le film et ses galeries (médias) pour éviter les N+1 queries.
        $query = Projection::with('film.galeries', 'attendanceRecord')->orderBy('date')->orderBy('heure');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('film', fn($q) => $q->where('titre', 'like', "%$search%"))
                  ->orWhere('lieu', 'like', "%$search%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        $projections = $query->paginate(15);

        // Projections imminentes (dans moins de 24h) et en cours
        $alertes = Projection::with('film.galeries')
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
            'lieu'    => 'required|string|max:255',
            'notes'   => 'nullable|string',
            'publie'  => 'boolean',
            'media_selection_mode' => 'nullable|in:all,specific',
            'selected_media_ids'   => 'nullable|array',
            'selected_media_ids.*' => 'integer|exists:galeries,id',
        ]);

        // Normalise la checkbox en booléen fiable (true/false).
        $validated['publie'] = $request->boolean('publie');
        $validated['salle'] = 'Direct';

        // Traite la sélection des médias
        $this->processMediaSelection($validated);

        // Empêche deux projections qui se chevauchent dans la même salle.
        if ($conflit = $this->detectConflitSalle($validated)) {
            return back()
                ->withInput()
                ->withErrors([
                    'heure' => "Conflit de programmation : une diffusion « {$conflit->film->titre} » occupe déjà ce créneau.",
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
            'lieu'    => 'required|string|max:255',
            'notes'   => 'nullable|string',
            'publie'  => 'boolean',
            'media_selection_mode' => 'nullable|in:all,specific',
            'selected_media_ids'   => 'nullable|array',
            'selected_media_ids.*' => 'integer|exists:galeries,id',
        ]);

        $validated['publie'] = $request->boolean('publie');
        $validated['salle'] = 'Direct';

        // Traite la sélection des médias
        $this->processMediaSelection($validated);

        // Ignore la projection courante lors du contrôle de conflit en édition.
        if ($conflit = $this->detectConflitSalle($validated, $projection->id)) {
            return back()
                ->withInput()
                ->withErrors([
                    'heure' => "Conflit de programmation : une diffusion « {$conflit->film->titre} » occupe déjà ce créneau.",
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
        $validated = request()->validate([
            'spectators_count' => ['nullable', 'integer', 'min:0'],
            'available_seats' => ['nullable', 'integer', 'min:0'],
        ]);

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

        $record = AttendanceRecord::firstOrNew([
            'projection_id' => $projection->id,
        ]);

        $spectators = (int) ($validated['spectators_count'] ?? $record->spectators_count ?? 0);
        $availableSeats = (int) ($validated['available_seats'] ?? $record->available_seats ?? 0);
        $totalSeats = $spectators + $availableSeats;
        $occupancyRate = $totalSeats > 0 ? round(($spectators / $totalSeats) * 100, 2) : 0;

        $record->spectators_count = $spectators;
        $record->available_seats = $availableSeats;
        $record->occupancy_rate = $occupancyRate;
        $record->save();

        return redirect()->route('admin.projections.index')
            ->with('success', "Projection « {$projection->film->titre} » mise en pause. Fréquentation enregistrée.");
    }

    private function detectConflitSalle(array $data, ?int $excludeId = null): ?Projection
    {
        // Calcule l'intervalle horaire de la projection à créer/modifier.
        $film = Film::find($data['film_id']);
        $dureeCouranteSecondes = $film ? $film->dureeSecondesReelle() : 60;

        $debutCourant = Carbon::parse($data['date'].' '.$data['heure']);
        $finCourante = $debutCourant->copy()->addSeconds($dureeCouranteSecondes);

        $query = Projection::with('film')
            ->whereDate('date', $data['date']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existantes = $query->get();

        foreach ($existantes as $projection) {
            $dureeExistanteSecondes = $projection->film ? $projection->film->dureeSecondesReelle() : 60;
            $debutExistant = $projection->dateHeure();
            $finExistante = $debutExistant->copy()->addSeconds($dureeExistanteSecondes);

            // Chevauchement strict entre deux intervalles [debut, fin).
            $overlap = $debutCourant->lt($finExistante) && $finCourante->gt($debutExistant);

            if ($overlap) {
                return $projection;
            }
        }

        return null;
    }

    /**
     * Process media selection: convert from form data to database format.
     * If film has multiple media:
     *   - Mode 'all': stores all media in order (selected_media_ids = null, mode = 'all')
     *   - Mode 'specific': stores only selected media (selected_media_ids = array, mode = 'specific')
     * If film has 1 media: defaults to 'all' (no selection needed)
     */
    private function processMediaSelection(array &$data): void
    {
        $film = Film::find($data['film_id']);
        if (!$film) {
            $data['media_selection_mode'] = 'all';
            $data['selected_media_ids'] = null;
            return;
        }

        $mediaCount = $film->galeries()->count();

        // If only 1 media or none, default to 'all'
        if ($mediaCount < 2) {
            $data['media_selection_mode'] = 'all';
            $data['selected_media_ids'] = null;
            return;
        }

        // Multiple media exist
        $mode = $data['media_selection_mode'] ?? 'all';
        $data['media_selection_mode'] = in_array($mode, ['all', 'specific']) ? $mode : 'all';

        if ($mode === 'all') {
            // Store all media IDs in order  
            $allMediaIds = $film->galeries()
                ->orderBy('created_at', 'desc')
                ->pluck('id')
                ->toArray();
            $data['selected_media_ids'] = $allMediaIds;
        } else {
            // Store selected media IDs as provided
            $data['selected_media_ids'] = $data['selected_media_ids'] ?? [];
        }
    }
}

