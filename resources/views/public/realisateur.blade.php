
@extends('public.layout')

@section('content')
<style>
    .modern-photo {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5em;
        animation: fadeIn 1.2s cubic-bezier(.4,0,.2,1);
    }
    .modern-photo img {
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18), 0 0 0 4px #fff;
        border: 3px solid #b22222;
        background: #fff;
        max-width: 220px;
        max-height: 220px;
        object-fit: cover;
        aspect-ratio: 1/1;
        transition: transform 0.3s;
    }
    .modern-photo img:hover {
        transform: scale(1.04) rotate(-1deg);
        box-shadow: 0 12px 40px 0 #b22222a0, 0 0 0 8px #fff;
    }
    .modern-photo::after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        border-radius: 18px;
        box-shadow: 0 0 24px 4px #b2222240;
        z-index: 0;
        pointer-events: none;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: none; }
    }
    .modern-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 4px 32px rgba(178,34,34,0.08);
        max-width: 900px;
        margin: auto;
        border: none;
    }
    .modern-title {
        color: #b22222;
        font-weight: 900;
        letter-spacing: 1px;
    }
    .modern-subtitle {
        color: #555;
        font-weight: 600;
        margin-bottom: 1.2em;
    }
    .modern-badge {
        font-size: 1em;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.3em 1em;
        margin-right: 0.7em;
    }
    .modern-bio-title {
        color: #b22222;
        font-weight: 700;
        margin-bottom: 0.5em;
    }
    .modern-bio {
        white-space: pre-line;
        text-align: justify;
        color: #222;
        background: #f8f8fa;
        border-radius: 12px;
        padding: 1em 1.2em;
    }
    @media (max-width: 900px) {
        .modern-card { max-width: 98vw; }
        .modern-photo img { max-width: 150px; max-height: 150px; }
    }
</style>
<div class="container py-5">
    <div class="modern-card shadow-lg border-0 mb-4 p-4">
        <div class="d-flex flex-wrap align-items-center mb-4" style="gap: 2.5rem;">
            @if($realisateur->photo)
                <span class="modern-photo" style="margin-bottom:0;">
                    <img src="{{ asset('storage/' . $realisateur->photo) }}" alt="Photo du réalisateur" class="img-fluid">
                </span>
            @endif
            <div>
                <h1 class="display-5 fw-bold mb-2 modern-title">{{ $realisateur->nom }} {{ $realisateur->prenom }}</h1>
                <h5 class="modern-subtitle">Réalisateur</h5>
                <div class="mb-2">
                    <span class="badge bg-secondary modern-badge">Nationalité</span>
                    <span>{{ $realisateur->nationalite }}</span>
                </div>
                <div class="mb-2">
                    <span class="badge bg-info text-dark modern-badge">Type</span>
                    <span>{{ $realisateur->type }}</span>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <h6 class="fw-bold mb-2 modern-bio-title">Biographie :</h6>
            <div class="modern-bio">{{ $realisateur->biographie }}</div>
        </div>
    </div>
</div>
@endsection
