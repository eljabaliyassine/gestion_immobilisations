@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Amortissement exceptionnel</h5>
                    <a href="{{ route('immobilisations.show', $immobilisation->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à l'immobilisation
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

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Immobilisation concernée</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Code :</div>
                                        <div class="col-md-8">{{ $immobilisation->code_barre }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Désignation :</div>
                                        <div class="col-md-8">{{ $immobilisation->designation }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Valeur d'acquisition :</div>
                                        <div class="col-md-8">{{ number_format($immobilisation->valeur_acquisition, 2, ',', ' ') }} DH</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('immobilisations.amortissement.exceptionnel.store', $immobilisation->id) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_amortissement" class="form-control-label">Date d'amortissement <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_amortissement" name="date_amortissement" value="{{ old('date_amortissement', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="montant" class="form-control-label">Montant (DH) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="montant" name="montant" value="{{ old('montant') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="commentaire" class="form-control-label">Justification <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required>{{ old('commentaire') }}</textarea>
                            <small class="form-text text-muted">Veuillez justifier la raison de cet amortissement exceptionnel (dépréciation, obsolescence, etc.)</small>
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
