@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Modification d\'une famille') }}</span>
                    <a href="{{ route('parametres.familles.index') }}" class="btn btn-sm btn-secondary">Retour à la liste</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('parametres.familles.update', $famille->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="code" class="col-md-4 col-form-label text-md-end">{{ __('Code') }} *</label>
                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $famille->code) }}" required autocomplete="code" autofocus>
                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="libelle" class="col-md-4 col-form-label text-md-end">{{ __('Libellé') }} *</label>
                            <div class="col-md-6">
                                <input id="libelle" type="text" class="form-control @error('libelle') is-invalid @enderror" name="libelle" value="{{ old('libelle', $famille->libelle) }}" required autocomplete="libelle">
                                @error('libelle')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comptecompta_immobilisation_id" class="col-md-4 col-form-label text-md-end">{{ __('Compte Immobilisation') }} *</label>
                            <div class="col-md-6">
                                <select id="comptecompta_immobilisation_id" class="form-control @error('comptecompta_immobilisation_id') is-invalid @enderror" name="comptecompta_immobilisation_id" required>
                                    <option value="">Sélectionnez un compte</option>
                                    @foreach($comptesCompta as $compte)
                                        <option value="{{ $compte->id }}" {{ (old('comptecompta_immobilisation_id', $famille->comptecompta_immobilisation_id) == $compte->id) ? 'selected' : '' }}>
                                            {{ $compte->numero }} - {{ $compte->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('comptecompta_immobilisation_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comptecompta_amortissement_id" class="col-md-4 col-form-label text-md-end">{{ __('Compte Amortissement') }} *</label>
                            <div class="col-md-6">
                                <select id="comptecompta_amortissement_id" class="form-control @error('comptecompta_amortissement_id') is-invalid @enderror" name="comptecompta_amortissement_id" required>
                                    <option value="">Sélectionnez un compte</option>
                                    @foreach($comptesCompta as $compte)
                                        <option value="{{ $compte->id }}" {{ (old('comptecompta_amortissement_id', $famille->comptecompta_amortissement_id) == $compte->id) ? 'selected' : '' }}>
                                            {{ $compte->numero }} - {{ $compte->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('comptecompta_amortissement_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comptecompta_dotation_id" class="col-md-4 col-form-label text-md-end">{{ __('Compte Dotation') }} *</label>
                            <div class="col-md-6">
                                <select id="comptecompta_dotation_id" class="form-control @error('comptecompta_dotation_id') is-invalid @enderror" name="comptecompta_dotation_id" required>
                                    <option value="">Sélectionnez un compte</option>
                                    @foreach($comptesCompta as $compte)
                                        <option value="{{ $compte->id }}" {{ (old('comptecompta_dotation_id', $famille->comptecompta_dotation_id) == $compte->id) ? 'selected' : '' }}>
                                            {{ $compte->numero }} - {{ $compte->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('comptecompta_dotation_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="duree_amortissement_par_defaut" class="col-md-4 col-form-label text-md-end">{{ __('Durée d\'amortissement par défaut') }}</label>
                            <div class="col-md-6">
                                <input id="duree_amortissement_par_defaut" type="number" min="1" max="50" class="form-control @error('duree_amortissement_par_defaut') is-invalid @enderror" name="duree_amortissement_par_defaut" value="{{ old('duree_amortissement_par_defaut', $famille->duree_amortissement_par_defaut) }}" autocomplete="duree_amortissement_par_defaut">
                                @error('duree_amortissement_par_defaut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="methode_amortissement_par_defaut" class="col-md-4 col-form-label text-md-end">{{ __('Méthode d\'amortissement par défaut') }}</label>
                            <div class="col-md-6">
                                <select id="methode_amortissement_par_defaut" class="form-control @error('methode_amortissement_par_defaut') is-invalid @enderror" name="methode_amortissement_par_defaut">
                                    <option value="">Sélectionnez une méthode</option>
                                    <option value="lineaire" {{ old('methode_amortissement_par_defaut', $famille->methode_amortissement_par_defaut) == 'lineaire' ? 'selected' : '' }}>Linéaire</option>
                                    <option value="degressif" {{ old('methode_amortissement_par_defaut', $famille->methode_amortissement_par_defaut) == 'degressif' ? 'selected' : '' }}>Dégressif</option>
                                </select>
                                @error('methode_amortissement_par_defaut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Mettre à jour') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
