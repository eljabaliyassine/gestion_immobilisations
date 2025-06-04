@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier le dossier</h5>
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

                    <form action="{{ route('dossiers.update', $dossier->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="societe_id" class="form-label">Société</label>
                                <select name="societe_id" id="societe_id" class="form-control @error('societe_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner une société</option>
                                    @foreach($societes as $societe)
                                        <option value="{{ $societe->id }}" {{ old('societe_id', $dossier->societe_id) == $societe->id ? 'selected' : '' }}>
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
                                        <option value="{{ $client->id }}" {{ old('client_id', $dossier->client_id) == $client->id ? 'selected' : '' }}>
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
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $dossier->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $dossier->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="libelle" class="form-label">Libellé</label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror" id="libelle" name="libelle" value="{{ old('libelle', $dossier->libelle) }}" required>
                                @error('libelle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="exercice_debut" class="form-label">Début d'exercice</label>
                                <input type="date" class="form-control @error('exercice_debut') is-invalid @enderror" id="exercice_debut" name="exercice_debut" value="{{ old('exercice_debut', $dossier->exercice_debut ? $dossier->exercice_debut->format('Y-m-d') : '') }}">
                                @error('exercice_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="exercice_fin" class="form-label">Fin d'exercice</label>
                                <input type="date" class="form-control @error('exercice_fin') is-invalid @enderror" id="exercice_fin" name="exercice_fin" value="{{ old('exercice_fin', $dossier->exercice_fin ? $dossier->exercice_fin->format('Y-m-d') : '') }}">
                                @error('exercice_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $dossier->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="est_cloture" name="est_cloture" value="1" {{ old('est_cloture', $dossier->est_cloture) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="est_cloture">
                                        Dossier clôturé
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="date_cloture" class="form-label">Date de clôture</label>
                                <input type="date" class="form-control @error('date_cloture') is-invalid @enderror" id="date_cloture" name="date_cloture" value="{{ old('date_cloture', $dossier->date_cloture ? $dossier->date_cloture->format('Y-m-d') : '') }}">
                                @error('date_cloture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
        // Filtrer les clients en fonction de la société sélectionnée
        const societeSelect = document.getElementById('societe_id');
        const clientSelect = document.getElementById('client_id');
        const clientOptions = Array.from(clientSelect.options);
        
        societeSelect.addEventListener('change', function() {
            const societeId = this.value;
            
            // Réinitialiser la liste des clients
            clientSelect.innerHTML = '<option value="">Sélectionner un client</option>';
            
            if (societeId) {
                // Filtrer les clients par société
                const filteredClients = clientOptions.filter(option => {
                    return option.dataset.societeId === societeId;
                });
                
                // Ajouter les clients filtrés
                filteredClients.forEach(option => {
                    clientSelect.appendChild(option.cloneNode(true));
                });
            }
        });
    });
</script>
@endsection
