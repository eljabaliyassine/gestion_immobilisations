@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Détails du mouvement</h5>
                    <div>
                        <a href="{{ route('mouvements.edit', $mouvement->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('mouvements.index') }}" class="btn btn-secondary">
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
                                    <h6>Informations du mouvement</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Date :</div>
                                        <div class="col-md-8">{{ $mouvement->date_mouvement ? $mouvement->date_mouvement->format('d/m/Y') : '-' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Type :</div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $mouvement->type_mouvement == 'entree' ? 'success' : ($mouvement->type_mouvement == 'sortie' ? 'danger' : 'info') }}">
                                                {{ $mouvement->type_formatted }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Localisation :</div>
                                        <div class="col-md-8">
                                            {{ $mouvement->site ? $mouvement->site->libelle : '-' }}
                                            @if($mouvement->service)
                                                / {{ $mouvement->service->libelle }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Utilisateur :</div>
                                        <div class="col-md-8">{{ $mouvement->user ? $mouvement->user->name : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Immobilisation concernée</h6>
                                </div>
                                <div class="card-body">
                                    @if($mouvement->immobilisation)
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Code :</div>
                                            <div class="col-md-8">{{ $mouvement->immobilisation->code_barre }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Désignation :</div>
                                            <div class="col-md-8">{{ $mouvement->immobilisation->designation }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Famille :</div>
                                            <div class="col-md-8">{{ $mouvement->immobilisation->famille ? $mouvement->immobilisation->famille->libelle : '-' }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Voir :</div>
                                            <div class="col-md-8">
                                                <a href="{{ route('immobilisations.show', $mouvement->immobilisation->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Détails de l'immobilisation
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            Aucune immobilisation associée à ce mouvement
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Commentaire</h6>
                                </div>
                                <div class="card-body">
                                    {{ $mouvement->commentaire ?? 'Aucun commentaire' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Historique</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-3 fw-bold">Créé le :</div>
                                        <div class="col-md-9">{{ $mouvement->created_at ? $mouvement->created_at->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3 fw-bold">Dernière modification :</div>
                                        <div class="col-md-9">{{ $mouvement->updated_at ? $mouvement->updated_at->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
