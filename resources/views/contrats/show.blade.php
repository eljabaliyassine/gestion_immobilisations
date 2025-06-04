@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Détails du contrat</h5>
                    <div>
                        <a href="{{ route('contrats.edit', $contrat->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('contrats.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Informations du contrat</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Référence :</div>
                                        <div class="col-md-8">{{ $contrat->reference }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Type :</div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($contrat->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Période :</div>
                                        <div class="col-md-8">
                                            @if($contrat->date_debut)
                                                @if(is_string($contrat->date_debut))
                                                    {{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}
                                                @else
                                                    {{ $contrat->date_debut->format('d/m/Y') }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                            
                                            @if($contrat->date_fin)
                                                au 
                                                @if(is_string($contrat->date_fin))
                                                    {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}
                                                @else
                                                    {{ $contrat->date_fin->format('d/m/Y') }}
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Montant périodique :</div>
                                        <div class="col-md-8">{{ number_format($contrat->montant_periodique ?? 0, 2, ',', ' ') }} DH</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Périodicité :</div>
                                        <div class="col-md-8">{{ ucfirst($contrat->periodicite ?? '-') }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Statut :</div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $contrat->statut == 'actif' ? 'success' : ($contrat->statut == 'inactif' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($contrat->statut) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Prestataire et fournisseur</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Prestataire :</div>
                                        <div class="col-md-8">{{ $contrat->prestataire ? $contrat->prestataire->nom : '-' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Fournisseur :</div>
                                        <div class="col-md-8">{{ $contrat->fournisseur ? $contrat->fournisseur->nom : '-' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Créé le :</div>
                                        <div class="col-md-8">
                                            @if($contrat->created_at)
                                                @if(is_string($contrat->created_at))
                                                    {{ \Carbon\Carbon::parse($contrat->created_at)->format('d/m/Y H:i') }}
                                                @else
                                                    {{ $contrat->created_at->format('d/m/Y H:i') }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Modifié le :</div>
                                        <div class="col-md-8">
                                            @if($contrat->updated_at)
                                                @if(is_string($contrat->updated_at))
                                                    {{ \Carbon\Carbon::parse($contrat->updated_at)->format('d/m/Y H:i') }}
                                                @else
                                                    {{ $contrat->updated_at->format('d/m/Y H:i') }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Description</h6>
                                </div>
                                <div class="card-body">
                                    {{ $contrat->description ?? 'Aucune description' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h6>Immobilisations liées ({{ $immobilisations->count() }})</h6>
                                    <a href="{{ route('contrats.immobilisations', $contrat->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-link me-2"></i>Gérer les immobilisations
                                    </a>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Désignation</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Famille</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Localisation</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($immobilisations as $immobilisation)
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="d-flex px-3 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm">{{ $immobilisation->code_barre ?? $immobilisation->code }}</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <p class="text-sm font-weight-bold mb-0">{{ $immobilisation->designation }}</p>
                                                        </td>
                                                        <td class="align-middle">
                                                            <p class="text-sm font-weight-bold mb-0">{{ $immobilisation->famille ? $immobilisation->famille->libelle : '-' }}</p>
                                                        </td>
                                                        <td class="align-middle">
                                                            <p class="text-sm font-weight-bold mb-0">
                                                                {{ $immobilisation->site ? $immobilisation->site->libelle : '-' }}
                                                                @if($immobilisation->service)
                                                                    / {{ $immobilisation->service->libelle }}
                                                                @endif
                                                            </p>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="d-flex">
                                                                <a href="{{ route('immobilisations.show', $immobilisation->id) }}" class="btn btn-sm btn-info me-2" title="Voir">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <form action="{{ route('contrats.detachImmobilisation', [$contrat->id, $immobilisation->id]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger" title="Retirer" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette immobilisation du contrat ?')">
                                                                        <i class="fas fa-unlink"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">Aucune immobilisation liée à ce contrat</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($contrat->type == 'leasing' && isset($detailCreditBail) && isset($echeances))
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h6>Détails du crédit-bail</h6>
                                    <a href="{{ route('contrats.echeances', $contrat->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-calendar-alt me-2"></i>Gérer les échéances
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <div class="col-md-6 fw-bold">Durée (mois) :</div>
                                                <div class="col-md-6">{{ $detailCreditBail->duree_mois }}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 fw-bold">Valeur résiduelle :</div>
                                                <div class="col-md-6">{{ number_format($detailCreditBail->valeur_residuelle, 2, ',', ' ') }} DH</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 fw-bold">Périodicité :</div>
                                                <div class="col-md-6">{{ ucfirst($detailCreditBail->periodicite) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <div class="col-md-6 fw-bold">Montant redevance :</div>
                                                <div class="col-md-6">{{ number_format($detailCreditBail->montant_redevance_periodique, 2, ',', ' ') }} DH</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 fw-bold">Taux d'intérêt :</div>
                                                <div class="col-md-6">{{ $detailCreditBail->taux_interet_periodique ? number_format($detailCreditBail->taux_interet_periodique, 2, ',', ' ') . ' %' : '-' }}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 fw-bold">Montant total :</div>
                                                <div class="col-md-6">{{ number_format($detailCreditBail->montant_total_redevances, 2, ',', ' ') }} DH</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive mt-4">
                                        <h6 class="mb-3">Prochaines échéances</h6>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Date</th>
                                                    <th>Montant</th>
                                                    <th>Intérêt</th>
                                                    <th>Capital</th>
                                                    <th>Capital restant</th>
                                                    <th>Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($echeances->take(5) as $echeance)
                                                    <tr>
                                                        <td>{{ $echeance->numero_echeance }}</td>
                                                        <td>
                                                            @if($echeance->date_echeance)
                                                                @if(is_string($echeance->date_echeance))
                                                                    {{ \Carbon\Carbon::parse($echeance->date_echeance)->format('d/m/Y') }}
                                                                @else
                                                                    {{ $echeance->date_echeance->format('d/m/Y') }}
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($echeance->montant_redevance, 2, ',', ' ') }}</td>
                                                        <td>{{ number_format($echeance->part_interet, 2, ',', ' ') }}</td>
                                                        <td>{{ number_format($echeance->part_capital, 2, ',', ' ') }}</td>
                                                        <td>{{ number_format($echeance->capital_restant_du, 2, ',', ' ') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $echeance->statut == 'payee' ? 'success' : ($echeance->statut == 'en_retard' ? 'danger' : 'warning') }}">
                                                                {{ $echeance->statut == 'payee' ? 'Payée' : ($echeance->statut == 'en_retard' ? 'En retard' : 'À payer') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($contrat->document_path)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Document</h6>
                                </div>
                                <div class="card-body">
                                    <a href="{{ asset('storage/' . $contrat->document_path) }}" target="_blank" class="btn btn-info">
                                        <i class="fas fa-file-download me-2"></i>Télécharger le document
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
