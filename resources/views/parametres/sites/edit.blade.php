@extends("layouts.app")

@section("title", "Modifier Site : " . $site->libelle)

@section("content")
<div class="container">
    <h1>Modifier le Site Géographique : {{ $site->libelle }}</h1>

    <form action="{{ route("parametres.sites.update", $site) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="row mb-3">
            <label for="code" class="col-md-4 col-form-label text-md-end">Code *</label>
            <div class="col-md-6">
                <input id="code" type="text" class="form-control @error("code") is-invalid @enderror" name="code" value="{{ old("code", $site->code) }}" required autofocus>
                @error("code")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="libelle" class="col-md-4 col-form-label text-md-end">Libellé *</label>
            <div class="col-md-6">
                <input id="libelle" type="text" class="form-control @error("libelle") is-invalid @enderror" name="libelle" value="{{ old("libelle", $site->libelle) }}" required>
                @error("libelle")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="adresse" class="col-md-4 col-form-label text-md-end">Adresse</label>
            <div class="col-md-6">
                <textarea id="adresse" class="form-control @error("adresse") is-invalid @enderror" name="adresse" rows="3">{{ old("adresse", $site->adresse) }}</textarea>
                @error("adresse")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route("parametres.sites.index") }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection
