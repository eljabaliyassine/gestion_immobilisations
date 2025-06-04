@extends("layouts.app")

@section("title", "Immobilisations")

@section("content")
<div class="container-fluid">
    <h1>Gestion des Immobilisations</h1>

    <div class="card mb-3">
        <div class="card-header">Filtres</div>
        <div class="card-body">
            <form action="{{ route("immobilisations.index") }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filter_code_barre" class="form-label">Code Barre</label>
                    <input type="text" class="form-control" id="filter_code_barre" name="filter_code_barre" value="{{ request("filter_code_barre") }}">
                </div>
                <div class="col-md-3">
                    <label for="filter_designation" class="form-label">Désignation</label>
                    <input type="text" class="form-control" id="filter_designation" name="filter_designation" value="{{ request("filter_designation") }}">
                </div>
                <div class="col-md-3">
                    <label for="filter_famille_id" class="form-label">Famille</label>
                    <select class="form-select" id="filter_famille_id" name="filter_famille_id">
                        <option value="">Toutes</option>
                        {{-- Populate with familles from controller --}}
                        @foreach ($familles as $famille)
                            <option value="{{ $famille->id }}" {{ request("filter_famille_id") == $famille->id ? "selected" : "" }}>{{ $famille->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-md-3">
                    <label for="filter_site_id" class="form-label">Site</label>
                    <select class="form-select" id="filter_site_id" name="filter_site_id">
                        <option value="">Tous</option>
                         {{-- Populate with sites from controller --}}
                        @foreach ($sites as $site)
                            <option value="{{ $site->id }}" {{ request("filter_site_id") == $site->id ? "selected" : "" }}>{{ $site->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-md-3">
                    <label for="filter_service_id" class="form-label">Service</label>
                    <select class="form-select" id="filter_service_id" name="filter_service_id">
                        <option value="">Tous</option>
                         {{-- Populate with services from controller --}}
                         @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ request("filter_service_id") == $service->id ? "selected" : "" }}>{{ $service->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Add more filters as needed (date acquisition, etc.) --}}
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route("immobilisations.index") }}" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route("immobilisations.create") }}" class="btn btn-success">Nouvelle Immobilisation</a>
    </div>

    @if (session("success"))
        <div class="alert alert-success">{{ session("success") }}</div>
    @endif
    @if (session("error"))
        <div class="alert alert-danger">{{ session("error") }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code Barre</th>
                    <th>Désignation</th>
                    <th>Famille</th>
                    <th>Site</th>
                    <th>Service</th>
                    <th>Date Acq.</th>
                    <th>Valeur Acq.</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($immobilisations as $immo)
                    <tr>
                        <td>{{ $immo->id }}</td>
                        <td>{{ $immo->code_barre }}</td>
                        <td>{{ $immo->designation }}</td>
                        <td>{{ $immo->famille->libelle ?? "N/A" }}</td>
                        <td>{{ $immo->site->libelle ?? "N/A" }}</td>
                        <td>{{ $immo->service->libelle ?? "N/A" }}</td>
                        <td>{{ $immo->date_acquisition ? $immo->date_acquisition->format("d/m/Y") : "N/A" }}</td>
                        <td class="text-end">{{ number_format($immo->valeur_acquisition, 2, ",", " ") }}</td>
                        <td><span class="badge bg-{{ $immo->statut == "En service" ? "success" : ($immo->statut == "Cédé" || $immo->statut == "Rebut" ? "danger" : "secondary") }}">{{ $immo->statut }}</span></td>
                        <td>
                            <a href="{{ route("immobilisations.show", $immo) }}" class="btn btn-info btn-sm" title="Voir"><i class="bi bi-eye"></i></a>
                            <a href="{{ route("immobilisations.edit", $immo) }}" class="btn btn-warning btn-sm" title="Modifier"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route("immobilisations.destroy", $immo) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer cette immobilisation ? Attention, cela peut affecter les calculs d'amortissement.");">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Aucune immobilisation trouvée pour ce dossier ou avec ces filtres.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $immobilisations->appends(request()->query())->links() }} {{-- Keep filters during pagination --}}

</div>
@endsection

@push("styles")
{{-- Add specific styles if needed --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
