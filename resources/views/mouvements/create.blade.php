@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Nouveau mouvement</h5>
                    <a href="{{ route('mouvements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('mouvements.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="immobilisation_id" class="form-control-label">Immobilisation <span class="text-danger">*</span></label>
                                    <select class="form-control" id="immobilisation_id" name="immobilisation_id" required>
                                        <option value="">Sélectionner une immobilisation</option>
                                        @foreach($immobilisations as $immobilisation)
                                            <option value="{{ $immobilisation->id }}" {{ old('immobilisation_id') == $immobilisation->id ? 'selected' : '' }}>
                                                {{ $immobilisation->code_barre }} - {{ $immobilisation->designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_mouvement" class="form-control-label">Type de mouvement <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type_mouvement" name="type_mouvement" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="entree" {{ old('type_mouvement') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                        <option value="sortie" {{ old('type_mouvement') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                                        <option value="transfert" {{ old('type_mouvement') == 'transfert' ? 'selected' : '' }}>Transfert</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_mouvement" class="form-control-label">Date du mouvement <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_mouvement" name="date_mouvement" value="{{ old('date_mouvement', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="site_id" class="form-control-label">Site <span class="text-danger">*</span></label>
                                    <select class="form-control" id="site_id" name="site_id" required>
                                        <option value="">Sélectionner un site</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                                {{ $site->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="service_id" class="form-control-label">Service <span class="text-danger">*</span></label>
                                    <select class="form-control" id="service_id" name="service_id" required>
                                        <option value="">Sélectionner un service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="commentaire" class="form-control-label">Commentaire</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3">{{ old('commentaire') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
