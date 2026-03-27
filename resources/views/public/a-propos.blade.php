<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .apropos-bg {
            background: #f8fafc;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            padding: 32px 24px;
            margin-bottom: 32px;
        }
        .apropos-title {
            color: #2563eb;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .apropos-lead {
            font-size: 1.15rem;
            color: #333;
        }
        @media (max-width: 576px) {
            .apropos-bg { padding: 18px 8px; }
        }
    </style>
</head>
<body>
    @include('public.navbar')
    <div class="container py-5">
        <h1 class="mb-4 text-center apropos-title">À propos du FESPACO</h1>
        <div class="apropos-bg mx-auto" style="max-width: 700px;">
            <p class="apropos-lead mb-3">
                <strong>Le Festival Panafricain du Cinéma et de la Télévision de Ouagadougou (FESPACO)</strong> est l’un des plus grands festivals consacrés au cinéma africain. Créé en 1969 à Ouagadougou, il constitue un événement majeur qui valorise les productions cinématographiques africaines et celles de la diaspora.
            </p>
            <p class="apropos-lead mb-3">
                Le FESPACO se déroule tous les deux ans (biennale) et rassemble des réalisateurs, acteurs, producteurs et professionnels du cinéma venus de plusieurs pays.
            </p>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Création</h5>
                        <p class="card-text">1969, Ouagadougou</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Périodicité</h5>
                        <p class="card-text">Biennale (tous les 2 ans)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Portée</h5>
                        <p class="card-text">Afrique & Diaspora</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="/contact" class="btn btn-primary btn-lg">Nous contacter</a>
        </div>
    </div>
</body>
</html>
