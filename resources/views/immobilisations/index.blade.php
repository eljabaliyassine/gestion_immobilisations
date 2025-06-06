@extends("layouts.app")

@section("title", "Immobilisations")

@section("content")
<div class="container-fluid px-4">
   <!-- En-tête avec titre et actions principales -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="h4 mb-1 text-gray-800">
            <i class="fas fa-building me-2 text-primary"></i>
            Gestion des Immobilisations
        </h1>
        <p class="text-muted mb-0">Gérez et suivez vos immobilisations</p>
    </div>
    <a href="{{ route('immobilisations.create') }}" class="btn btn-primary btn-sm btn-md-lg shadow-sm">
        <i class="fas fa-plus me-2"></i>
        Nouvelle Immobilisation
    </a>
</div>


    <!-- Messages d'alerte -->
    @if (session("success"))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session("success") }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session("error"))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session("error") }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Carte des filtres améliorée -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2 text-secondary"></i>
                Filtres de recherche
            </h5>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="filtersCollapse">
            <div class="card-body">
                <form action="{{ route("immobilisations.index") }}" method="GET" class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <label for="filter_code_barre" class="form-label fw-semibold">
                            <i class="fas fa-barcode me-1"></i>
                            Code Barre
                        </label>
                        <input type="text" class="form-control" id="filter_code_barre" name="filter_code_barre"
                               value="{{ request("filter_code_barre") }}" placeholder="Rechercher par code...">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="filter_designation" class="form-label fw-semibold">
                            <i class="fas fa-tag me-1"></i>
                            Désignation
                        </label>
                        <input type="text" class="form-control" id="filter_designation" name="filter_designation"
                               value="{{ request("filter_designation") }}" placeholder="Rechercher par nom...">
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label for="filter_famille_id" class="form-label fw-semibold">
                            <i class="fas fa-layer-group me-1"></i>
                            Famille
                        </label>
                        <select class="form-select" id="filter_famille_id" name="filter_famille_id">
                            <option value="">Toutes</option>
                            @foreach ($familles as $famille)
                                <option value="{{ $famille->id }}" {{ request("filter_famille_id") == $famille->id ? "selected" : "" }}>
                                    {{ $famille->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label for="filter_site_id" class="form-label fw-semibold">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Site
                        </label>
                        <select class="form-select" id="filter_site_id" name="filter_site_id">
                            <option value="">Tous</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" {{ request("filter_site_id") == $site->id ? "selected" : "" }}>
                                    {{ $site->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label for="filter_service_id" class="form-label fw-semibold">
                            <i class="fas fa-users me-1"></i>
                            Service
                        </label>
                        <select class="form-select" id="filter_service_id" name="filter_service_id">
                            <option value="">Tous</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ request("filter_service_id") == $service->id ? "selected" : "" }}>
                                    {{ $service->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Filtrer
                            </button>
                            <a href="{{ route("immobilisations.index") }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tableau optimisé -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-secondary"></i>
                Liste des Immobilisations
            </h5>
            <span class="badge bg-primary">{{ $immobilisations->total() }} résultats</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">#</th>
                            <th class="fw-semibold">
                                <i class="fas fa-barcode me-1"></i>
                                Code Barre
                            </th>
                            <th class="fw-semibold">
                                <i class="fas fa-tag me-1"></i>
                                Désignation
                            </th>
                            <th class="fw-semibold">
                                <i class="fas fa-layer-group me-1"></i>
                                Famille
                            </th>
                            <th class="fw-semibold">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Site
                            </th>
                            <th class="fw-semibold">
                                <i class="fas fa-users me-1"></i>
                                Service
                            </th>
                            <th class="fw-semibold">
                                <i class="fas fa-calendar me-1"></i>
                                Date Acq.
                            </th>
                            <th class="fw-semibold text-end">
                                <i class="fas fa-euro-sign me-1"></i>
                                Valeur Acq.
                            </th>
                            <th class="fw-semibold text-center">
                                <i class="fas fa-info-circle me-1"></i>
                                Statut
                            </th>
                            <th class="fw-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($immobilisations as $immo)
                            <tr class="align-middle">
                                <td class="fw-medium text-muted">{{ $immo->id }}</td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded">{{ $immo->code_barre }}</code>
                                </td>
                                <td class="fw-medium">{{ $immo->designation }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $immo->famille->libelle ?? "N/A" }}</span>
                                </td>
                                <td>{{ $immo->site->libelle ?? "N/A" }}</td>
                                <td>{{ $immo->service->libelle ?? "N/A" }}</td>
                                <td>
                                    @if($immo->date_acquisition)
                                        <small class="text-muted">{{ $immo->date_acquisition->format("d/m/Y") }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end fw-medium">
                                    {{ number_format($immo->valeur_acquisition, 2, ",", " ") }} €
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match($immo->statut) {
                                            "En service" => "success",
                                            "Cédé", "Rebut" => "danger",
                                            default => "secondary"
                                        };
                                        $statusIcon = match($immo->statut) {
                                            "En service" => "fas fa-check-circle",
                                            "Cédé" => "fas fa-exchange-alt",
                                            "Rebut" => "fas fa-trash",
                                            default => "fas fa-question-circle"
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} d-inline-flex align-items-center">
                                        <i class="{{ $statusIcon }} me-1"></i>
                                        {{ $immo->statut }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route("immobilisations.show", $immo) }}"
                                           class="btn btn-outline-info btn-sm" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route("immobilisations.edit", $immo) }}"
                                           class="btn btn-outline-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                title="Supprimer" onclick="confirmDelete({{ $immo->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Formulaire de suppression caché -->
                                    <form id="delete-form-{{ $immo->id }}"
                                          action="{{ route("immobilisations.destroy", $immo) }}"
                                          method="POST" style="display: none;">
                                        @csrf
                                        @method("DELETE")
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-search fa-3x mb-3 d-block"></i>
                                        <h5>Aucune immobilisation trouvée</h5>
                                        <p class="mb-0">Aucune immobilisation ne correspond à vos critères de recherche.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($immobilisations->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de {{ $immobilisations->firstItem() }} à {{ $immobilisations->lastItem() }}
                        sur {{ $immobilisations->total() }} résultats
                    </div>
                    {{ $immobilisations->appends(request()->query())->links() }}
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
                <p>Êtes-vous sûr de vouloir supprimer cette immobilisation ?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Attention :</strong> Cette action peut affecter les calculs d'amortissement.
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

@push("styles")
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
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
    }

    code {
        font-size: 0.875rem;
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
</style>
@endpush

@push("scripts")
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
