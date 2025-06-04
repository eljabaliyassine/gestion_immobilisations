@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Détails de l'immobilisation</h5>
                    <div>
                        <a href="{{ route('immobilisations.edit', $immobilisation->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('immobilisations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-sm">Informations générales</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Code barre</th>
                                            <td>{{ $immobilisation->code_barre }}</td>
                                        </tr>
                                        <tr>
                                            <th>Désignation</th>
                                            <td>{{ $immobilisation->designation }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $immobilisation->description ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Famille</th>
                                            <td>{{ $immobilisation->famille->libelle ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                <span class="badge bg-{{ $immobilisation->statut == 'En service' ? 'success' : ($immobilisation->statut == 'Cédé' || $immobilisation->statut == 'Rebut' ? 'danger' : 'secondary') }}">
                                                    {{ $immobilisation->statut }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Numéro de série</th>
                                            <td>{{ $immobilisation->numero_serie ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-sm">Localisation</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Site</th>
                                            <td>{{ $immobilisation->site->libelle ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Service</th>
                                            <td>{{ $immobilisation->service->libelle ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <h6 class="text-uppercase text-sm mt-4">Acquisition</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Date acquisition</th>
                                            <td>{{ $immobilisation->date_acquisition ? $immobilisation->date_acquisition->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date mise en service</th>
                                            <td>{{ $immobilisation->date_mise_service ? $immobilisation->date_mise_service->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Valeur acquisition</th>
                                            <td>{{ number_format($immobilisation->valeur_acquisition, 2, ',', ' ') }} DH</td>
                                        </tr>
                                        <tr>
                                            <th>Fournisseur</th>
                                            <td>{{ $immobilisation->fournisseur->nom ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>N° Facture</th>
                                            <td>{{ $immobilisation->numero_facture ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-sm">Amortissement</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th class="w-30">Base amortissement</th>
                                            <td>{{ number_format($immobilisation->base_amortissement, 2, ',', ' ') }} DH</td>
                                        </tr>
                                        <tr>
                                            <th>Méthode</th>
                                            <td>{{ $immobilisation->methode_amortissement == 'lineaire' ? 'Linéaire' : 'Dégressif' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Durée</th>
                                            <td>{{ $immobilisation->duree_amortissement }} ans</td>
                                        </tr>
                                        <tr>
                                            <th>Valeur résiduelle</th>
                                            <td>{{ number_format($immobilisation->valeur_residuelle ?? 0, 2, ',', ' ') }} DH</td>
                                        </tr>
                                        <tr>
                                            <th>VNC actuelle</th>
                                            <td>{{ number_format($immobilisation->vnc, 2, ',', ' ') }} DH</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($immobilisation->photo_path)
                            <h6 class="text-uppercase text-sm">Photo</h6>
                            <div class="text-center">
                                <img src="{{ Storage::url($immobilisation->photo_path) }}" alt="Photo de l'immobilisation" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Onglets pour les informations détaillées -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="amortissement-tab" data-bs-toggle="tab" data-bs-target="#amortissement" type="button" role="tab" aria-controls="amortissement" aria-selected="true">Plan d'amortissement</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="mouvements-tab" data-bs-toggle="tab" data-bs-target="#mouvements" type="button" role="tab" aria-controls="mouvements" aria-selected="false">Mouvements</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="maintenances-tab" data-bs-toggle="tab" data-bs-target="#maintenances" type="button" role="tab" aria-controls="maintenances" aria-selected="false">Maintenances</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contrats-tab" data-bs-toggle="tab" data-bs-target="#contrats" type="button" role="tab" aria-controls="contrats" aria-selected="false">Contrats</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="amortissement" role="tabpanel" aria-labelledby="amortissement-tab">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Année</th>
                                                    <th>Base</th>
                                                    <th>Taux</th>
                                                    <th>Dotation</th>
                                                    <th>Cumul début</th>
                                                    <th>Cumul fin</th>
                                                    <th>VNC fin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($immobilisation->planAmortissements as $plan)
                                                <tr>
                                                    <td>{{ $plan->annee_exercice }}</td>
                                                    <td>{{ number_format($plan->base_amortissable, 2, ',', ' ') }} DH</td>
                                                    <td>{{ number_format($plan->taux_applique * 100, 2, ',', ' ') }} %</td>
                                                    <td>{{ number_format($plan->dotation_annuelle, 2, ',', ' ') }} DH</td>
                                                    <td>{{ number_format($plan->amortissement_cumule_debut, 2, ',', ' ') }} DH</td>
                                                    <td>{{ number_format($plan->amortissement_cumule_fin, 2, ',', ' ') }} DH</td>
                                                    <td>{{ number_format($plan->vna_fin_exercice, 2, ',', ' ') }} DH</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Aucun plan d'amortissement disponible</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="mouvements" role="tabpanel" aria-labelledby="mouvements-tab">
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="{{ route('mouvements.create', ['immobilisation_id' => $immobilisation->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-2"></i>Nouveau mouvement
                                        </a>
                                    </div>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Valeur</th>
                                                    <th>VNC</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($immobilisation->mouvements as $mouvement)
                                                <tr>
                                                    <td>{{ $mouvement->date_mouvement ? $mouvement->date_mouvement->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $mouvement->type_mouvement == 'entree' ? 'success' : ($mouvement->type_mouvement == 'sortie' ? 'danger' : 'info') }}">
                                                            {{ $mouvement->type_formatted }}
                                                        </span>
                                                    </td>
                                                    <td>{{ number_format($mouvement->valeur_mouvement, 2, ',', ' ') }} DH</td>
                                                    <td>{{ number_format($mouvement->valeur_nette_comptable, 2, ',', ' ') }} DH</td>
                                                    <td>{{ Str::limit($mouvement->description, 30) }}</td>
                                                    <td>
                                                        <a href="{{ route('mouvements.show', $mouvement->id) }}" class="btn btn-sm btn-info mx-1" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucun mouvement enregistré</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="maintenances" role="tabpanel" aria-labelledby="maintenances-tab">
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="{{ route('maintenances.create', ['immobilisation_id' => $immobilisation->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-2"></i>Nouvelle maintenance
                                        </a>
                                    </div>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Prestataire</th>
                                                    <th>Coût</th>
                                                    <th>Est charge</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($immobilisation->maintenances as $maintenance)
                                                <tr>
                                                    <td>{{ $maintenance->date_intervention ? $maintenance->date_intervention->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>{{ $maintenance->type }}</td>
                                                    <td>{{ $maintenance->prestataire->nom ?? 'N/A' }}</td>
                                                    <td>{{ number_format($maintenance->cout, 2, ',', ' ') }} DH</td>
                                                    <td>
                                                        <span class="badge bg-{{ $maintenance->est_charge ? 'primary' : 'warning' }}">
                                                            {{ $maintenance->est_charge ? 'Charge' : 'À capitaliser' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('maintenances.show', $maintenance->id) }}" class="btn btn-sm btn-info mx-1" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucune maintenance enregistrée</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="contrats" role="tabpanel" aria-labelledby="contrats-tab">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Référence</th>
                                                    <th>Type</th>
                                                    <th>Prestataire</th>
                                                    <th>Période</th>
                                                    <th>Montant</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($immobilisation->contrats as $contrat)
                                                <tr>
                                                    <td>{{ $contrat->reference }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary')) }}">
                                                            {{ ucfirst($contrat->type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $contrat->prestataire->nom ?? 'N/A' }}</td>
                                                    <td>
                                                        {{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : 'N/A' }}
                                                        @if($contrat->date_fin)
                                                            au {{ $contrat->date_fin->format('d/m/Y') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($contrat->montant_periodique, 2, ',', ' ') }} DH</td>
                                                    <td>
                                                        <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-sm btn-info mx-1" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucun contrat associé</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
