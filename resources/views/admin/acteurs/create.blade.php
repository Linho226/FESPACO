@extends('admin.layout')

@section('content')
<h1>Ajouter un Acteur</h1>

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
</style>

<form action="{{ route('admin.acteurs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="prenom" placeholder="Prénom" value="{{ old('prenom') }}" required>
    <input type="text" name="nom" placeholder="Nom" value="{{ old('nom') }}" required>
    <input type="text" name="nationalite" placeholder="Nationalité" value="{{ old('nationalite') }}">
    <input type="file" name="photo">
    <input type="text" name="type" placeholder="Type" value="{{ old('type') }}">
    <textarea name="biographie" placeholder="Biographie">{{ old('biographie') }}</textarea>
    <button type="submit">Ajouter</button>
</form>
@endsection