@extends('admin.layout')

@section('title', 'Choisir le type de film')

@section('content')
<div class="container">
    <h2 class="mb-4">Quel type de film souhaitez-vous ajouter ?</h2>
    <div class="mb-3">
        <a href="{{ route('admin.films.create', ['type' => 'film']) }}" class="btn btn-primary">Film complet</a>
        <a href="{{ route('admin.films.create', ['type' => 'serie']) }}" class="btn btn-secondary">Série</a>
    </div>
    <a href="{{ route('admin.films.index') }}" class="btn btn-link">Annuler</a>
</div>
@endsection
