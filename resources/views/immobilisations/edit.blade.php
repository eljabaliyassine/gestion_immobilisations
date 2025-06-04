@extends("layouts.app")

@section("title", "Modifier Immobilisation : " . $immobilisation->designation)

@section("content")
<div class="container">
    <h1>Modifier l'Immobilisation : {{ $immobilisation->designation }}</h1>

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
                            <option value="En service" {{ old("statut", $immobilisation->statut) == "En service" || old("statut", $immobilisation->statut) == "actif" ? "selected" : "" }}>En service</option>
                            <option value="En stock" {{ old("statut", $immobilisation->statut) == "En stock" ? "selected" : "" }}>En stock</option>
                            <option value="En réparation" {{ old("statut", $immobilisation->statut) == "En réparation" ? "selected" : "" }}>En réparation</option>
                            <option value="Cédé" {{ old("statut", $immobilisation->statut) == "Cédé" ? "selected" : "" }}>Cédé</option>
                            <option value="Rebut" {{ old("statut", $immobilisation->statut) == "Rebut" ? "selected" : "" }}>Rebut</option>
                        </select>
                        @error("statut") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control @error("photo") is-invalid @enderror" id="photo" name="photo">
                        @error("photo") <span class="invalid-feedback">{{ $message }}</span> @enderror
                        @if($immobilisation->photo_path)
                            <div class="mt-2">
                                <img src="{{ Storage::url($immobilisation->photo_path) }}" alt="Photo actuelle" height="100">
                                <small class="d-block">Photo actuelle. Télécharger un nouveau fichier pour remplacer.</small>
                            </div>
                        @endif
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
                        <input type="date" class="form-control @error("date_acquisition") is-invalid @enderror" id="date_acquisition" name="date_acquisition" value="{{ old("date_acquisition", $immobilisation->date_acquisition ? $immobilisation->date_acquisition->format("Y-m-d") : null) }}" required>
                        @error("date_acquisition") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_mise_service" class="form-label">Date Mise en Service</label>
                        <input type="date" class="form-control @error("date_mise_service") is-invalid @enderror" id="date_mise_service" name="date_mise_service" value="{{ old("date_mise_service", $immobilisation->date_mise_service ? $immobilisation->date_mise_service->format("Y-m-d") : null) }}">
                        @error("date_mise_service") <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="valeur_acquisition" class="form-label">Valeur Acquisition (HT) *</label>
                        <input type="number" step="0.01" class="form-control @error("valeur_acquisition") is-invalid @enderror" id="valeur_acquisition" name="valeur_acquisition" value="{{ old("valeur_acquisition", $immobilisation->valeur_acquisition) }}" required>
                        @error("valeur_acquisition") <span class="invalid-feedback">{{ $message }}</span> @enderror
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
                            <option value="">-- Sélectionner --</option>
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
                        <input type="text" class="form-control" id="comptecompta_immobilisation_display" value="{{ $immobilisation->compteImmobilisation ? $immobilisation->compteImmobilisation->numero . ' - ' . $immobilisation->compteImmobilisation->libelle : 'Non défini' }}" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Amortissement</label>
                        <input type="text" class="form-control" id="comptecompta_amortissement_display" value="{{ $immobilisation->compteAmortissement ? $immobilisation->compteAmortissement->numero . ' - ' . $immobilisation->compteAmortissement->libelle : 'Non défini' }}" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Compte Dotation</label>
                        <input type="text" class="form-control" id="comptecompta_dotation_display" value="{{ $immobilisation->compteDotation ? $immobilisation->compteDotation->numero . ' - ' . $immobilisation->compteDotation->libelle : 'Non défini' }}" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Durée Amortissement</label>
                        <input type="text" class="form-control" id="duree_amortissement_display" value="{{ $immobilisation->duree_amortissement ? $immobilisation->duree_amortissement . ' ans' : 'Non défini' }}" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Méthode Amortissement</label>
                        <input type="text" class="form-control" id="methode_amortissement_display" value="{{ $immobilisation->methode_amortissement === 'lineaire' ? 'Linéaire' : ($immobilisation->methode_amortissement === 'degressif' ? 'Dégressif' : 'Non défini') }}" readonly disabled>
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
    // Gestion de la date de mise en service auto-remplie
    document.getElementById("date_acquisition").addEventListener("change", function() {
        const dateMiseService = document.getElementById("date_mise_service");
        if (!dateMiseService.value) {
            dateMiseService.value = this.value;
        }
    });

    const siteSelect = document.getElementById("site_id");
    const serviceSelect = document.getElementById("service_id");
    const initialSiteId = "{{ old("site_id", $immobilisation->site_id) }}";
    const initialServiceId = "{{ old("service_id", $immobilisation->service_id) }}";
    const dossierId = {{ session("dossier_id") }};

    function fetchServices(siteId) {
        serviceSelect.innerHTML = "<option value=''>-- Chargement... --</option>";
        if (!siteId) {
            loadAllServices();
            return;
        }

        fetch(`/api/dossiers/${dossierId}/sites/${siteId}/services`)
            .then(response => response.json())
            .then(data => {
                serviceSelect.innerHTML = "<option value=''>-- Sélectionner --</option>";
                data.forEach(service => {
                    const option = document.createElement("option");
                    option.value = service.id;
                    option.textContent = service.libelle;
                    // Select the initial service if it matches
                    if (service.id == initialServiceId && siteId == initialSiteId) {
                        option.selected = true;
                    }
                    serviceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching services:", error);
                serviceSelect.innerHTML = "<option value=''>Erreur chargement</option>";
                loadAllServices(); // Fallback to loading all services
            });
    }

    // Fonction pour charger tous les services du dossier courant
    function loadAllServices() {
        const serviceSelect = document.getElementById("service_id");
        
        fetch(`/api/dossiers/${dossierId}/services`)
            .then(response => response.json())
            .then(data => {
                serviceSelect.innerHTML = "<option value=''>-- Sélectionner --</option>";
                data.forEach(service => {
                    const option = document.createElement("option");
                    option.value = service.id;
                    option.textContent = service.libelle;
                    if (service.id == initialServiceId) {
                        option.selected = true;
                    }
                    serviceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching all services:", error);
                serviceSelect.innerHTML = "<option value=''>Erreur chargement</option>";
            });
    }

    siteSelect.addEventListener("change", function() {
        fetchServices(this.value);
    });

    // Gestion de l'affichage des informations héritées de la famille
    document.getElementById("famille_id").addEventListener("change", function() {
        const familleId = this.value;
        
        if (!familleId) {
            return;
        }

        // Récupérer les informations de la famille sélectionnée
        fetch(`/api/familles/${familleId}/info`)
            .then(response => response.json())
            .then(data => {
                // Afficher les informations dans les champs en lecture seule
                document.getElementById("comptecompta_immobilisation_display").value = data.comptecompta_immobilisation || "Non défini";
                document.getElementById("comptecompta_amortissement_display").value = data.comptecompta_amortissement || "Non défini";
                document.getElementById("comptecompta_dotation_display").value = data.comptecompta_dotation || "Non défini";
                document.getElementById("duree_amortissement_display").value = data.duree_amortissement ? data.duree_amortissement + " ans" : "Non défini";
                document.getElementById("methode_amortissement_display").value = data.methode_amortissement === "lineaire" ? "Linéaire" : 
                                                                                (data.methode_amortissement === "degressif" ? "Dégressif" : "Non défini");
            })
            .catch(error => {
                console.error("Error fetching famille info:", error);
            });
    });

    // Initial load of services
    document.addEventListener("DOMContentLoaded", function() {
        // Charger tous les services du dossier courant par défaut
        loadAllServices();
        
        const familleSelect = document.getElementById("famille_id");
        if (familleSelect.value) {
            familleSelect.dispatchEvent(new Event("change"));
        }
    });
</script>
@endpush
