@extends('admin.layout')

@section('content')
<h1>Liste des Acteurs</h1>
<a href="{{ route('admin.acteurs.create') }}">Ajouter un acteur</a>

@if($acteurs->isEmpty())
    <p>Aucun acteur enregistré.</p>
@else
<ul>
    @foreach($acteurs as $acteur)
        <li>
            @if($acteur->photo)
                <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo de {{ $acteur->prenom }}" style="max-width:50px;vertical-align:middle;">
            @endif
            {{ $acteur->prenom }} {{ $acteur->nom }}
            <a href="{{ route('admin.acteurs.show', $acteur) }}">Voir</a>
            <a href="{{ route('admin.acteurs.edit', $acteur) }}">Modifier</a>
            <form action="{{ route('admin.acteurs.destroy', $acteur) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Supprimer cet acteur ?')">Supprimer</button>
            </form>
        </li>
    @endforeach
</ul>
@endif
@endsection