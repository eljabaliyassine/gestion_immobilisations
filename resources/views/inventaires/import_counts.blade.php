@extends("layouts.app")

@section("title", "Importer des Comptages - " . $inventaire->reference)

@section("content")
<div class="container">
    <h1>Importer des Comptages</h1>
    <h2>Session d'Inventaire : <a href="{{ route("inventaires.show", $inventaire) }}">{{ $inventaire->reference }}</a></h2>

    <div class="card mb-4">
        <div class="card-header">Importer un fichier de comptages</div>
        <div class="card-body">
            <form action="{{ route("inventaires.import_counts.handle", $inventaire) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <label for="file" class="col-md-4 col-form-label text-md-end">Fichier CSV/Excel</label>
                    <div class="col-md-6">
                        <input id="file" type="file" class="form-control @error("file") is-invalid @enderror" name="file" required>
                        @error("file")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="form-text text-muted">
                            Format attendu : CSV ou Excel avec les colonnes "immobilisation_id", "statut_comptage", "commentaire" (optionnel)
                        </small>
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Importer
                        </button>
                        <a href="{{ route("inventaires.show", $inventaire) }}" class="btn btn-secondary">Retour à la Session</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Liste des Immobilisations Actives</span>
            <a href="{{ route('inventaires.export.immobilisations', ['inventaire' => $inventaire->id, 'site_id' => $siteId, 'service_id' => $serviceId]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Exporter CSV
            </a>
        </div>
        <div class="card-body">
            <!-- Filtres -->
            <form action="{{ route('inventaires.import_counts.form', $inventaire) }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="site_id" class="form-label">Filtrer par Site</label>
                        <select name="site_id" id="site_id" class="form-select">
                            <option value="">Tous les sites</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ $siteId == $site->id ? 'selected' : '' }}>{{ $site->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="service_id" class="form-label">Filtrer par Service</label>
                        <select name="service_id" id="service_id" class="form-select">
                            <option value="">Tous les services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>{{ $service->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </div>
            </form>

            <!-- Tableau des immobilisations -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Désignation</th>
                            <th>Description</th>
                            <th>Service</th>
                            <th>Site</th>
                            <th>Statut du Comptage</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($immobilisations as $immo)
                            @php
                                $detail = $inventaire->details()->where('immobilisation_id', $immo->id)->first();
                                $statutConstate = $detail ? ($statutMappingReverse[$detail->statut_constate] ?? $detail->statut_constate) : 'Non compté';
                                $commentaire = $detail ? $detail->commentaire : '';
                            @endphp
                            <tr>
                                <td>{{ $immo->id }}</td>
                                <td>{{ $immo->designation }}</td>
                                <td>{{ Str::limit($immo->description, 50) }}</td>
                                <td>{{ $immo->service ? $immo->service->libelle : 'N/A' }}</td>
                                <td>{{ $immo->site ? $immo->site->libelle : 'N/A' }}</td>
                                <td>{{ $statutConstate }}</td>
                                <td>{{ $commentaire }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucune immobilisation trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $immobilisations->appends(['site_id' => $siteId, 'service_id' => $serviceId])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
