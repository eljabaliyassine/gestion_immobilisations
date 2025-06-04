@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Plan d'amortissement</h5>
                </div>
                <div class="card-body">
                    @if(session('success') || isset($success))
                        <div class="alert alert-success mx-4 mt-3">
                            {{ session('success') ?? $success ?? '' }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('amortissements.generer') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_derniere_cloture">Date dernière clôture</label>
                                    <input type="date" class="form-control" id="date_derniere_cloture" name="date_derniere_cloture" value="{{ $dateDerniereCloture ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_prochaine_cloture">Date prochaine clôture</label>
                                    <input type="date" class="form-control" id="date_prochaine_cloture" name="date_prochaine_cloture" value="{{ $dateProchaineCloture ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt me-2"></i>Générer plan d'amortissement
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    @if(isset($plansAmortissement) && $plansAmortissement->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Immobilisation</th>
                                    <th>Famille</th>
                                    <th>Date mise en service</th>
                                    <th>Base amortissable</th>
                                    <th>Taux</th>
                                    <th>Amort. cumulé début</th>
                                    <th>Dotation période</th>
                                    <th>Amort. cumulé fin</th>
                                    <th>VNA fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plansAmortissement as $plan)
                                <tr>
                                    <td>{{ $plan->immobilisations_description }}</td>
                                    <td>{{ $plan->famille }}</td>
                                    <td>{{ \Carbon\Carbon::parse($plan->immobilisation_date_mise_service)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($plan->base_amortissable, 2, ',', ' ') }} DH</td>
                                    <td>{{ number_format($plan->taux_applique * 100, 2, ',', ' ') }} %</td>
                                    <td>{{ number_format($plan->amortissement_cumule_debut, 2, ',', ' ') }} DH</td>
                                    <td>{{ number_format($plan->dotation_periode, 2, ',', ' ') }} DH</td>
                                    <td>{{ number_format($plan->amortissement_cumule_fin, 2, ',', ' ') }} DH</td>
                                    <td>{{ number_format($plan->vna_fin_exercice, 2, ',', ' ') }} DH</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
			<a href="{{ route('amortissements.export.csv', ['date_derniere_cloture' => $dateDerniereCloture, 'date_prochaine_cloture' => $dateProchaineCloture]) }}" class="btn btn-info">
			    <i class="fas fa-file-csv me-2"></i>Exporter CSV (tous les champs)
			</a>
                    </div>
                    @else
                    <div class="alert alert-info">
                        @if(isset($success))
                            Il semble qu'aucune immobilisation active n'ait été trouvée pour les dates sélectionnées. 
                            Veuillez vérifier que des immobilisations actives existent dans le dossier courant.
                        @else
                            Veuillez générer le plan d'amortissement en sélectionnant les dates ci-dessus.
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
