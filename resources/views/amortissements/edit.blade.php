@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Modifier l'amortissement</h5>
                    <div>
                        <a href="{{ route('amortissements.show', $amortissement->id) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye me-2"></i>Voir
                        </a>
                        <a href="{{ route('amortissements.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
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

                    <form action="{{ route('amortissements.update', $amortissement->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="immobilisation_id" class="form-control-label">Immobilisation <span class="text-danger">*</span></label>
                                    <select class="form-control" id="immobilisation_id" name="immobilisation_id" required>
                                        <option value="">Sélectionner une immobilisation</option>
                                        @foreach($immobilisations as $immobilisation)
                                            <option value="{{ $immobilisation->id }}" {{ old('immobilisation_id', $amortissement->immobilisation_id) == $immobilisation->id ? 'selected' : '' }}>
                                                {{ $immobilisation->code_barre }} - {{ $immobilisation->designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_amortissement" class="form-control-label">Type d'amortissement <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type_amortissement" name="type_amortissement" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="normal" {{ old('type_amortissement', $amortissement->type_amortissement) == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="exceptionnel" {{ old('type_amortissement', $amortissement->type_amortissement) == 'exceptionnel' ? 'selected' : '' }}>Exceptionnel</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_amortissement" class="form-control-label">Date d'amortissement <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_amortissement" name="date_amortissement" value="{{ old('date_amortissement', $amortissement->date_amortissement ? $amortissement->date_amortissement->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="montant" class="form-control-label">Montant (DH) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="montant" name="montant" value="{{ old('montant', $amortissement->montant) }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="commentaire" class="form-control-label">Commentaire</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3">{{ old('commentaire', $amortissement->commentaire) }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
