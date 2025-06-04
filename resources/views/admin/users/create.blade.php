@extends("layouts.app")

@section("title", "Créer un Utilisateur")

@section("content")
<div class="container">
    <h1>Créer un Nouvel Utilisateur</h1>

    <form action="{{ route("admin.users.store") }}" method="POST">
        @csrf

        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">Nom *</label>
            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error("name") is-invalid @enderror" name="name" value="{{ old("name") }}" required autofocus>
                @error("name")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">Adresse Email *</label>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error("email") is-invalid @enderror" name="email" value="{{ old("email") }}" required>
                @error("email")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end">Mot de passe *</label>
            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error("password") is-invalid @enderror" name="password" required autocomplete="new-password">
                @error("password")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirmer Mot de passe *</label>
            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>

        <div class="row mb-3">
            <label for="compte_id" class="col-md-4 col-form-label text-md-end">Compte *</label>
            <div class="col-md-6">
                <select id="compte_id" class="form-select @error("compte_id") is-invalid @enderror" name="compte_id" required>
                    <option value="">-- Sélectionner un Compte --</option>
                    @foreach ($comptes as $compte)
                        <option value="{{ $compte->id }}" {{ old("compte_id") == $compte->id ? "selected" : "" }}>{{ $compte->nom }}</option>
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
                        <option value="{{ $role->id }}" {{ old("role_id") == $role->id ? "selected" : "" }}>{{ $role->nom }}</option>
                    @endforeach
                </select>
                @error("role_id")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        {{-- Add fields for assigning dossiers if needed, could be complex --}}

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Créer Utilisateur</button>
                <a href="{{ route("admin.users.index") }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection
