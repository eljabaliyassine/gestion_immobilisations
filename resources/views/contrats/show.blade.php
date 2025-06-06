@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white fw-bold">
                            <i class="fas fa-file-contract me-3"></i>Détails du contrat
                        </h4>
                        <p class="mb-0 text-white-75 small">{{ $contrat->reference }}</p>
                    </div>
                    <div>
                        <a href="{{ route('contrats.edit', $contrat->id) }}" class="btn btn-warning btn-sm shadow-sm me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('contrats.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-info-circle text-primary me-2"></i>Informations du contrat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Référence</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="fw-bold">{{ $contrat->reference }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Type</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary')) }} px-3 py-2 rounded-pill">
                                                <i class="fas fa-{{ $contrat->type == 'maintenance' ? 'tools' : ($contrat->type == 'location' ? 'home' : ($contrat->type == 'leasing' ? 'handshake' : 'file')) }} me-1"></i>
                                                {{ ucfirst($contrat->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Période</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                <span>
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
                                                        <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                        @if(is_string($contrat->date_fin))
                                                            {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}
                                                        @else
                                                            {{ $contrat->date_fin->format('d/m/Y') }}
                                                        @endif
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Montant</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                <span class="fw-bold text-success">{{ number_format($contrat->montant_periodique ?? 0, 2, ',', ' ') }} DH</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Périodicité</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-light text-dark border">{{ ucfirst($contrat->periodicite ?? '-') }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Statut</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $contrat->statut == 'actif' ? 'success' : ($contrat->statut == 'inactif' ? 'warning' : 'danger') }} px-3 py-2 rounded-pill">
                                                <i class="fas fa-{{ $contrat->statut == 'actif' ? 'check-circle' : ($contrat->statut == 'inactif' ? 'pause-circle' : 'times-circle') }} me-1"></i>
                                                {{ ucfirst($contrat->statut) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-users text-primary me-2"></i>Prestataire et fournisseur
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Prestataire</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-tie text-primary me-2"></i>
                                                <span class="fw-bold">{{ $contrat->prestataire ? $contrat->prestataire->nom : '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Fournisseur</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-building text-primary me-2"></i>
                                                <span class="fw-bold">{{ $contrat->fournisseur ? $contrat->fournisseur->nom : '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Créé le</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-plus-circle text-success me-2"></i>
                                                <span class="small">
                                                    @if($contrat->created_at)
                                                        @if(is_string($contrat->created_at))
                                                            {{ \Carbon\Carbon::parse($contrat->created_at)->format('d/m/Y H:i') }}
                                                        @else
                                                            {{ $contrat->created_at->format('d/m/Y H:i') }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <span class="text-muted small text-uppercase fw-bold">Modifié le</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-edit text-warning me-2"></i>
                                                <span class="small">
                                                    @if($contrat->updated_at)
                                                        @if(is_string($contrat->updated_at))
                                                            {{ \Carbon\Carbon::parse($contrat->updated_at)->format('d/m/Y H:i') }}
                                                        @else
                                                            {{ $contrat->updated_at->format('d/m/Y H:i') }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-file-alt text-primary me-2"></i>Description
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-0 text-muted">{{ $contrat->description ?? 'Aucune description' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 pb-0 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-boxes text-primary me-2"></i>
                                        Immobilisations liées
                                        <span class="badge bg-primary rounded-pill ms-2">{{ $immobilisations->count() }}</span>
                                    </h6>
                                    <a href="{{ route('contrats.immobilisations', $contrat->id) }}" class="btn btn-primary btn-sm shadow-sm">
                                        <i class="fas fa-link me-2"></i>Gérer les immobilisations
                                    </a>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-items-center mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-barcode me-1"></i>Code
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-tag me-1"></i>Désignation
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-layer-group me-1"></i>Famille
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-map-marker-alt me-1"></i>Localisation
                                                    </th>
                                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                                        <i class="fas fa-cogs me-1"></i>Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($immobilisations as $immobilisation)
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="d-flex px-3 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm fw-bold">{{ $immobilisation->code_barre ?? $immobilisation->code }}</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <p class="text-sm font-weight-bold mb-0">{{ $immobilisation->designation }}</p>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="badge bg-light text-dark border">{{ $immobilisation->famille ? $immobilisation->famille->libelle : '-' }}</span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                                <span class="text-sm">
                                                                    {{ $immobilisation->site ? $immobilisation->site->libelle : '-' }}
                                                                    @if($immobilisation->service)
                                                                        <br><small class="text-muted">{{ $immobilisation->service->libelle }}</small>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="d-flex gap-2">
                                                                <a href="{{ route('immobilisations.show', $immobilisation->id) }}" class="btn btn-sm btn-info shadow-sm" title="Voir">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <form action="{{ route('contrats.detachImmobilisation', [$contrat->id, $immobilisation->id]) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Retirer" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette immobilisation du contrat ?')">
                                                                        <i class="fas fa-unlink"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                                                <p class="mb-0">Aucune immobilisation liée à ce contrat</p>
                                                            </div>
                                                        </td>
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
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-gradient-warning text-white pb-0 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-white fw-bold">
                                        <i class="fas fa-credit-card me-2"></i>Détails du crédit-bail
                                    </h6>
                                    <a href="{{ route('contrats.echeances', $contrat->id) }}" class="btn btn-light btn-sm shadow-sm">
                                        <i class="fas fa-calendar-alt me-2"></i>Gérer les échéances
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="card bg-light border-0 h-100">
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Durée (mois)</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold">{{ $detailCreditBail->duree_mois }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Valeur résiduelle</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-success">{{ number_format($detailCreditBail->valeur_residuelle, 2, ',', ' ') }} DH</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Périodicité</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="badge bg-primary">{{ ucfirst($detailCreditBail->periodicite) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light border-0 h-100">
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Montant redevance</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-success">{{ number_format($detailCreditBail->montant_redevance_periodique, 2, ',', ' ') }} DH</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Taux d'intérêt</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-info">{{ $detailCreditBail->taux_interet_periodique ? number_format($detailCreditBail->taux_interet_periodique, 2, ',', ' ') . ' %' : '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <span class="text-muted small text-uppercase fw-bold">Montant total</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <span class="fw-bold text-primary">{{ number_format($detailCreditBail->montant_total_redevances, 2, ',', ' ') }} DH</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-calendar-check text-primary me-2"></i>
                                            <h6 class="mb-0 fw-bold">Prochaines échéances</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-sm">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="fw-bold">N°</th>
                                                        <th class="fw-bold">Date</th>
                                                        <th class="fw-bold">Montant</th>
                                                        <th class="fw-bold">Intérêt</th>
                                                        <th class="fw-bold">Capital</th>
                                                        <th class="fw-bold">Capital restant</th>
                                                        <th class="fw-bold">Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($echeances->take(5) as $echeance)
                                                        <tr>
                                                            <td class="fw-bold">{{ $echeance->numero_echeance }}</td>
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
                                                            <td class="fw-bold">{{ number_format($echeance->montant_redevance, 2, ',', ' ') }}</td>
                                                            <td>{{ number_format($echeance->part_interet, 2, ',', ' ') }}</td>
                                                            <td>{{ number_format($echeance->part_capital, 2, ',', ' ') }}</td>
                                                            <td>{{ number_format($echeance->capital_restant_du, 2, ',', ' ') }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $echeance->statut == 'payee' ? 'success' : ($echeance->statut == 'en_retard' ? 'danger' : 'warning') }} px-2 py-1 rounded-pill">
                                                                    <i class="fas fa-{{ $echeance->statut == 'payee' ? 'check' : ($echeance->statut == 'en_retard' ? 'exclamation-triangle' : 'clock') }} me-1"></i>
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
                    </div>
                    @endif

                    @if($contrat->document_path)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 pb-0">
                                    <h6 class="mb-0 text-dark fw-bold">
                                        <i class="fas fa-paperclip text-primary me-2"></i>Document
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="p-4">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <div>
                                            <a href="{{ asset('storage/' . $contrat->document_path) }}" target="_blank" class="btn btn-info btn-lg shadow-sm">
                                                <i class="fas fa-download me-2"></i>Télécharger le document
                                            </a>
                                        </div>
                                    </div>
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

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #51697B 0%, #0c2e46 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75em;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.gap-2 {
    gap: 0.5rem;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}
</style>
@endsection
