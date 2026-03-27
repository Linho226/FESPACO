@extends('admin.layout')

@section('content')
<style>
    .realisateur-container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 32px 24px;
        text-align: center;
    }
    .realisateur-photo {
        max-width: 180px;
        border-radius: 8px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }
    .realisateur-info {
        margin-bottom: 18px;
        text-align: left;
    }
    .realisateur-info p {
        margin: 8px 0;
        font-size: 1.08rem;
    }
    .realisateur-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }
    .realisateur-actions a, .realisateur-actions button {
        padding: 8px 18px;
        border-radius: 4px;
        border: none;
        text-decoration: none;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .realisateur-actions a {
        background: #007bff;
        color: #fff;
    }
    .realisateur-actions a:hover {
        background: #0056b3;
    }
    .realisateur-actions .delete-btn {
        background: #dc3545;
        color: #fff;
    }
    .realisateur-actions .delete-btn:hover {
        background: #a71d2a;
    }
    @media (max-width: 600px) {
        .realisateur-container {
            padding: 16px 4vw;
        }
        .realisateur-photo {
            max-width: 100%;
        }
    }
</style>

<div class="realisateur-container">
    @if($realisateur->photo)
        <img src="{{ asset('storage/'.$realisateur->photo) }}" alt="Photo de {{ $realisateur->prenom }}" class="realisateur-photo">
    @endif

    <h1>{{ $realisateur->prenom }} {{ $realisateur->nom }}</h1>
    <div class="realisateur-info">
        <p><strong>Nationalité :</strong> {{ $realisateur->nationalite ?? 'Non renseignée' }}</p>
        <p><strong>Type :</strong> {{ $realisateur->type ?? 'Non renseigné' }}</p>
        <p><strong>Biographie :</strong> {{ $realisateur->biographie ?? 'Non renseignée' }}</p>
    </div>

    <div class="realisateur-actions">
        <a href="{{ route('admin.realisateurs.edit', $realisateur) }}">Modifier</a>
        <form action="{{ route('admin.realisateurs.destroy', $realisateur) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn" onclick="return confirm('Supprimer ce réalisateur ?')">Supprimer</button>
        </form>
        <a href="{{ route('admin.realisateurs.index') }}" style="background:#6c757d;">Retour à la liste</a>
    </div>
</div>
@endsection