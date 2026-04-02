@extends('admin.layout')

@section('title', 'Utilisateurs - Administration FESPACO')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h1 class="display-6 fw-bold mb-1">Utilisateurs</h1>
        <p class="text-muted mb-0">Gerez les comptes et attribuez le role administrateur aux utilisateurs autorises.</p>
    </div>
    <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-primary">Modifier mon profil</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Comptes utilisateurs</h5>
        <span class="badge bg-secondary">{{ $users->total() }} compte(s)</span>
    </div>
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Creation</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    @if(auth()->id() === $user->id)
                                        <small class="text-muted">Votre compte</small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge bg-success">Administrateur</span>
                                    @else
                                        <span class="badge bg-secondary">Utilisateur</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2 flex-wrap">
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="d-inline-flex align-items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_admin" value="{{ $user->isAdmin() ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm {{ $user->isAdmin() ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                {{ $user->isAdmin() ? 'Retirer admin' : 'Rendre admin' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline-flex" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 text-center text-muted">Aucun utilisateur trouve.</div>
        @endif
    </div>
    <div class="card-footer bg-transparent">
        {{ $users->links() }}
    </div>
</div>
@endsection