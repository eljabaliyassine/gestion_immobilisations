@extends("layouts.app")

@section("title", "Modifier Immobilisation")

@section("content")
<div class="container">
    <h1>Modifier l'Immobilisation</h1>

    <form action="{{ route("immobilisations.update", $immobilisation) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")

        <div class="card mb-3">
            <div class="card-header">Informations Générales</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code_barre" class="form-label">Code Barre</label>
                        <input type="text" class="form-control @error("code_barre") is-invalid @enderror" id="code_barre" name="code_barre" value="{{ old("code_barre", $immobilisation->code_barre) }}">
                        @error("code_barre") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="designation" class="form-label">Désignation *</label>
                        <input type="text" class="form-control @error("designation") is-invalid @enderror" id="designation" name="designation" value="{{ old("designation", $immobilisation->designation) }}" required autofocus>
                        @error("designation") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error("description") is-invalid @enderror" id="description" name="description" rows="3">{{ old("description", $immobilisation->description) }}</textarea>
                        @error("description") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="famille_id" class="form-label">Famille *</label>
                        <select class="form-select @error("famille_id") is-invalid @enderror" id="famille_id" name="famille_id" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach ($familles as $famille)
                                <option value="{{ $famille->id }}" {{ old("famille_id", $immobilisation->famille_id) == $famille->id ? "selected" : "" }}>{{ $famille->libelle }}</option>
                            @endforeach
                        </select>
                        @error("famille_id") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="statut" class="form-label">Statut *</label>
                        <select class="form-select @error("statut") is-invalid @enderror" id="statut" name="statut" required>
                            <option value="En service" {{ old("statut", $immobilisation->statut) == "En service" ? "selected" : "" }}>En service</option>
                            <option value="En stock" {{ old("statut", $immobilisation->statut) == "En stock" ? "selected" : "" }}>En stock</option>
                            <option value="En réparation" {{ old("statut", $immobilisation->statut) == "En réparation" ? "selected" : "" }}>En réparation</option>
                            <option value="Cédé" {{ old("statut", $immobilisation->statut) == "Cédé" ? "selected" : "" }}>Cédé</option>
                            <option value="Rebut" {{ old("statut", $immobilisation->statut) == "Rebut" ? "selected" : "" }}>Rebut</option>
                        </select>
                        @error("statut") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="numero_serie" class="form-label">Numéro de Série</label>
                        <input type="text" class="form-control @error("numero_serie") is-invalid @enderror" id="numero_serie" name="numero_serie" value="{{ old("numero_serie", $immobilisation->numero_serie) }}">
                        @error("numero_serie") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control @error("photo") is-invalid @enderror" id="photo" name="photo">
                        @if($immobilisation->photo_path)
                            <small class="text-muted">Photo actuelle : <a href="{{ Storage::url($immobilisation->photo_path) }}" target="_blank">Voir</a></small>
                        @endif
                        @error("photo") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Acquisition & Valeur</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="date_acquisition" class="form-label">Date Acquisition *</label>
                        <input type="date" class="form-control @error("date_acquisition") is-invalid @enderror" id="date_acquisition" name="date_acquisition" value="{{ old("date_acquisition", $immobilisation->date_acquisition?->format('Y-m-d')) }}" required>
                        @error("date_acquisition") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_mise_service" class="form-label">Date Mise en Service</label>
                        <input type="date" class="form-control @error("date_mise_service") is-invalid @enderror" id="date_mise_service" name="date_mise_service" value="{{ old("date_mise_service", $immobilisation->date_mise_service?->format('Y-m-d')) }}">
                        @error("date_mise_service") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="valeur_acquisition" class="form-label">Valeur Acquisition (HT) *</label>
                        <input type="number" step="0.01" class="form-control @error("valeur_acquisition") is-invalid @enderror" id="valeur_acquisition" name="valeur_acquisition" value="{{ old("valeur_acquisition", $immobilisation->valeur_acquisition) }}" required>
                        @error("valeur_acquisition") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tva_deductible" class="form-label">TVA Déductible</label>
                        <input type="number" step="0.01" class="form-control @error("tva_deductible") is-invalid @enderror" id="tva_deductible" name="tva_deductible" value="{{ old("tva_deductible", $immobilisation->tva_deductible) }}">
                        @error("tva_deductible") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="fournisseur_id" class="form-label">Fournisseur</label>
                        <select class="form-select @error("fournisseur_id") is-invalid @enderror" id="fournisseur_id" name="fournisseur_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ old("fournisseur_id", $immobilisation->fournisseur_id) == $fournisseur->id ? "selected" : "" }}>{{ $fournisseur->nom }}</option>
                            @endforeach
                        </select>
                        @error("fournisseur_id") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="numero_facture" class="form-label">Numéro Facture</label>
                        <input type="text" class="form-control @error("numero_facture") is-invalid @enderror" id="numero_facture" name="numero_facture" value="{{ old("numero_facture", $immobilisation->numero_facture) }}">
                        @error("numero_facture") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Localisation</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="site_id" class="form-label">Site *</label>
                        <select class="form-select @error("site_id") is-invalid @enderror" id="site_id" name="site_id" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" {{ old("site_id", $immobilisation->site_id) == $site->id ? "selected" : "" }}>{{ $site->libelle }}</option>
                            @endforeach
                        </select>
                        @error("site_id") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="service_id" class="form-label">Service *</label>
                        <select class="form-select @error("service_id") is-invalid @enderror" id="service_id" name="service_id" required>
                            <option value="">-- Chargement... --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ old("service_id", $immobilisation->service_id) == $service->id ? "selected" : "" }}>{{ $service->libelle }}</option>
                            @endforeach
                        </select>
                        @error("service_id") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="emplacement" class="form-label">Emplacement Précis</label>
                        <input type="text" class="form-control @error("emplacement") is-invalid @enderror" id="emplacement" name="emplacement" value="{{ old("emplacement", $immobilisation->emplacement) }}">
                        @error("emplacement") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Amortissement (Informations héritées de la famille)</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Compte Immobilisation</label>
                        <input type="text" class="form-control" id="comptecompta_immobilisation_display" readonly disabled
                               value="{{ $immobilisation->compteImmobilisation ? $immobilisation->compteImmobilisation->numero . ' - ' . $immobilisation->compteImmobilisation->libelle : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Amortissement</label>
                        <input type="text" class="form-control" id="comptecompta_amortissement_display" readonly disabled
                               value="{{ $immobilisation->compteAmortissement ? $immobilisation->compteAmortissement->numero . ' - ' . $immobilisation->compteAmortissement->libelle : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Dotation</label>
                        <input type="text" class="form-control" id="comptecompta_dotation_display" readonly disabled
                               value="{{ $immobilisation->compteDotation ? $immobilisation->compteDotation->numero . ' - ' . $immobilisation->compteDotation->libelle : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Durée Amortissement</label>
                        <input type="text" class="form-control" id="duree_amortissement_display" readonly disabled
                               value="{{ $immobilisation->duree_amortissement ? $immobilisation->duree_amortissement . ' ans' : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Méthode Amortissement</label>
                        <input type="text" class="form-control" id="methode_amortissement_display" readonly disabled
                               value="{{ $immobilisation->methode_amortissement ? ucfirst($immobilisation->methode_amortissement) : '' }}">
                    </div>
                    <div class="col-md-6">
                        <label for="base_amortissement" class="form-label">Base Amortissement *</label>
                        <input type="number" step="0.01" class="form-control @error("base_amortissement") is-invalid @enderror" id="base_amortissement" name="base_amortissement" value="{{ old("base_amortissement", $immobilisation->base_amortissement) }}" required>
                        @error("base_amortissement") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="comptecompta_tva_id" class="form-label">Compte TVA</label>
                        <select class="form-select @error("comptecompta_tva_id") is-invalid @enderror" id="comptecompta_tva_id" name="comptecompta_tva_id">
                            <option value="">-- Sélectionner --</option>
                            @foreach ($comptescompta as $compte)
                                <option value="{{ $compte->id }}" {{ old("comptecompta_tva_id", $immobilisation->comptecompta_tva_id) == $compte->id ? "selected" : "" }}>{{ $compte->numero }} - {{ $compte->libelle }}</option>
                            @endforeach
                        </select>
                        @error("comptecompta_tva_id") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Documents</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="document_path" class="form-label">Document (PDF, DOC, DOCX)</label>
                        <input type="file" class="form-control @error("document_path") is-invalid @enderror" id="document_path" name="document_path">
                        @if($immobilisation->document_path)
                            <small class="text-muted">Document actuel : <a href="{{ Storage::url($immobilisation->document_path) }}" target="_blank">Télécharger</a></small>
                        @endif
                        @error("document_path") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-2 text-center">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route("immobilisations.index") }}" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push("scripts")
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Token CSRF pour les requêtes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                     document.querySelector('input[name="_token"]')?.value;

    // Valeurs actuelles pour la restauration
    const currentSiteId = @json(old("site_id", $immobilisation->site_id));
    const currentServiceId = @json(old("service_id", $immobilisation->service_id));

    console.log('CSRF Token:', csrfToken ? 'Trouvé' : 'Manquant'); // Debug
    console.log('Site actuel:', currentSiteId); // Debug
    console.log('Service actuel:', currentServiceId); // Debug

    // AUTO-REMPLISSAGE: Date de mise en service = Date d'acquisition (seulement si vide)
    document.getElementById("date_acquisition").addEventListener("change", function() {
        const dateMiseService = document.getElementById("date_mise_service");
        if (!dateMiseService.value) {
            dateMiseService.value = this.value;
        }
    });

    // FILTRAGE DYNAMIQUE: Services par site sélectionné - SOLUTION ALTERNATIVE
    document.getElementById("site_id").addEventListener("change", function() {
        const siteId = this.value;
        const serviceSelect = document.getElementById("service_id");

        console.log('Site sélectionné:', siteId); // Debug

        // Réinitialiser le select des services
        serviceSelect.innerHTML = "<option value=''>-- Chargement... --</option>";
        serviceSelect.disabled = true;

        if (!siteId) {
            serviceSelect.innerHTML = "<option value=''>-- Sélectionner d'abord un site --</option>";
            serviceSelect.disabled = false;
            return;
        }

        // SOLUTION ALTERNATIVE: Utilise une route POST simple
        const formData = new FormData();
        formData.append('site_id', siteId);
        formData.append('_token', csrfToken);

        fetch('{{ route("immobilisations.ajax.services-by-site") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Réponse services:', response.status); // Debug
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Services reçus:', data); // Debug
            serviceSelect.innerHTML = "<option value=''>-- Sélectionner --</option>";

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(service => {
                    const option = document.createElement("option");
                    option.value = service.id;
                    option.textContent = service.libelle;
                    // Restaurer la sélection actuelle
                    if (currentServiceId == service.id) {
                        option.selected = true;
                    }
                    serviceSelect.appendChild(option);
                });
            } else {
                serviceSelect.innerHTML += "<option value='' disabled>Aucun service trouvé</option>";
            }
            serviceSelect.disabled = false;
        })
        .catch(error => {
            console.error("Erreur lors du chargement des services:", error);
            serviceSelect.innerHTML = "<option value=''>Erreur de chargement</option>";
            serviceSelect.disabled = false;
        });
    });

    // ACTUALISATION DYNAMIQUE: Informations héritées de la famille lors du changement - SOLUTION ALTERNATIVE
    document.getElementById("famille_id").addEventListener("change", function() {
        const familleId = this.value;

        console.log('Famille sélectionnée:', familleId); // Debug

        if (!familleId) {
            // Réinitialiser les champs d'affichage
            document.getElementById("comptecompta_immobilisation_display").value = "";
            document.getElementById("comptecompta_amortissement_display").value = "";
            document.getElementById("comptecompta_dotation_display").value = "";
            document.getElementById("duree_amortissement_display").value = "";
            document.getElementById("methode_amortissement_display").value = "";
            return;
        }

        // SOLUTION ALTERNATIVE: Utilise une route POST simple
        const formData = new FormData();
        formData.append('famille_id', familleId);
        formData.append('_token', csrfToken);

        fetch('{{ route("immobilisations.ajax.famille-info") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Réponse famille:', response.status); // Debug
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Infos famille reçues:', data); // Debug

            // Remplir les champs avec les données reçues
            document.getElementById("comptecompta_immobilisation_display").value = data.comptecompta_immobilisation || '';
            document.getElementById("comptecompta_amortissement_display").value = data.comptecompta_amortissement || '';
            document.getElementById("comptecompta_dotation_display").value = data.comptecompta_dotation || '';
            document.getElementById("duree_amortissement_display").value = data.duree_amortissement_par_defaut || '';
            document.getElementById("methode_amortissement_display").value = data.methode_amortissement_par_defaut || '';
        })
        .catch(error => {
            console.error("Erreur lors du chargement des informations de la famille:", error);
        });
    });

    // Charger les services du site actuel au chargement de la page
    if (currentSiteId) {
        console.log('Chargement initial des services pour le site:', currentSiteId); // Debug
        setTimeout(() => {
            document.getElementById("site_id").dispatchEvent(new Event('change'));
        }, 100);
    }
});
</script>
@endpush

