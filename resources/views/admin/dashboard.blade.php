@extends('admin.layout')

@section('title', 'Dashboard Administration FESPACO')

@section('content')
    <style>
        .banner {
            background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            min-height: 260px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-shadow: 0 2px 8px rgba(0,0,0,0.25);
            margin-top: 56px;
        }
        .banner h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .banner p {
            font-size: 1.2rem;
            margin-bottom: 24px;
        }
        .banner .cta {
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .banner .cta:hover {
            background: #1d4ed8;
        }
        .cards-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 28px;
            color: #fff;
        }
        .dashboard-actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin: 32px 0 0 0;
        }
        .dashboard-actions a, .dashboard-actions button {
            padding: 10px 22px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            transition: background 0.2s;
        }
        .dashboard-actions a:hover, .dashboard-actions button:hover {
            background: #1d4ed8;
        }
        .dashboard-actions form {
            display: inline;
        }
    </style>
    <div class="banner">
        <h1>Espace Administration FESPACO</h1>
        <p>Gérez facilement les films, acteurs et projections du festival.</p>
        <a href="#gestion" class="cta">Voir les sections</a>
    </div>
    <div class="container py-5" id="gestion">
        <div class="cards-title">Les sections principales de gestion</div>
        <div class="row justify-content-center g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Films">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Films</h5>
                        <p class="card-text">Ajoutez, modifiez ou supprimez les films du festival.</p>
                        <a href="{{ route('admin.films.index') }}" class="btn btn-primary">Gérer les films</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Acteurs">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Acteurs</h5>
                        <p class="card-text">Gérez la liste des acteurs participants au festival.</p>
                        <a href="{{ route('admin.acteurs.index') }}" class="btn btn-primary">Gérer les acteurs</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Réalisateurs">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Réalisateurs</h5>
                        <p class="card-text">Gérez la liste des réalisateurs participants au festival.</p>
                        <a href="{{ route('admin.realisateurs.index') }}" class="btn btn-primary">Gérer les réalisateurs</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=600&q=80" class="card-img-top" alt="Projections">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Projections</h5>
                        <p class="card-text">Planifiez et modifiez les projections de films.</p>
                        <a href="{{ route('admin.projections') }}" class="btn btn-primary">Gérer les projections</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection