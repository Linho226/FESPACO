@extends('admin.layout')

@section('content')
<style>
    .acteur-container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 32px 24px;
        text-align: center;
    }
    .acteur-photo {
        max-width: 180px;
        border-radius: 8px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }
    .acteur-info {
        margin-bottom: 18px;
        text-align: left;
    }
    .acteur-info p {
        margin: 8px 0;
        font-size: 1.08rem;
    }
    .acteur-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }
    .acteur-actions a, .acteur-actions button {
        padding: 8px 18px;
        border-radius: 4px;
        border: none;
        text-decoration: none;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .acteur-actions a {
        background: #007bff;
        color: #fff;
    }
    .acteur-actions a:hover {
        background: #0056b3;
    }
    .acteur-actions .delete-btn {
        background: #dc3545;
        color: #fff;
    }
    .acteur-actions .delete-btn:hover {
        background: #a71d2a;
    }
    @media (max-width: 600px) {
        .acteur-container {
            padding: 16px 4vw;
        }
        .acteur-photo {
            max-width: 100%;
        }
    }
</style>

<div class="acteur-container">
    @if($acteur->photo)
        <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo de {{ $acteur->prenom }}" class="acteur-photo">
    @endif

    <h1>{{ $acteur->prenom }} {{ $acteur->nom }}</h1>
    <div class="acteur-info">
        <p><strong>Nationalité :</strong> {{ $acteur->nationalite ?? 'Non renseignée' }}</p>
        <p><strong>Type :</strong> {{ $acteur->type ?? 'Non renseigné' }}</p>
        <p><strong>Biographie :</strong> {{ $acteur->biographie ?? 'Non renseignée' }}</p>
    </div>

    <div class="acteur-actions">
        <a href="{{ route('admin.acteurs.edit', $acteur) }}">Modifier</a>
        <form action="{{ route('admin.acteurs.destroy', $acteur) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn" onclick="return confirm('Supprimer cet acteur ?')">Supprimer</button>
        </form>
        <a href="{{ route('admin.acteurs.index') }}" style="background:#6c757d;">Retour à la liste</a>
    </div>
</div>
@endsection