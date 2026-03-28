<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Projection;
use App\Models\AttendanceRecord;
use App\Models\Actualite;
use App\Models\Galerie;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord admin avec statistiques.
     */
    public function index(): View
    {
        // Statistiques générales
        $stats = [
            'total_films' => Film::count(),
            'total_projections' => Projection::count(),
            'total_actualites' => Actualite::count(),
            'total_galeries' => Galerie::count(),
            
            // Projections en cours et à venir
            'projections_today' => Projection::whereDate('date', today())->count(),
            'projections_this_week' => Projection::whereBetween('date', [today(), today()->addWeek()])->count(),
            'projections_this_month' => Projection::whereMonth('date', now()->month)->count(),
            
            // Films les plus projetés
            'most_projected_films' => Film::withCount('projections')
                ->orderByDesc('projections_count')
                ->limit(5)
                ->get(),
            
            // Fréquentation moyenne
            'average_occupancy' => AttendanceRecord::avg('occupancy_rate') ?? 0,
            'total_spectators' => AttendanceRecord::sum('spectators_count') ?? 0,
            'attendance_records_count' => AttendanceRecord::count(),
        ];

        // Projections à venir (prochains 7 jours)
        $upcoming_projections = Projection::whereBetween('date', [today(), today()->addWeek()])
            ->with('film')
            ->orderBy('date', 'asc')
            ->orderBy('heure', 'asc')
            ->limit(10)
            ->get();

        // Actualités récentes
        $recent_actualites = Actualite::latest('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'upcoming_projections', 'recent_actualites'));
    }
}
