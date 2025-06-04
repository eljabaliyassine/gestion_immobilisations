@extends("layouts.app")

@section("title", "Modifier Session d'Inventaire : " . $inventaire->reference)

@section("content")
<div class="container">
    <h1>Modifier la Session d'Inventaire : {{ $inventaire->reference }}</h1>

    <div class="card">
        <div class="card-header">Informations sur la session</div>
        <div class="card-body">
            <form action="{{ route("inventaires.update", $inventaire) }}" method="POST">
                @csrf
                @method("PUT")

                <div class="row mb-3">
                    <label for="reference" class="col-md-4 col-form-label text-md-end">Référence</label>
                    <div class="col-md-6">
                        <input id="reference" type="text" class="form-control @error("reference") is-invalid @enderror" name="reference" value="{{ old("reference", $inventaire->reference) }}" required autofocus>
                        @error("reference")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="date_debut" class="col-md-4 col-form-label text-md-end">Date de Début</label>
                    <div class="col-md-6">
                        <input id="date_debut" type="date" class="form-control @error("date_debut") is-invalid @enderror" name="date_debut" value="{{ old("date_debut", $inventaire->date_debut->format("Y-m-d")) }}" required>
                        @error("date_debut")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                 <div class="row mb-3">
                    <label for="date_fin" class="col-md-4 col-form-label text-md-end">Date de Fin</label>
                    <div class="col-md-6">
                        <input id="date_fin" type="date" class="form-control @error("date_fin") is-invalid @enderror" name="date_fin" value="{{ old("date_fin", $inventaire->date_fin ? $inventaire->date_fin->format("Y-m-d") : null) }}">
                         <div class="form-text">Laissez vide si la session est toujours en cours.</div>
                        @error("date_fin")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                    <div class="col-md-6">
                        <textarea id="description" class="form-control @error("description") is-invalid @enderror" name="description" rows="3">{{ old("description", $inventaire->description) }}</textarea>
                        @error("description")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                 <div class="row mb-3">
                        <label for="statut" class="col-md-4 col-form-label text-md-end">Statut *</label>
                        <div class="col-md-6">
                            <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                <option value="Planifié" {{ old('statut', $inventaire->statut_display) == 'Planifié' ? 'selected' : '' }}>Planifié</option>
                                <option value="En cours" {{ old('statut', $inventaire->statut_display) == 'En cours' ? 'selected' : '' }}>En cours</option>
                                <option value="Terminé" {{ old('statut', $inventaire->statut_display) == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                                <option value="Annulé" {{ old('statut', $inventaire->statut_display) == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('statut')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>


                {{-- Add fields for scope if needed --}}

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Mettre à jour la Session
                        </button>
                        <a href="{{ route("inventaires.index") }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

