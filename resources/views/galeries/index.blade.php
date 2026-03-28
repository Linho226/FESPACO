@extends('admin.layout')
@section('content')
<div class="container">
    <h1>Galerie multimédia</h1>
    <a href="{{ route('admin.galeries.create') }}" class="btn btn-primary mb-3">Ajouter un média</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row">
        @foreach($galeries as $galerie)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($galerie->type_media === 'image')
                        <img src="{{ asset('storage/'.$galerie->fichier) }}" class="card-img-top" alt="{{ $galerie->titre }}">
                    @else
                        <video controls class="w-100">
                            <source src="{{ asset('storage/'.$galerie->fichier) }}" type="video/mp4">
                        </video>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $galerie->titre }}</h5>
                        <p class="card-text">{{ Str::limit($galerie->description, 60) }}</p>
                        <a href="{{ route('admin.galeries.edit', $galerie) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.galeries.destroy', $galerie) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce média ?')">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $galeries->links() }}
</div>
@endsection
