@extends('admin.layout')

@section('title', 'Boite de reception')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 class="mb-0">Boite de reception</h2>
        <span class="badge bg-danger">{{ $unreadCount }} non lu(s)</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.messages.index') }}" class="row g-2 mb-3">
        <div class="col-md-7">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, email, sujet ou message" value="{{ $search }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Tous</option>
                <option value="unread" {{ $status === 'unread' ? 'selected' : '' }}>Non lus</option>
                <option value="read" {{ $status === 'read' ? 'selected' : '' }}>Lus</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Statut</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Recu le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>
                            @if($message->is_read)
                                <span class="badge bg-success">Lu</span>
                            @else
                                <span class="badge bg-warning text-dark">Nouveau</span>
                            @endif
                        </td>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->subject }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($message->message, 70) }}</td>
                        <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                        <td class="d-flex flex-wrap gap-1">
                            <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-sm btn-primary">Voir</a>

                            @if(!$message->is_read)
                                <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Marquer lu</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Aucun message.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $messages->links() }}
</div>
@endsection
