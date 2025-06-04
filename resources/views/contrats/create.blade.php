@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Nouveau contrat</h5>
                    <a href="{{ route('contrats.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
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

                    <form action="{{ route('contrats.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference" class="form-control-label">Référence <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="reference" name="reference" value="{{ old('reference') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-control-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="location" {{ old('type') == 'location' ? 'selected' : '' }}>Location</option>
                                        <option value="leasing" {{ old('type') == 'leasing' ? 'selected' : '' }}>Crédit-bail</option>
                                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prestataire_id" class="form-control-label">Prestataire</label>
                                    <select class="form-control" id="prestataire_id" name="prestataire_id">
                                        <option value="">Sélectionner un prestataire</option>
                                        @foreach($prestataires as $prestataire)
                                            <option value="{{ $prestataire->id }}" {{ old('prestataire_id') == $prestataire->id ? 'selected' : '' }}>
                                                {{ $prestataire->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fournisseur_id" class="form-control-label">Fournisseur</label>
                                    <select class="form-control" id="fournisseur_id" name="fournisseur_id">
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                                {{ $fournisseur->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_debut" class="form-control-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_fin" class="form-control-label">Date de fin</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ old('date_fin') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_prochaine_echeance" class="form-control-label">Date prochaine échéance</label>
                                    <input type="date" class="form-control" id="date_prochaine_echeance" name="date_prochaine_echeance" value="{{ old('date_prochaine_echeance') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="montant_periodique" class="form-control-label">Montant périodique (DH) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="montant_periodique" name="montant_periodique" value="{{ old('montant_periodique') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periodicite" class="form-control-label">Périodicité <span class="text-danger">*</span></label>
                                    <select class="form-control" id="periodicite" name="periodicite" required>
                                        <option value="">Sélectionner une périodicité</option>
                                        <option value="mensuel" {{ old('periodicite') == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                        <option value="trimestriel" {{ old('periodicite') == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                                        <option value="semestriel" {{ old('periodicite') == 'semestriel' ? 'selected' : '' }}>Semestriel</option>
                                        <option value="annuel" {{ old('periodicite') == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="statut" class="form-control-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-control" id="statut" name="statut" required>
                                        <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                        <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="document_path" class="form-control-label">Document (PDF, DOC, DOCX)</label>
                            <input type="file" class="form-control" id="document_path" name="document_path">
                        </div>
                        
                        <!-- Champs spécifiques au crédit-bail -->
                        <div id="credit-bail-fields" class="mt-4" style="display: none;">
                            <h6 class="mb-3">Détails du crédit-bail</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="duree_mois" class="form-control-label">Durée (mois) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="duree_mois" name="duree_mois" value="{{ old('duree_mois') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="valeur_residuelle" class="form-control-label">Valeur résiduelle (DH) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" id="valeur_residuelle" name="valeur_residuelle" value="{{ old('valeur_residuelle', 0) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="taux_interet_periodique" class="form-control-label">Taux d'intérêt périodique (%)</label>
                                        <input type="number" step="0.001" class="form-control" id="taux_interet_periodique" name="taux_interet_periodique" value="{{ old('taux_interet_periodique') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
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
        // Afficher/masquer les champs spécifiques au crédit-bail
        const typeSelect = document.getElementById('type');
        const creditBailFields = document.getElementById('credit-bail-fields');
        
        function toggleCreditBailFields() {
            if (typeSelect.value === 'leasing') {
                creditBailFields.style.display = 'block';
                document.getElementById('duree_mois').setAttribute('required', 'required');
                document.getElementById('valeur_residuelle').setAttribute('required', 'required');
            } else {
                creditBailFields.style.display = 'none';
                document.getElementById('duree_mois').removeAttribute('required');
                document.getElementById('valeur_residuelle').removeAttribute('required');
            }
        }
        
        // Initialiser l'affichage
        toggleCreditBailFields();
        
        // Écouter les changements
        typeSelect.addEventListener('change', toggleCreditBailFields);
        
        // Remplir automatiquement la date de prochaine échéance
        const dateDebutInput = document.getElementById('date_debut');
        const dateProchainEcheanceInput = document.getElementById('date_prochaine_echeance');
        const periodiciteSelect = document.getElementById('periodicite');
        
        function updateDateProchainEcheance() {
            if (dateDebutInput.value) {
                const dateDebut = new Date(dateDebutInput.value);
                let dateProchainEcheance = new Date(dateDebut);
                
                switch (periodiciteSelect.value) {
                    case 'mensuel':
                        dateProchainEcheance.setMonth(dateProchainEcheance.getMonth() + 1);
                        break;
                    case 'trimestriel':
                        dateProchainEcheance.setMonth(dateProchainEcheance.getMonth() + 3);
                        break;
                    case 'semestriel':
                        dateProchainEcheance.setMonth(dateProchainEcheance.getMonth() + 6);
                        break;
                    case 'annuel':
                        dateProchainEcheance.setFullYear(dateProchainEcheance.getFullYear() + 1);
                        break;
                }
                
                // Formater la date au format YYYY-MM-DD
                const year = dateProchainEcheance.getFullYear();
                const month = String(dateProchainEcheance.getMonth() + 1).padStart(2, '0');
                const day = String(dateProchainEcheance.getDate()).padStart(2, '0');
                dateProchainEcheanceInput.value = `${year}-${month}-${day}`;
            }
        }
        
        dateDebutInput.addEventListener('change', updateDateProchainEcheance);
        periodiciteSelect.addEventListener('change', updateDateProchainEcheance);
    });
</script>
@endsection
