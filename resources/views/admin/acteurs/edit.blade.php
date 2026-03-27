@extends('admin.layout')

@section('content')
<h1>Modifier l'Acteur</h1>

@if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<style>
    form {
        display: flex;
        flex-direction: column;
        max-width: 400px;
        margin: 0 auto;
    }
    input, textarea, button {
        margin-bottom: 15px;
        padding: 8px;
        font-size: 1rem;
    }
    button {
        background: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }
    button:hover {
        background: #0056b3;
    }
    .current-photo {
        margin-bottom: 15px;
        text-align: center;
    }
    .current-photo img {
        max-width: 150px;
        border-radius: 8px;
        margin-bottom: 5px;
    }
</style>

<form action="{{ route('admin.acteurs.update', $acteur) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="text" name="prenom" value="{{ old('prenom', $acteur->prenom) }}" required>
    <input type="text" name="nom" value="{{ old('nom', $acteur->nom) }}" required>
    <input type="text" name="nationalite" value="{{ old('nationalite', $acteur->nationalite) }}">
    
    <div class="current-photo">
        @if($acteur->photo)
            <img src="{{ asset('storage/'.$acteur->photo) }}" alt="Photo actuelle">
            <div>Photo actuelle</div>
        @else
            <div>Aucune photo</div>
        @endif
    </div>
    <input type="file" name="photo">

    <input type="text" name="type" value="{{ old('type', $acteur->type) }}">
    <textarea name="biographie" placeholder="Biographie">{{ old('biographie', $acteur->biographie) }}</textarea>
    <button type="submit">Modifier</button>
    <a href="{{ route('admin.acteurs.index') }}">Annuler</a>
</form>
@endsection