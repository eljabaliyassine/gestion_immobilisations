@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête avec titre et actions principales -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calculator me-2 text-primary"></i>
                Comptes Comptables
            </h1>
            <p class="text-muted mb-0">Gérez votre plan comptable</p>
        </div>
        <a href="{{ route('parametres.comptescompta.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus me-2"></i>
            Nouveau Compte
        </a>
    </div>

    <!-- Messages d'alerte -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Carte des filtres de recherche -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-search me-2 text-secondary"></i>
                Recherche et filtres
            </h5>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#searchFilters">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="searchFilters">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search_numero" class="form-label fw-semibold">
                            <i class="fas fa-hashtag me-1"></i>
                            Numéro de compte
                        </label>
                        <input type="text" class="form-control" id="search_numero" name="search_numero"
                               value="{{ request('search_numero') }}" placeholder="Ex: 2183, 68...">
                    </div>
                    <div class="col-md-4">
                        <label for="search_libelle" class="form-label fw-semibold">
                            <i class="fas fa-tag me-1"></i>
                            Libellé
                        </label>
                        <input type="text" class="form-control" id="search_libelle" name="search_libelle"
                               value="{{ request('search_libelle') }}" placeholder="Rechercher par nom...">
                    </div>
                    <div class="col-md-4">
                        <label for="filter_type" class="form-label fw-semibold">
                            <i class="fas fa-filter me-1"></i>
                            Type de compte
                        </label>
                        <select class="form-select" id="filter_type" name="filter_type">
                            <option value="">Tous les types</option>
                            <option value="actif" {{ request('filter_type') == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="passif" {{ request('filter_type') == 'passif' ? 'selected' : '' }}>Passif</option>
                            <option value="charge" {{ request('filter_type') == 'charge' ? 'selected' : '' }}>Charge</option>
                            <option value="produit" {{ request('filter_type') == 'produit' ? 'selected' : '' }}>Produit</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Rechercher
                            </button>
                            <a href="{{ route('parametres.comptescompta.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides (optionnelles - à afficher seulement si pas de filtres actifs) -->
    @if(!request()->hasAny(['search_numero', 'search_libelle', 'filter_type']))
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Comptes Actif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\CompteCompta::where('type', 'actif')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Comptes Passif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\CompteCompta::where('type', 'passif')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Comptes Charge</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\CompteCompta::where('type', 'charge')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Comptes Produit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\CompteCompta::where('type', 'produit')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tableau principal -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-secondary"></i>
                Plan Comptable
            </h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary">{{ $comptescompta->total() ?? $comptescompta->count() }} comptes</span>
                {{-- <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-1"></i>
                        Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('parametres.comptescompta.export', ['format' => 'excel']) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="{{ route('parametres.comptescompta.export', ['format' => 'pdf']) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                        <li><a class="dropdown-item" href="{{ route('parametres.comptescompta.export', ['format' => 'csv']) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                    </ul>
                </div> --}}
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">
                                <i class="fas fa-hashtag me-1"></i>
                                Numéro
                            </th>
                            <th class="fw-semibold">
                                <i class="fas fa-tag me-1"></i>
                                Libellé
                            </th>
                            <th class="fw-semibold text-center">
                                <i class="fas fa-layer-group me-1"></i>
                                Type
                            </th>
                            <th class="fw-semibold text-center">
                                <i class="fas fa-cogs me-1"></i>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comptescompta as $compte)
                            <tr class="align-middle">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="account-number-badge me-2">
                                            <code class="bg-light px-3 py-2 rounded fw-bold">{{ $compte->numero }}</code>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $compte->libelle }}</div>
                                    <small class="text-muted">Compte {{ $compte->numero }}</small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $typeConfig = [
                                            'actif' => ['class' => 'success', 'icon' => 'fas fa-chart-line'],
                                            'passif' => ['class' => 'info', 'icon' => 'fas fa-balance-scale'],
                                            'charge' => ['class' => 'warning', 'icon' => 'fas fa-arrow-down'],
                                            'produit' => ['class' => 'primary', 'icon' => 'fas fa-arrow-up']
                                        ];
                                        $config = $typeConfig[$compte->type] ?? ['class' => 'secondary', 'icon' => 'fas fa-question'];
                                    @endphp
                                    <span class="badge bg-{{ $config['class'] }} d-inline-flex align-items-center">
                                        <i class="{{ $config['icon'] }} me-1"></i>
                                        {{ ucfirst($compte->type) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('parametres.comptescompta.show', $compte->id) }}"
                                           class="btn btn-outline-info btn-sm" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('parametres.comptescompta.edit', $compte->id) }}"
                                           class="btn btn-outline-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                title="Supprimer" onclick="confirmDelete({{ $compte->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Formulaire de suppression caché -->
                                    <form id="delete-form-{{ $compte->id }}"
                                          action="{{ route('parametres.comptescompta.destroy', $compte->id) }}"
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-calculator fa-3x mb-3 d-block"></i>
                                        <h5>Aucun compte comptable trouvé</h5>
                                        <p class="mb-0">Commencez par créer votre premier compte comptable.</p>
                                        <a href="{{ route('parametres.comptescompta.create') }}" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus me-2"></i>
                                            Créer un compte
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if(method_exists($comptescompta, 'hasPages') && $comptescompta->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de {{ $comptescompta->firstItem() }} à {{ $comptescompta->lastItem() }}
                        sur {{ $comptescompta->total() }} comptes
                    </div>
                    {{ $comptescompta->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce compte comptable ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible et peut affecter vos écritures comptables.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .table th {
        border-top: none;
        font-size: 0.875rem;
        padding: 1rem 0.75rem;
    }

    .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.75em;
    }

    code {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .alert {
        border: none;
        border-radius: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
    }

    .btn {
        border-radius: 0.375rem;
    }

    .account-number-badge code {
        background-color: #f8f9fa !important;
        border: 1px solid #dee2e6;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }
</style>
@endpush

@push('scripts')
<script>
let deleteFormId = null;

function confirmDelete(id) {
    deleteFormId = id;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteFormId) {
        document.getElementById('delete-form-' + deleteFormId).submit();
    }
});
</script>
@endpush
