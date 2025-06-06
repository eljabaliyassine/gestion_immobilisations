@extends("layouts.app")

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Tableau de bord') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5 class="mb-3">{{ __('Bienvenue dans votre application de gestion des immobilisations') }}</h5>
                    
                    @if(Auth::user()->current_dossier_id)
                        <p>Vous travaillez actuellement sur le dossier : <strong>{{ $dossier->nom ?? 'Sans nom' }}</strong> 
                        @if($dossier->compte)
                            (Compte: {{ $dossier->compte->nom ?? 'Non défini' }})
                        @endif
                        </p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Immobilisations</h5>
                                        <p class="card-text">Gérez vos immobilisations, suivez les amortissements et les mouvements.</p>
                                        <a href="{{ route('immobilisations.index') }}" class="btn btn-primary">Accéder</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Inventaires</h5>
                                        <p class="card-text">Réalisez et suivez vos inventaires physiques d'immobilisations.</p>
                                        <a href="{{ route('inventaires.index') }}" class="btn btn-primary">Accéder</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Paramètres</h5>
                                        <p class="card-text">Configurez les familles, sites, services et autres paramètres.</p>
                                        <a href="{{ route('parametres.familles.index') }}" class="btn btn-primary">Accéder</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Exports</h5>
                                        <p class="card-text">Générez des exports pour vos besoins comptables et fiscaux.</p>
                                        <a href="{{ route('exports.index') }}" class="btn btn-primary">Accéder</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <p>Vous n'avez pas sélectionné de dossier. Veuillez en sélectionner un pour accéder à toutes les fonctionnalités.</p>
                            <a href="{{ route('dossiers.select') }}" class="btn btn-warning">Sélectionner un dossier</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
