@extends('admin.layout')

@section('content')
<h1>Liste des Réalisateurs</h1>
<a href="{{ route('admin.realisateurs.create') }}">Ajouter un réalisateur</a>

@if($realisateurs->isEmpty())
    <p>Aucun réalisateur enregistré.</p>
@else
<ul>
    @foreach($realisateurs as $realisateur)
        <li>
            @if($realisateur->photo)
                <img src="{{ asset('storage/'.$realisateur->photo) }}" alt="Photo de {{ $realisateur->prenom }}" style="max-width:50px;vertical-align:middle;">
            @endif
            {{ $realisateur->prenom }} {{ $realisateur->nom }}
            <a href="{{ route('admin.realisateurs.show', $realisateur) }}">Voir</a>
            <a href="{{ route('admin.realisateurs.edit', $realisateur) }}">Modifier</a>
            <form action="{{ route('admin.realisateurs.destroy', $realisateur) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Supprimer ce réalisateur ?')">Supprimer</button>
            </form>
        </li>
    @endforeach
</ul>
@endif
@endsection