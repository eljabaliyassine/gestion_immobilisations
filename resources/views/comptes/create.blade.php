@extends("layouts.app")

@section("content")
<div class="container">
    <h1>Créer un nouveau Compte</h1>

    <form action="{{ route("comptes.store") }}" method="POST">
        @csrf
        <div class="row mb-3">
            <label for="nom" class="col-md-4 col-form-label text-md-end">Nom du Compte *</label>
            <div class="col-md-6">
                <input id="nom" type="text" class="form-control @error("nom") is-invalid @enderror" name="nom" value="{{ old("nom") }}" required autofocus>
                @error("nom")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="identifiant_unique" class="col-md-4 col-form-label text-md-end">Identifiant Unique (SIRET, ICE, etc.) *</label>
            <div class="col-md-6">
                <input id="identifiant_unique" type="text" class="form-control @error("identifiant_unique") is-invalid @enderror" name="identifiant_unique" value="{{ old("identifiant_unique") }}" required>
                @error("identifiant_unique")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="adresse" class="col-md-4 col-form-label text-md-end">Adresse</label>
            <div class="col-md-6">
                <textarea id="adresse" class="form-control @error("adresse") is-invalid @enderror" name="adresse">{{ old("adresse") }}</textarea>
                @error("adresse")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="telephone" class="col-md-4 col-form-label text-md-end">Téléphone</label>
            <div class="col-md-6">
                <input id="telephone" type="text" class="form-control @error("telephone") is-invalid @enderror" name="telephone" value="{{ old("telephone") }}">
                @error("telephone")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error("email") is-invalid @enderror" name="email" value="{{ old("email") }}">
                @error("email")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 offset-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1" {{ old("actif", true) ? "checked" : "" }}>
                    <label class="form-check-label" for="actif">
                        Actif
                    </label>
                </div>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Créer le Compte</button>
                <a href="{{ route("comptes.index") }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection
