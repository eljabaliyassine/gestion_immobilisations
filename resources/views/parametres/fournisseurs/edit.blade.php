@extends("layouts.app")

@section("title", "Modifier Fournisseur : " . $fournisseur->nom)

@section("content")
<div class="container">
    <h1>Modifier le Fournisseur : {{ $fournisseur->nom }}</h1>

    <form action="{{ route("parametres.fournisseurs.update", $fournisseur) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="row mb-3">
            <label for="code" class="col-md-4 col-form-label text-md-end">Code *</label>
            <div class="col-md-6">
                <input id="code" type="text" class="form-control @error("code") is-invalid @enderror" name="code" value="{{ old("code", $fournisseur->code) }}" required autofocus>
                @error("code")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="nom" class="col-md-4 col-form-label text-md-end">Nom *</label>
            <div class="col-md-6">
                <input id="nom" type="text" class="form-control @error("nom") is-invalid @enderror" name="nom" value="{{ old("nom", $fournisseur->nom) }}" required>
                @error("nom")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="adresse" class="col-md-4 col-form-label text-md-end">Adresse</label>
            <div class="col-md-6">
                <textarea id="adresse" class="form-control @error("adresse") is-invalid @enderror" name="adresse" rows="3">{{ old("adresse", $fournisseur->adresse) }}</textarea>
                @error("adresse")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="telephone" class="col-md-4 col-form-label text-md-end">Téléphone</label>
            <div class="col-md-6">
                <input id="telephone" type="text" class="form-control @error("telephone") is-invalid @enderror" name="telephone" value="{{ old("telephone", $fournisseur->telephone) }}">
                @error("telephone")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error("email") is-invalid @enderror" name="email" value="{{ old("email", $fournisseur->email) }}">
                @error("email")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

         <div class="row mb-3">
            <label for="site_web" class="col-md-4 col-form-label text-md-end">Site Web</label>
            <div class="col-md-6">
                <input id="site_web" type="url" class="form-control @error("site_web") is-invalid @enderror" name="site_web" value="{{ old("site_web", $fournisseur->site_web) }}">
                @error("site_web")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route("parametres.fournisseurs.index") }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection
