@extends("layouts.app")

@section("title", "Détails Session d'Inventaire : " . $inventaire->reference)

@section("content")
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Détails Session d'Inventaire : {{ $inventaire->reference }}</h1>
        <div>
            <a href="{{ route('inventaires.index') }}" class="btn btn-secondary">Retour à la liste</a>
            @if($inventaire->statut != 'Terminé')
                <a href="{{ route('inventaires.edit', $inventaire) }}" class="btn btn-warning">Modifier</a>
            @endif
            <a href="{{ route('inventaires.results', $inventaire) }}" class="btn btn-info">Voir les Résultats</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Informations Générales</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Référence :</strong> {{ $inventaire->reference }}</p>
                    <p><strong>Date de Début :</strong> {{ $inventaire->date_debut->format('d/m/Y') }}</p>
                    <p><strong>Date de Fin :</strong> {{ $inventaire->date_fin ? $inventaire->date_fin->format('d/m/Y') : 'Non terminée' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Statut :</strong> <span class="badge bg-{{ $inventaire->statut == 'Terminé' ? 'success' : ($inventaire->statut == 'En cours' ? 'warning' : 'secondary') }}">{{ $inventaire->statut }}</span></p>
                    <p><strong>Créé par :</strong> {{ $inventaire->user->name ?? 'N/A' }} le {{ $inventaire->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Description :</strong> {{ $inventaire->description ?: 'Aucune description' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Comptages d'Inventaire</div>
        <div class="card-body">
            @if($inventaire->statut != 'Terminé')
                <div class="mb-3">
                    <a href="{{ route('inventaires.add_count.form', $inventaire) }}" class="btn btn-success">Ajouter un Comptage Manuel</a>
                    <a href="{{ route('inventaires.import_counts.form', $inventaire) }}" class="btn btn-info">Importer des Comptages</a>
                </div>
            @else
                 <p>La session est terminée, les comptages ne peuvent plus être modifiés.</p>
            @endif

            <h4>Comptages Enregistrés</h4>
            @if($inventaire->details->isEmpty())
                <p>Aucun comptage n'a encore été enregistré pour cette session.</p>
            @else
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Code Barre Scanné</th>
                            <th>Immobilisation Trouvée</th>
                            <th>Date Comptage</th>
                            <th>Utilisateur Comptage</th>
                            <th>Statut Comptage</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- We will load details via InventaireController@show --}}
                        @foreach($detailsInventaire as $detail)
                            <tr>
                                <td>{{ $detail->code_barre_scanne }}</td>
                                <td>
                                    @if($detail->immobilisation)
                                        <a href="{{ route('immobilisations.show', $detail->immobilisation_id) }}">{{ $detail->immobilisation->designation }}</a>
                                    @else
                                        <span class="text-danger">Non trouvée</span>
                                    @endif
                                </td>
                                <td>{{ $detail->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $detail->user->name ?? 'N/A' }}</td>
                                <td>{{ $detail->statut_comptage }}</td>
                                <td>{{ $detail->commentaire }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                 {{-- Pagination Links --}}
                <div class="d-flex justify-content-center">
                     {{ $detailsInventaire->links() }}
                </div>
            @endif

            @if($inventaire->statut == 'En cours')
                <hr>
                <form action="{{ route('inventaires.process', $inventaire) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir traiter les comptages et calculer les écarts ? Cette action peut prendre du temps et mettra à jour le statut des immobilisations.');">
                    @csrf
                    <button type="submit" class="btn btn-primary">Traiter les Comptages et Calculer les Écarts</button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection

