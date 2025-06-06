@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Nouveau compte comptable</h5>
                    <a href="{{ route('parametres.comptescompta.index') }}" class="btn btn-secondary">
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

                    <form action="{{ route('parametres.comptescompta.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero" class="form-control-label">Numéro <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="libelle" class="form-control-label">Libellé <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="libelle" name="libelle" value="{{ old('libelle') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-control-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="immobilisation" {{ old('type') == 'immobilisation' ? 'selected' : '' }}>Immobilisation</option>
                                        <option value="dotation" {{ old('type') == 'dotation' ? 'selected' : '' }}>Dotation</option>
                                        <option value="amortissement" {{ old('type') == 'amortissement' ? 'selected' : '' }}>Amortissement</option>
                                        <option value="pci" {{ old('type') == 'pci' ? 'selected' : '' }}>Produit cession</option>
					<option value="vnc" {{ old('type') == 'vnc' ? 'selected' : '' }}>VNC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categorie" class="form-control-label">Catégorie</label>
                                    <input type="text" class="form-control" id="categorie" name="categorie" value="{{ old('categorie') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
