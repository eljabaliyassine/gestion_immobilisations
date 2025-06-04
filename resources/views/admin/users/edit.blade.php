@extends("layouts.app")

@section("title", "Modifier l'Utilisateur : " . $user->name)

@section("content")
<div class="container">
    <h1>Modifier l'Utilisateur : {{ $user->name }}</h1>

    <form action="{{ route("admin.users.update", $user) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">Nom *</label>
            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error("name") is-invalid @enderror" name="name" value="{{ old("name", $user->name) }}" required autofocus>
                @error("name")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">Adresse Email *</label>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error("email") is-invalid @enderror" name="email" value="{{ old("email", $user->email) }}" required>
                @error("email")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end">Nouveau Mot de passe</label>
            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error("password") is-invalid @enderror" name="password" autocomplete="new-password">
                <small class="form-text text-muted">Laissez vide pour ne pas changer le mot de passe.</small>
                @error("password")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirmer Nouveau Mot de passe</label>
            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
            </div>
        </div>

        <div class="row mb-3">
            <label for="compte_id" class="col-md-4 col-form-label text-md-end">Compte *</label>
            <div class="col-md-6">
                <select id="compte_id" class="form-select @error("compte_id") is-invalid @enderror" name="compte_id" required>
                    <option value="">-- Sélectionner un Compte --</option>
                    @foreach ($comptes as $compte)
                        <option value="{{ $compte->id }}" {{ old("compte_id", $user->compte_id) == $compte->id ? "selected" : "" }}>{{ $compte->nom }}</option>
                    @endforeach
                </select>
                @error("compte_id")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="role_id" class="col-md-4 col-form-label text-md-end">Rôle *</label>
            <div class="col-md-6">
                <select id="role_id" class="form-select @error("role_id") is-invalid @enderror" name="role_id" required>
                     <option value="">-- Sélectionner un Rôle --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old("role_id", $user->role_id) == $role->id ? "selected" : "" }}>{{ $role->nom }}</option>
                    @endforeach
                </select>
                @error("role_id")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        {{-- Add fields for assigning dossiers if needed --}}

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route("admin.users.index") }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection
