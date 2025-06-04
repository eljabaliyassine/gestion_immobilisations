@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Créer un nouveau dossier</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dossiers.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="societe_id" class="form-label">Société</label>
                                <select name="societe_id" id="societe_id" class="form-control @error('societe_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner une société</option>
                                    @foreach($societes as $societe)
                                        <option value="{{ $societe->id }}" {{ old('societe_id') == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->nom }} ({{ $societe->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('societe_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="client_id" class="form-label">Client</label>
                                <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}"
                                                data-societe-id="{{ $client->societe_id }}"
                                                {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }} ({{ $client->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="libelle" class="form-label">Libellé</label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror" id="libelle" name="libelle" value="{{ old('libelle') }}" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="exercice_debut" class="form-label">Début d'exercice</label>
                                <input type="date" class="form-control @error('exercice_debut') is-invalid @enderror" id="exercice_debut" name="exercice_debut" value="{{ old('exercice_debut') }}">
                                @error('exercice_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="exercice_fin" class="form-label">Fin d'exercice</label>
                                <input type="date" class="form-control @error('exercice_fin') is-invalid @enderror" id="exercice_fin" name="exercice_fin" value="{{ old('exercice_fin') }}">
                                @error('exercice_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="est_cloture" name="est_cloture" value="1" {{ old('est_cloture') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="est_cloture">
                                        Dossier clôturé
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="date_cloture" class="form-label">Date de clôture</label>
                                <input type="date" class="form-control @error('date_cloture') is-invalid @enderror" id="date_cloture" name="date_cloture" value="{{ old('date_cloture') }}">
                                @error('date_cloture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <a href="{{ route('dossiers.index') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const societeSelect = document.getElementById('societe_id');
        const clientSelect = document.getElementById('client_id');

        // Sauvegarder toutes les options des clients au chargement
        const allClientOptions = Array.from(clientSelect.querySelectorAll('option')).slice(1); // Exclure l'option par défaut

        societeSelect.addEventListener('change', function() {
            const societeId = this.value;

            // Vider la liste des clients (garder seulement l'option par défaut)
            clientSelect.innerHTML = '<option value="">Sélectionner un client</option>';

            if (societeId) {
                // Filtrer et ajouter les clients correspondants à la société sélectionnée
                allClientOptions.forEach(option => {
                    if (option.dataset.societeId === societeId) {
                        clientSelect.appendChild(option.cloneNode(true));
                    }
                });
            } else {
                // Si aucune société n'est sélectionnée, remettre tous les clients
                allClientOptions.forEach(option => {
                    clientSelect.appendChild(option.cloneNode(true));
                });
            }
        });

        // Déclencher le filtrage au chargement si une société est déjà sélectionnée (old input)
        if (societeSelect.value) {
            societeSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
