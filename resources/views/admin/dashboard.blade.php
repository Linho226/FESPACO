@extends('admin.layout')

@section('title', 'Tableau de Bord - Administration FESPACO')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header mb-4">
        <h1 class="display-5 fw-bold">📊 Tableau de Bord</h1>
        <p class="text-muted">Aperçu des statistiques du festival FESPACO</p>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon films">🎬</div>
                <div class="stat-content">
                    <h3>{{ $stats['total_films'] }}</h3>
                    <p>Films enregistrés</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon projections">📽️</div>
                <div class="stat-content">
                    <h3>{{ $stats['total_projections'] }}</h3>
                    <p>Projections totales</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon actualites">📰</div>
                <div class="stat-content">
                    <h3>{{ $stats['total_actualites'] }}</h3>
                    <p>Actualités publiées</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon galeries">🖼️</div>
                <div class="stat-content">
                    <h3>{{ $stats['total_galeries'] ?? \App\Models\Galerie::count() }}</h3>
                    <p>Médias en galerie</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">📅 Projections</h5>
                </div>
                <div class="card-body">
                    <div class="projection-period-row">
                        <div class="projection-period-item">
                            <span class="period-label">Aujourd'hui</span>
                            <span class="period-count">{{ $stats['projections_today'] }}</span>
                        </div>
                        <div class="projection-period-item">
                            <span class="period-label">Cette semaine</span>
                            <span class="period-count">{{ $stats['projections_this_week'] }}</span>
                        </div>
                        <div class="projection-period-item">
                            <span class="period-label">Ce mois</span>
                            <span class="period-count">{{ $stats['projections_this_month'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">👥 Fréquentation</h5>
                </div>
                <div class="card-body">
                    <div class="frequency-stat">
                        <div class="frequency-item">
                            <span class="frequency-label">Utilisateurs connectés pendant une projection en cours</span>
                            <span class="frequency-value">{{ $stats['connected_users_during_projections'] }}</span>
                            <small class="text-muted d-block mt-1">{{ $stats['ongoing_projections_count'] }} projection(s) en cours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">🏆 Films les plus projetés</h5>
                </div>
                <div class="card-body p-0">
                    @if($stats['most_projected_films']->count() > 0)
                        <div class="most-projected-list">
                            @foreach($stats['most_projected_films'] as $index => $film)
                                <div class="projected-item {{ $index < count($stats['most_projected_films']) - 1 ? 'border-bottom' : '' }}">
                                    <div class="rank">{{ $index + 1 }}</div>
                                    <div class="film-info">
                                        <p class="film-title">{{ $film->titre }}</p>
                                        <small class="text-muted">{{ $film->projections_count }} projection(s)</small>
                                    </div>
                                    <div class="projection-badge">{{ $film->projections_count }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">Aucun film projeté</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">⏰ Prochaines projections (7 jours)</h5>
                </div>
                <div class="card-body p-0">
                    @if($upcoming_projections->count() > 0)
                        <div class="upcoming-list">
                            @foreach($upcoming_projections as $index => $projection)
                                <div class="upcoming-item {{ $index < $upcoming_projections->count() - 1 ? 'border-bottom' : '' }}">
                                    <div class="upcoming-date">
                                        <strong>{{ $projection->date->format('d/m') }}</strong>
                                        <span class="upcoming-time">{{ $projection->heure }}</span>
                                    </div>
                                    <div class="upcoming-film">
                                        <p class="film-title">{{ $projection->film->titre ?? 'Film supprimé' }}</p>
                                        <small class="text-muted">{{ $projection->salle }} • {{ $projection->lieu }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">Aucune projection à venir</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">📢 Actualités récentes</h5>
                </div>
                <div class="card-body p-0">
                    @if($latest_actualite)
                        <div class="actualites-list">
                            <div class="actualite-item">
                                <div class="actualite-date">{{ $latest_actualite->created_at->format('d/m/Y') }}</div>
                                <div class="actualite-content">
                                    <h6 class="actualite-title">{{ $latest_actualite->titre ?? 'Sans titre' }}</h6>
                                    <p class="actualite-desc text-muted">{{ Str::limit($latest_actualite->contenu ?? '', 160) }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-3 text-center text-muted">Aucune actualité</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-container { animation: fadeIn .5s ease; }
    .dashboard-header { padding: 1rem 0; border-bottom: 1px solid var(--surface-border); }
    .dashboard-header h1 { color: var(--app-text); font-size: 2rem; }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        padding: 1.5rem;
        background: var(--surface);
        border: 1px solid var(--surface-border);
        border-radius: 14px;
        transition: all .25s ease;
        box-shadow: var(--surface-shadow);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .12);
    }

    .stat-icon {
        font-size: 2.5rem;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        flex-shrink: 0;
    }

    .stat-icon.films { background: rgba(59, 130, 246, .1); }
    .stat-icon.projections { background: rgba(34, 197, 94, .1); }
    .stat-icon.actualites { background: rgba(236, 72, 153, .1); }
    .stat-icon.galeries { background: rgba(168, 85, 247, .1); }

    .stat-content h3 { font-size: 1.8rem; font-weight: 700; margin: 0; color: var(--app-text); }
    .stat-content p { font-size: .9rem; margin: .3rem 0 0 0; }

    .projection-period-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .projection-period-item { text-align: center; padding: 1rem; background: var(--table-zebra); border-radius: 10px; border: 1px solid var(--surface-border); }
    .period-label { display: block; font-size: .85rem; color: var(--muted-text); margin-bottom: .5rem; }
    .period-count { display: block; font-size: 1.6rem; font-weight: 700; color: var(--app-text); }

    .frequency-stat { padding: 0; }
    .frequency-item { margin-bottom: 1rem; }
    .frequency-label { display: block; font-size: .9rem; color: var(--app-text); margin-bottom: .5rem; font-weight: 500; }
    .frequency-value { display: block; font-size: 1.4rem; font-weight: 700; color: var(--app-text); }

    .most-projected-list { max-height: 400px; overflow-y: auto; }
    .projected-item { display: flex; align-items: center; gap: 1rem; padding: 1rem; }
    .rank { min-width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #3b82f6, #22c55e); color: #fff; border-radius: 50%; font-weight: 700; flex-shrink: 0; }
    .film-info { flex: 1; min-width: 0; }
    .film-title { margin: 0; font-weight: 600; color: var(--app-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .projection-badge { background: var(--table-zebra); padding: .4rem .8rem; border-radius: 6px; font-weight: 600; color: var(--app-text); font-size: .85rem; flex-shrink: 0; }

    .upcoming-list { max-height: 400px; overflow-y: auto; }
    .upcoming-item { display: flex; gap: 1.2rem; padding: 1rem; align-items: flex-start; }
    .upcoming-date { display: flex; flex-direction: column; align-items: center; min-width: 50px; padding: .5rem; background: var(--table-zebra); border-radius: 8px; border: 1px solid var(--surface-border); }
    .upcoming-date strong { font-size: 1.1rem; color: var(--app-text); }
    .upcoming-time { font-size: .75rem; color: var(--muted-text); }
    .upcoming-film { flex: 1; min-width: 0; }

    .actualites-list { max-height: 350px; overflow-y: auto; }
    .actualite-item { display: flex; gap: 1.2rem; padding: 1rem; }
    .actualite-date { min-width: 90px; color: var(--muted-text); font-size: .85rem; font-weight: 600; }
    .actualite-content { flex: 1; min-width: 0; }
    .actualite-title { margin: 0 0 .4rem 0; color: var(--app-text); font-weight: 600; }
    .actualite-desc { margin: 0; font-size: .9rem; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 768px) {
        .projection-period-row { grid-template-columns: 1fr; }
        .stat-card { gap: 1rem; }
        .stat-icon { font-size: 2rem; width: 50px; height: 50px; }
        .stat-content h3 { font-size: 1.4rem; }
    }
</style>
@endsection