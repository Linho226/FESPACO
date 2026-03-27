@extends('public.layout')

@section('title', 'Films du festival')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Films du festival</h2>
    <div class="row g-4">
        @forelse($films as $film)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch">
                <div class="card shadow-sm w-100 h-100 border-0 rounded-4 overflow-hidden">
                    @if($film->affiche)
                        <img src="{{ asset('storage/' . $film->affiche) }}" class="card-img-top object-fit-cover" alt="Affiche" style="height: 250px; width: 100%;">
                    @endif
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title fw-bold text-primary">{{ $film->titre }}</h5>
                        <p class="card-text mb-2">{{ Str::limit($film->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="badge bg-secondary">{{ $film->annee_production }}</span>
                            <span class="text-muted small">{{ $film->pays }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Aucun film pour le moment.</p>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $films->links() }}
    </div>
</div>
@endsection
