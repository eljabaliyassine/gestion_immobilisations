@extends("layouts.app")

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Ajouter des comptages - {{ $inventaire->reference }}</h5>
                        <small class="text-muted">Session d'inventaire du {{ \Carbon\Carbon::parse($inventaire->date_debut)->format('d/m/Y') }}</small>
                    </div>
                    <div>
                        <a href="{{ route('inventaires.show', $inventaire) }}" class="btn btn-sm btn-secondary">Retour</a>
                        <a href="{{ route('inventaires.export.immobilisations', $inventaire) }}?site_id={{ $siteId ?? '' }}&service_id={{ $serviceId ?? '' }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-csv"></i> Exporter CSV
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtres -->
                    <form method="GET" action="{{ route('inventaires.add_count.form', $inventaire) }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="site_id">Site</label>
                                    <select name="site_id" id="site_id" class="form-control">
                                        <option value="">Tous les sites</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ ($siteId ?? '') == $site->id ? 'selected' : '' }}>
                                                {{ $site->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="service_id">Service</label>
                                    <select name="service_id" id="service_id" class="form-control">
                                        <option value="">Tous les services</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ ($serviceId ?? '') == $service->id ? 'selected' : '' }}>
                                                {{ $service->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                @if($siteId || $serviceId)
                                    <a href="{{ route('inventaires.add_count.form', $inventaire) }}" class="btn btn-outline-secondary ml-2">Réinitialiser</a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Message de statut -->
                    <div id="status-message" class="alert alert-success" style="display: none;"></div>
                    <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                    <!-- Tableau des immobilisations -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code Barre</th>
                                    <th>Désignation</th>
                                    <th>Description</th>
                                    <th>Service</th>
                                    <th>Site</th>
                                    <th>Statut du Comptage</th>
                                    <th>Commentaire</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($immobilisations as $immobilisation)
                                    @php
                                        $detail = $detailsExistants[$immobilisation->id] ?? null;
                                        $statutConstate = $detail ? ($statutMappingReverse[$detail->statut_constate] ?? $detail->statut_constate) : 'Non compté';
                                        $commentaire = $detail ? $detail->commentaire : '';
                                    @endphp
                                    <tr id="row-{{ $immobilisation->id }}">
                                        <td>{{ $immobilisation->id }}</td>
                                        <td>{{ $immobilisation->code_barre ?? 'N/A' }}</td>
                                        <td>{{ $immobilisation->designation }}</td>
                                        <td>{{ Str::limit($immobilisation->description, 50) }}</td>
                                        <td>{{ $immobilisation->service ? $immobilisation->service->libelle : 'N/A' }}</td>
                                        <td>{{ $immobilisation->site ? $immobilisation->site->libelle : 'N/A' }}</td>
                                        <td>
                                            <select class="form-control form-control-sm statut-select"
                                                    data-immo-id="{{ $immobilisation->id }}"
                                                    data-original-value="{{ $statutConstate }}">
                                                <option value="Non compté" {{ $statutConstate == 'Non compté' ? 'selected' : '' }}>Non compté</option>
                                                @foreach($statutOptions as $value => $label)
                                                    <option value="{{ $value }}" {{ $statutConstate == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm commentaire-input"
                                                   data-immo-id="{{ $immobilisation->id }}"
                                                   value="{{ $commentaire }}"
                                                   placeholder="Ajouter un commentaire">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success save-btn" 
                                                    data-immo-id="{{ $immobilisation->id }}">
                                                <i class="fas fa-save"></i> Enregistrer
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $immobilisations->appends(['site_id' => $siteId ?? '', 'service_id' => $serviceId ?? ''])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si le token CSRF est disponible
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token non trouvé. Veuillez vous assurer que la balise meta csrf-token est présente dans le layout.');
        showStatusMessage('Erreur de configuration CSRF. Veuillez contacter l\'administrateur.', true);
        return;
    }

    // Fonction pour afficher un message de statut
    function showStatusMessage(message, isError = false) {
        const statusElement = document.getElementById('status-message');
        const errorElement = document.getElementById('error-message');

        if (isError) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            statusElement.style.display = 'none';

            // Masquer après 5 secondes
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 5000);
        } else {
            statusElement.textContent = message;
            statusElement.style.display = 'block';
            errorElement.style.display = 'none';

            // Masquer après 3 secondes
            setTimeout(() => {
                statusElement.style.display = 'none';
            }, 3000);
        }
    }

    // Fonction pour sauvegarder les modifications
    function saveChanges(immoId) {
        const row = document.getElementById(`row-${immoId}`);
        const statutSelect = row.querySelector('.statut-select');
        const commentaireInput = row.querySelector('.commentaire-input');
        const saveBtn = row.querySelector('.save-btn');

        const statut = statutSelect.value;
        const commentaire = commentaireInput.value;

        // Désactiver le bouton pendant la sauvegarde
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

        // Envoyer les données via AJAX
        fetch('{{ route("inventaires.update_count", $inventaire) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                immobilisation_id: immoId,
                statut_constate: statut,
                commentaire: commentaire
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Erreur réseau: ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mettre à jour les valeurs originales
                statutSelect.setAttribute('data-original-value', statut);
                statutSelect.classList.remove('bg-warning');

                // Ajouter une classe pour indiquer que la ligne a été mise à jour
                row.classList.add('table-success');
                setTimeout(() => {
                    row.classList.remove('table-success');
                }, 2000);

                // Afficher un message de succès
                showStatusMessage('Comptage mis à jour avec succès');
            } else {
                showStatusMessage('Erreur lors de la mise à jour du comptage: ' + data.message, true);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showStatusMessage('Une erreur est survenue lors de la mise à jour du comptage: ' + error.message, true);
        })
        .finally(() => {
            // Restaurer le bouton
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
        });
    }

    // Ajouter des écouteurs d'événements aux boutons de sauvegarde
    const saveBtns = document.querySelectorAll('.save-btn');
    saveBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const immoId = this.getAttribute('data-immo-id');
            saveChanges(immoId);
        });
    });

    // Ajouter des écouteurs d'événements pour la touche Entrée dans les champs de commentaire
    const commentaireInputs = document.querySelectorAll('.commentaire-input');
    commentaireInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const immoId = this.getAttribute('data-immo-id');
                saveChanges(immoId);
            }
        });
    });

    // Ajouter des écouteurs d'événements pour le changement de statut
    const statutSelects = document.querySelectorAll('.statut-select');
    statutSelects.forEach(select => {
        select.addEventListener('change', function() {
            const immoId = this.getAttribute('data-immo-id');
            const originalValue = this.getAttribute('data-original-value');
            const currentValue = this.value;

            // Mettre en évidence le changement
            if (originalValue !== currentValue) {
                this.classList.add('bg-warning');
            } else {
                this.classList.remove('bg-warning');
            }
        });
    });
});
</script>
@endsection
