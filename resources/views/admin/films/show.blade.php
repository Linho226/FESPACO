@extends('admin.layout')

@section('title', 'Détail du film')

@section('content')
<div class="container">
    <h2 class="mb-4">Détail du film</h2>
    <div class="card mb-3">
        <div class="row g-0">
            @if($film->affiche)
            <div class="col-md-4">
                <img src="{{ asset('storage/' . $film->affiche) }}" class="img-fluid rounded-start" alt="Affiche du film">
            </div>
            @endif
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title">{{ $film->titre }}</h3>
                    <p class="card-text"><strong>Description :</strong> {{ $film->description }}</p>
                    <p class="card-text"><strong>Année de production :</strong> {{ $film->annee_production }}</p>
                    <p class="card-text"><strong>Pays :</strong> {{ $film->pays }}</p>
                    <p class="card-text"><strong>Durée :</strong> {{ $film->duree }} min</p>
                    <p class="card-text"><strong>Réalisateur :</strong> {{ $film->realisateur }}</p>
                    <p class="card-text"><strong>Acteurs :</strong> {{ $film->acteurs }}</p>
                    <p class="card-text"><strong>Catégorie :</strong> {{ $film->categorie }}</p>
                    <p class="card-text"><strong>Type :</strong> {{ $film->type == 'serie' ? 'Série' : 'Film' }}</p>

                    @if($film->type == 'serie')
                        @php
                            $videoLinks = $film->video_links ? json_decode($film->video_links, true) : [];
                            $videoFiles = $film->video_files ? json_decode($film->video_files, true) : [];
                        @endphp
                        @if(count($videoLinks))
                            <div class="mb-2">
                                <strong>Liens vidéos :</strong>
                                <ul>
                                    @foreach($videoLinks as $link)
                                        <li><a href="{{ $link }}" target="_blank">{{ $link }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(count($videoFiles))
                            <div class="mb-2">
                                <strong>Vidéos uploadées :</strong>
                                <ul>
                                    @foreach($videoFiles as $file)
                                        <li>
                                            <video src="{{ asset('storage/' . $file) }}" controls style="max-width: 100%; height: 120px;"></video>
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank">Télécharger</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @else
                        @if($film->video)
                            <div class="mb-2">
                                <strong>Vidéo :</strong><br>
                                <video src="{{ asset('storage/' . $film->video) }}" controls style="max-width: 100%; height: 240px;"></video>
                                <a href="{{ asset('storage/' . $film->video) }}" target="_blank">Télécharger</a>
                            </div>
                        @endif
                        @if($film->video_link)
                            <div class="mb-2">
                                <strong>Lien vidéo externe :</strong> <a href="{{ $film->video_link }}" target="_blank">{{ $film->video_link }}</a>
                            </div>
                        @endif
                    @endif
                    <a href="{{ route('admin.films.edit', $film) }}" class="btn btn-warning">Modifier</a>
                    <a href="{{ route('admin.films.index') }}" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
