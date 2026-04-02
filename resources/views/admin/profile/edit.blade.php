@extends('admin.layout')

@section('title', 'Mon profil - Administration FESPACO')

@section('content')
<div class="mx-auto" style="max-width: 980px;">
    <div class="mb-4 p-4 rounded-4 border" style="background: var(--table-zebra); border-color: var(--surface-border) !important;">
        <h1 class="h3 mb-1">Mon profil</h1>
        <p class="text-muted mb-0">Gerez vos informations personnelles, votre mot de passe et la securite de votre compte depuis l'espace admin.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">Profil mis a jour avec succes.</div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="alert alert-success">Mot de passe mis a jour avec succes.</div>
    @endif

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-info">Un nouveau lien de verification a ete envoye a votre adresse email.</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-1">Informations du profil</h2>
            <p class="text-muted small mb-3">Mettez a jour votre nom et votre adresse email.</p>

            <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="POST" action="{{ route('admin.profile.update') }}" class="row g-3">
                @csrf
                @method('PATCH')

                <div class="col-12">
                    <label for="name" class="form-label">Nom complet</label>
                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Adresse email</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">
                            Votre adresse email n'est pas verifiee.
                            <button form="send-verification" class="btn btn-sm btn-outline-dark ms-2" type="submit">Renvoyer le lien</button>
                        </div>
                    </div>
                @endif

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-1">Mot de passe</h2>
            <p class="text-muted small mb-3">Choisissez un mot de passe long et difficile a deviner.</p>

            <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                    <input id="current_password" name="current_password" type="password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" autocomplete="current-password">
                    @if($errors->updatePassword->has('current_password'))
                        <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                    @endif
                </div>

                <div class="col-12 col-md-6">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input id="password" name="password" type="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" autocomplete="new-password">
                    @if($errors->updatePassword->has('password'))
                        <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                    @endif
                </div>

                <div class="col-12 col-md-6">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" autocomplete="new-password">
                    @if($errors->updatePassword->has('password_confirmation'))
                        <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                    @endif
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Mettre a jour le mot de passe</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-danger-subtle">
        <div class="card-header text-danger fw-semibold">Zone sensible</div>
        <div class="card-body p-4">
            <h2 class="h5 mb-1 text-danger">Supprimer mon compte</h2>
            <p class="text-muted small mb-3">Cette action est irreversible. Toutes vos donnees seront supprimees.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" class="row g-3">
                @csrf
                @method('DELETE')

                <div class="col-12 col-md-6">
                    <label for="delete_password" class="form-label">Confirmez avec votre mot de passe</label>
                    <input id="delete_password" name="password" type="password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" autocomplete="current-password" required>
                    @if($errors->userDeletion->has('password'))
                        <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer votre compte ?');">Supprimer definitivement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection