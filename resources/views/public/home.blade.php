<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FESPACO - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #181f2a;
            color: #fff;
        }
        .hero {
            background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            min-height: 320px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }
        .hero h1 {
            font-size: 2.7rem;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 28px;
        }
        .section-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 48px 0 28px 0;
            color: #fff;
        }
        .footer {
            background: #222;
            color: #bbb;
            text-align: center;
            padding: 18px 0 8px 0;
            margin-top: 48px;
        }
    </style>
</head>
<body>
        @include('public.navbar')
    <div class="hero">
        <h1>Bienvenue au FESPACO</h1>
        <p>Le plus grand festival du cinéma africain. Découvrez la culture, les films et les talents du continent !</p>
        <a href="/films" class="btn btn-primary btn-lg">Découvrir les films</a>
    </div>
    <div class="container py-5">
        <div class="row justify-content-center mb-5">
            <div class="col-md-4 text-center">
                <div class="card bg-light text-dark mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Programmation</h5>
                        <p class="card-text">Consultez le programme des projections, ateliers et événements spéciaux du festival.</p>
                        <a href="/projections" class="btn btn-outline-primary">Voir la programmation</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card bg-light text-dark mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Galerie photos & vidéos</h5>
                        <p class="card-text">Revivez les meilleurs moments du festival en images et vidéos.</p>
                        <a href="/galerie" class="btn btn-outline-primary">Voir la galerie</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="card bg-light text-dark mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Réalisateurs & Acteurs</h5>
                        <p class="card-text">Découvrez les talents du cinéma africain présents au FESPACO.</p>
                        <a href="/realisateurs-acteurs" class="btn btn-outline-primary">Voir les profils</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} FESPACO. Tous droits réservés.
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
