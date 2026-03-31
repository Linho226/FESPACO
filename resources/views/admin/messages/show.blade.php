@extends('admin.layout')

@section('title', 'Message')

@section('content')
<style>
    .message-detail-card {
        background: #111a2a;
        border: 1px solid #2a3a52;
        color: #e5edf8;
    }

    .message-meta-label {
        color: #9fb0c8;
        font-size: .82rem;
        margin-bottom: .2rem;
        display: block;
    }

    .message-meta-value {
        color: #eef4ff;
        font-weight: 600;
    }

    .message-content {
        border: 1px solid #3b4f6b;
        background: #0f1727;
        color: #eaf1fb;
        border-radius: .6rem;
        padding: 1rem;
        white-space: pre-wrap;
    }

    @media (prefers-color-scheme: light) {
        .message-detail-card {
            background: #ffffff;
            border: 1px solid #d8e1ec;
            color: #152235;
        }

        .message-meta-label {
            color: #5f6f85;
        }

        .message-meta-value {
            color: #122033;
        }

        .message-content {
            border-color: #cfd8e3;
            background: #f7fafc;
            color: #152235;
        }
    }
</style>
<div class="container" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 class="mb-0">Message de contact</h2>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card shadow-sm message-detail-card">
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <span class="message-meta-label">Nom</span>
                    <div class="message-meta-value">{{ $contactMessage->name }}</div>
                </div>
                <div class="col-md-6">
                    <span class="message-meta-label">Email</span>
                    <div class="message-meta-value">{{ $contactMessage->email }}</div>
                </div>
                <div class="col-md-6">
                    <span class="message-meta-label">Telephone</span>
                    <div class="message-meta-value">{{ $contactMessage->phone ?: 'Non renseigne' }}</div>
                </div>
                <div class="col-md-6">
                    <span class="message-meta-label">Sujet</span>
                    <div class="message-meta-value">{{ $contactMessage->subject }}</div>
                </div>
                <div class="col-md-6">
                    <span class="message-meta-label">Date de reception</span>
                    <div class="message-meta-value">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-md-6">
                    <span class="message-meta-label">Statut</span>
                    @if($contactMessage->is_read)
                        <span class="badge bg-success">Lu</span>
                    @else
                        <span class="badge bg-warning text-dark">Nouveau</span>
                    @endif
                </div>
            </div>

            <div class="message-content">{{ $contactMessage->message }}</div>

            <div class="mt-3 d-flex gap-2">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re:%20{{ urlencode($contactMessage->subject) }}" class="btn btn-primary">Repondre par email</a>
                <form method="POST" action="{{ route('admin.messages.destroy', $contactMessage) }}" onsubmit="return confirm('Supprimer ce message ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
