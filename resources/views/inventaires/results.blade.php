@extends("layouts.app")

@section("title", "Résultats Inventaire - " . $inventaire->reference)

@section("content")
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Résultats Inventaire : {{ $inventaire->reference }}</h1>
        <div>
            <a href="{{ route("inventaires.show", $inventaire) }}" class="btn btn-secondary">Retour à la Session</a>
            <a href="{{ route('inventaires.export.resultats', ['inventaire' => $inventaire->id, 'site_id' => $siteId ?? '', 'service_id' => $serviceId ?? '']) }}" class="btn btn-success">
                <i class="fas fa-file-csv"></i> Exporter les Résultats
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header">Filtres</div>
        <div class="card-body">
            <form action="{{ route('inventaires.results', $inventaire) }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label for="site_id" class="form-label">Filtrer par Site</label>
                    <select name="site_id" id="site_id" class="form-select">
                        <option value="">Tous les sites</option>
                        @foreach($sites ?? [] as $site)
                            <option value="{{ $site->id }}" {{ ($siteId ?? '') == $site->id ? 'selected' : '' }}>{{ $site->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="service_id" class="form-label">Filtrer par Service</label>
                    <select name="service_id" id="service_id" class="form-select">
                        <option value="">Tous les services</option>
                        @foreach($services ?? [] as $service)
                            <option value="{{ $service->id }}" {{ ($serviceId ?? '') == $service->id ? 'selected' : '' }}>{{ $service->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Résumé des Écarts</div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Immobilisations Attendues</h5>
                            <p class="card-text fs-3">{{ $stats["attendues"] ?? "N/A" }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Immobilisations Trouvées</h5>
                            <p class="card-text fs-3">{{ $stats["trouvees"] ?? "N/A" }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Immobilisations Manquantes</h5>
                            <p class="card-text fs-3">{{ $stats["manquantes"] ?? "N/A" }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Détail des Écarts</div>
        <div class="card-body">
            <h4>Immobilisations Manquantes (Attendues mais non scannées)</h4>
            @if(isset($immobilisationsManquantes) && $immobilisationsManquantes->isEmpty())
                <p>Aucune immobilisation manquante.</p>
            @elseif(isset($immobilisationsManquantes))
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-danger">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code Barre</th>
                                <th>Désignation</th>
                                <th>Description</th>
                                <th>Service</th>
                                <th>Site</th>
                                <th>Statut Théorique</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($immobilisationsManquantes as $immo)
                                <tr>
                                    <td>{{ $immo->id }}</td>
                                    <td><a href="{{ route("immobilisations.show", $immo) }}">{{ $immo->code_barre ?? 'Sans code-barre' }}</a></td>
                                    <td>{{ $immo->designation }}</td>
                                    <td>{{ Str::limit($immo->description, 50) }}</td>
                                    <td>{{ $immo->service->libelle ?? "N/A" }}</td>
                                    <td>{{ $immo->site->libelle ?? "N/A" }}</td>
                                    <td>{{ $immo->statut }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $immobilisationsManquantes->appends(['service_id' => $serviceId ?? '', 'site_id' => $siteId ?? '', 'trouves_page' => request('trouves_page'), 'non_reconnus_page' => request('non_reconnus_page')])->links() }}
                </div>
            @endif

            <hr>

            <h4>Immobilisations Trouvées (Scannées et correspondantes)</h4>
             @if(isset($detailsTrouves) && $detailsTrouves->isEmpty())
                <p>Aucune immobilisation trouvée et scannée.</p>
            @elseif(isset($detailsTrouves))
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-success">
                         <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code Barre Scanné</th>
                                <th>Désignation</th>
                                <th>Description</th>
                                <th>Service</th>
                                <th>Site</th>
                                <th>Statut Comptage</th>
                                <th>Commentaire</th>
                                <th>Date Comptage</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailsTrouves as $detail)
                                <tr>
                                    <td>{{ $detail->immobilisation->id ?? 'N/A' }}</td>
                                    <td>
                                        @if($detail->immobilisation_id)
                                            <a href="{{ route("immobilisations.show", $detail->immobilisation_id) }}">
                                                {{ $detail->code_barre_scan ?? 'Sans code-barre' }}
                                            </a>
                                        @else
                                            {{ $detail->code_barre_scan ?? 'Sans code-barre' }}
                                        @endif
                                    </td>
                                    <td>{{ $detail->immobilisation->designation ?? "N/A" }}</td>
                                    <td>{{ Str::limit($detail->immobilisation->description ?? '', 50) }}</td>
                                    <td>{{ $detail->immobilisation->service->libelle ?? "N/A" }}</td>
                                    <td>{{ $detail->immobilisation->site->libelle ?? "N/A" }}</td>
                                    <td>{{ $detail->statut_constate_display ?? $detail->statut_constate }}</td>
                                    <td>{{ $detail->commentaire }}</td>
                                    <td>{{ $detail->date_scan ? $detail->date_scan->format("d/m/Y H:i") : $detail->created_at->format("d/m/Y H:i") }}</td>
                                    <td>{{ $detail->user->name ?? "N/A" }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $detailsTrouves->appends(['service_id' => $serviceId ?? '', 'site_id' => $siteId ?? '', 'manquantes_page' => request('manquantes_page'), 'non_reconnus_page' => request('non_reconnus_page')])->links() }}
                </div>
            @endif

            <hr>

            <h4>Codes Barres Scannés mais non trouvés dans la base</h4>
             @if(isset($detailsNonReconnus) && $detailsNonReconnus->isEmpty())
                <p>Aucun code barre scanné non trouvé dans la base.</p>
            @elseif(isset($detailsNonReconnus))
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-warning">
                         <thead>
                            <tr>
                                <th>Code Barre Scanné</th>
                                <th>Statut Comptage</th>
                                <th>Commentaire</th>
                                <th>Date Comptage</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailsNonReconnus as $detail)
                                <tr>
                                    <td>{{ $detail->code_barre_scan ?? 'Sans code-barre' }}</td>
                                    <td>{{ $detail->statut_constate_display ?? $detail->statut_constate }}</td>
                                    <td>{{ $detail->commentaire }}</td>
                                    <td>{{ $detail->date_scan ? $detail->date_scan->format("d/m/Y H:i") : $detail->created_at->format("d/m/Y H:i") }}</td>
                                    <td>{{ $detail->user->name ?? "N/A" }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $detailsNonReconnus->appends(['service_id' => $serviceId ?? '', 'site_id' => $siteId ?? '', 'manquantes_page' => request('manquantes_page'), 'trouves_page' => request('trouves_page')])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
