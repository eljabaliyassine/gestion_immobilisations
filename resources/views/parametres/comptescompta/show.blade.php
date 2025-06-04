@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Détails du compte comptable</h5>
                    <div>
                        <a href="{{ route('parametres.comptescompta.edit', $comptecompta->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('parametres.comptescompta.index') }}" class="btn btn-secondary">
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
                                    <h6>Informations du compte</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Numéro :</div>
                                        <div class="col-md-8">{{ $comptecompta->numero }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Libellé :</div>
                                        <div class="col-md-8">{{ $comptecompta->libelle }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Type :</div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $comptecompta->type == 'actif' ? 'success' : ($comptecompta->type == 'passif' ? 'info' : ($comptecompta->type == 'charge' ? 'warning' : 'primary')) }}">
                                                {{ ucfirst($comptecompta->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Catégorie :</div>
                                        <div class="col-md-8">{{ $comptecompta->categorie ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Informations complémentaires</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Créé le :</div>
                                        <div class="col-md-8">{{ $comptecompta->created_at ? $comptecompta->created_at->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Modifié le :</div>
                                        <div class="col-md-8">{{ $comptecompta->updated_at ? $comptecompta->updated_at->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Dossier :</div>
                                        <div class="col-md-8">{{ $comptecompta->dossier ? $comptecompta->dossier->nom : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Description</h6>
                                </div>
                                <div class="card-body">
                                    {{ $comptecompta->description ?? 'Aucune description' }}
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
