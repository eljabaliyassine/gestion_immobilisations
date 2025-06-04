@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Détails de l'amortissement</h5>
                    <div>
                        <a href="{{ route('amortissements.edit', $amortissement->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('amortissements.index') }}" class="btn btn-secondary">
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
                                    <h6>Informations de l'amortissement</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Date :</div>
                                        <div class="col-md-8">{{ $amortissement->date_amortissement ? $amortissement->date_amortissement->format('d/m/Y') : '-' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Type :</div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $amortissement->type_amortissement == 'normal' ? 'primary' : 'warning' }}">
                                                {{ $amortissement->type_amortissement == 'normal' ? 'Normal' : 'Exceptionnel' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Montant :</div>
                                        <div class="col-md-8">{{ number_format($amortissement->montant, 2, ',', ' ') }} DH</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Utilisateur :</div>
                                        <div class="col-md-8">{{ $amortissement->user ? $amortissement->user->name : '-' }}</div>
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
                                    @if($amortissement->immobilisation)
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Code :</div>
                                            <div class="col-md-8">{{ $amortissement->immobilisation->code_barre }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Désignation :</div>
                                            <div class="col-md-8">{{ $amortissement->immobilisation->designation }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Famille :</div>
                                            <div class="col-md-8">{{ $amortissement->immobilisation->famille ? $amortissement->immobilisation->famille->libelle : '-' }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fw-bold">Voir :</div>
                                            <div class="col-md-8">
                                                <a href="{{ route('immobilisations.show', $amortissement->immobilisation->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Détails de l'immobilisation
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            Aucune immobilisation associée à cet amortissement
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
                                    {{ $amortissement->commentaire ?? 'Aucun commentaire' }}
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
                                        <div class="col-md-9">{{ $amortissement->created_at ? $amortissement->created_at->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3 fw-bold">Dernière modification :</div>
                                        <div class="col-md-9">{{ $amortissement->updated_at ? $amortissement->updated_at->format('d/m/Y H:i') : '-' }}</div>
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
