@extends('admin.layout')

@section('content')
<h1 style="text-align:center; margin-bottom: 0;">Réalisateurs & Acteurs</h1>
<p style="text-align:center; margin-bottom: 30px;">Rencontrez les talents du cinéma africain présents au festival.</p>

<style>
    .tabs {
        display: flex;
        justify-content: center;
        margin-bottom: 24px;
        gap: 10px;
    }
    .tab-btn {
        padding: 10px 28px;
        border: none;
        background: #e9ecef;
        color: #222;
        font-size: 1.1rem;
        border-radius: 8px 8px 0 0;
        cursor: pointer;
        transition: background 0.2s;
    }
    .tab-btn.active {
        background: #fff;
        color: #007bff;
        font-weight: bold;
        border-bottom: 2px solid #007bff;
    }
    .tab-content {
        display: none;
        animation: fadeIn 0.4s;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .search-bar {
        display: flex;
        justify-content: center;
        margin-bottom: 18px;
    }
    .search-bar input {
        width: 260px;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 1rem;
    }
    .talent-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
        margin: 0 2vw;
    }
    .talent-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        padding: 18px 10px 14px 10px;
        text-align: center;
        transition: transform 0.18s, box-shadow 0.18s;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 260px;
        min-width: 0;
        aspect-ratio: 1/1.1; /* carré ou presque */
        justify-content: space-between;
    }
    .talent-card:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 6px 24px rgba(0,0,0,0.13);
    }
    .talent-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        border: 3px solid #f3f3f3;
        background: #f8f8f8;
    }
    .talent-card h3 {
        margin: 6px 0 2px 0;
        font-size: 1.08rem;
        font-weight: 600;
        color: #222;
    }
    .talent-card p {
        margin: 2px 0;
        font-size: 0.97rem;
        color: #444;
    }
    .talent-card .talent-type {
        font-size: 0.95rem;
        color: #007bff;
        font-weight: 500;
        margin-top: 4px;
    }
    .talent-card .details-btn {
        margin-top: 10px;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 7px 16px;
        font-size: 0.97rem;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
        display: inline-block;
    }
    .talent-card .details-btn:hover {
        background: #0056b3;
    }
    @media (max-width: 1200px) {
        .talent-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 900px) {
        .talent-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 600px) {
        .talent-grid {
            grid-template-columns: 1fr;
            margin: 0 4vw;
            gap: 12px;
        }
        .talent-card {
            min-height: 180px;
            padding: 10px 2px 8px 2px;
        }
    }
</style>

<div class="tabs">
    <button class="tab-btn active" onclick="showTab('realisateurs')">Réalisateurs</button>
    <button class="tab-btn" onclick="showTab('acteurs')">Acteurs</button>
</div>

<div id="realisateurs" class="tab-content active">
    <div class="search-bar">
        <input type="text" id="searchRealisateurs" placeholder="Rechercher un réalisateur..." onkeyup="filterTalents('realisateurs')">
    </div>
    <div class="talent-grid" id="gridRealisateurs">
        @forelse($realisateurs as $realisateur)
            <div class="talent-card">
                <div>
                    @if($realisateur->photo)
                        <img src="{{ asset('storage/'.$realisateur->photo) }}" alt="Photo de {{ $realisateur->prenom }}" class="talent-photo">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($realisateur->prenom.' '.$realisateur->nom) }}&background=cccccc&color=222222&size=80" class="talent-photo" alt="Avatar">
                    @endif
                    <h3 class="talent-name">{{ $realisateur->prenom }} {{ $realisateur->nom }}</h3>
                    <p><strong>Nationalité :</strong> {{ $realisateur->nationalite ?? 'Non renseignée' }}</p>
                    <div class="talent-type">{{ $realisateur->type ?? 'Réalisateur' }}</div>
                </div>
                <a href="{{ route('admin.realisateurs.show', $realisateur) }}" class="details-btn">Voir les détails</a>
            </div>
        @empty
            <p>Aucun réalisateur enregistré.</p>
        @endforelse
    </div>
</div>

<div id="acteurs" class="tab-content">
    <div class="search-bar">
        <input type="text" id="searchActeurs" placeholder="Rechercher un acteur..." onkeyup="filterTalents('acteurs')">
    </div>
    <div class="talent-grid" id="gridActeurs">
        @forelse($acteurs as $acteur)
            <div class="talent-card">
                <div>
                    @if($acteur->photo)
                        <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo de {{ $acteur->prenom }}" class="talent-photo">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($acteur->prenom.' '.$acteur->nom) }}&background=cccccc&color=222222&size=80" class="talent-photo" alt="Avatar">
                    @endif
                    <h3 class="talent-name">{{ $acteur->prenom }} {{ $acteur->nom }}</h3>
                    <p><strong>Nationalité :</strong> {{ $acteur->nationalite ?? 'Non renseignée' }}</p>
                    <div class="talent-type">{{ $acteur->type ?? 'Acteur' }}</div>
                </div>
                <a href="{{ route('admin.acteurs.show', $acteur) }}" class="details-btn">Voir les détails</a>
            </div>
        @empty
            <p>Aucun acteur enregistré.</p>
        @endforelse
    </div>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tabC => tabC.classList.remove('active'));
    document.querySelector('.tab-btn[onclick="showTab(\''+tab+'\')"]').classList.add('active');
    document.getElementById(tab).classList.add('active');
}

// Filtrage JS côté client
function filterTalents(type) {
    let input = document.getElementById('search'+(type.charAt(0).toUpperCase()+type.slice(1)));
    let filter = input.value.toLowerCase();
    let grid = document.getElementById('grid'+(type.charAt(0).toUpperCase()+type.slice(1)));
    let cards = grid.getElementsByClassName('talent-card');
    for (let i = 0; i < cards.length; i++) {
        let name = cards[i].getElementsByClassName('talent-name')[0];
        if (name.innerText.toLowerCase().indexOf(filter) > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}
</script>
@endsection
