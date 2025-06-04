@extends("layouts.app")

@section("title", "Modifier Service : " . $service->libelle)

@section("content")
<div class="container">
    <h1>Modifier le Service : {{ $service->libelle }}</h1>

    <form action="{{ route("parametres.services.update", $service) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="row mb-3">
            <label for="code" class="col-md-4 col-form-label text-md-end">Code *</label>
            <div class="col-md-6">
                <input id="code" type="text" class="form-control @error("code") is-invalid @enderror" name="code" value="{{ old("code", $service->code) }}" required autofocus>
                @error("code")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="libelle" class="col-md-4 col-form-label text-md-end">Libellé *</label>
            <div class="col-md-6">
                <input id="libelle" type="text" class="form-control @error("libelle") is-invalid @enderror" name="libelle" value="{{ old("libelle", $service->libelle) }}" required>
                @error("libelle")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="site_id" class="col-md-4 col-form-label text-md-end">Site Associé *</label>
            <div class="col-md-6">
                <select id="site_id" class="form-select @error("site_id") is-invalid @enderror" name="site_id" required>
                    <option value="">-- Sélectionner un Site --</option>
                    @foreach ($sites as $site) {{-- Assuming $sites is passed from controller --}}
                        <option value="{{ $site->id }}" {{ old("site_id", $service->site_id) == $site->id ? "selected" : "" }}>{{ $site->libelle }} ({{ $site->code }})</option>
                    @endforeach
                </select>
                @error("site_id")
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route("parametres.services.index") }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection
