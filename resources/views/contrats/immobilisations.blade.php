@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Immobilisations liées au contrat</h5>
                    <div>
                        <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye me-2"></i>Voir le contrat
                        </a>
                        <a href="{{ route('contrats.index') }}" class="btn btn-secondary">
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
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Informations du contrat</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Référence :</div>
                                        <div class="col-md-8">{{ $contrat->reference }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Type :</div>
                                        <div class="col-md-8">
                                            <span class="badge bg-{{ $contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($contrat->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Période :</div>
                                        <div class="col-md-8">
                                            {{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : '-' }}
                                            @if($contrat->date_fin)
                                                au {{ $contrat->date_fin->format('d/m/Y') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Ajouter une immobilisation</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('contrats.lierImmobilisations', $contrat->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="immobilisation_id" class="form-control-label">Immobilisation <span class="text-danger">*</span></label>
                                            <select class="form-control" id="immobilisation_id" name="immobilisation_id" required>
                                                <option value="">Sélectionner une immobilisation</option>
                                                @foreach($immobilisationsDisponibles as $immobilisation)
                                                    <option value="{{ $immobilisation->id }}">
                                                        {{ $immobilisation->code_barre }} - {{ $immobilisation->designation }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary">Ajouter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Immobilisations liées ({{ $immobilisations->count() }})</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Désignation</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Famille</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Localisation</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($immobilisations as $immobilisation)
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="d-flex px-3 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $immobilisation->code_barre }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <p class="text-sm font-weight-bold mb-0">{{ $immobilisation->designation }}</p>
                                                </td>
                                                <td class="align-middle">
                                                    <p class="text-sm font-weight-bold mb-0">{{ $immobilisation->famille ? $immobilisation->famille->libelle : '-' }}</p>
                                                </td>
                                                <td class="align-middle">
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ $immobilisation->site ? $immobilisation->site->libelle : '-' }}
                                                        @if($immobilisation->service)
                                                            / {{ $immobilisation->service->libelle }}
                                                        @endif
                                                    </p>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex">
                                                        <a href="{{ route('immobilisations.show', $immobilisation->id) }}" class="btn btn-link text-info px-2 mb-0" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="{{ route('contrats.detachImmobilisation', [$contrat->id, $immobilisation->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger px-2 mb-0" title="Retirer" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette immobilisation du contrat ?')">
                                                                <i class="fas fa-unlink"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">Aucune immobilisation liée à ce contrat</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
