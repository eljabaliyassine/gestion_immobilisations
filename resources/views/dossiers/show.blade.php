@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails du dossier</h5>
                    <div>
                        @if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->client_id == $dossier->client_id))
                        <a href="{{ route('dossiers.edit', $dossier->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @endif
                        <a href="{{ route('dossiers.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Informations générales</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 30%">Code :</th>
                                    <td>{{ $dossier->code }}</td>
                                </tr>
                                <tr>
                                    <th>Nom :</th>
                                    <td>{{ $dossier->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Libellé :</th>
                                    <td>{{ $dossier->libelle }}</td>
                                </tr>
                                <tr>
                                    <th>Société :</th>
                                    <td>{{ $dossier->societe->nom ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Client :</th>
                                    <td>{{ $dossier->client->nom ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Statut :</th>
                                    <td>
                                        @if($dossier->est_cloture)
                                            <span class="badge bg-danger">Clôturé</span>
                                        @else
                                            <span class="badge bg-success">Actif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Exercice comptable</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 30%">Début d'exercice :</th>
                                    <td>{{ $dossier->exercice_debut ? $dossier->exercice_debut->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Fin d'exercice :</th>
                                    <td>{{ $dossier->exercice_fin ? $dossier->exercice_fin->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Date de clôture :</th>
                                    <td>{{ $dossier->date_cloture ? $dossier->date_cloture->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Description :</th>
                                    <td>{{ $dossier->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Créé le :</th>
                                    <td>{{ $dossier->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Mis à jour le :</th>
                                    <td>{{ $dossier->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Utilisateurs associés</h6>
                            @if($dossier->users->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Rôle</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dossier->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role->name ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucun utilisateur associé à ce dossier.</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Immobilisations</h6>
                            @if($dossier->immobilisations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Désignation</th>
                                                <th>Date acquisition</th>
                                                <th>Valeur acquisition</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dossier->immobilisations as $immobilisation)
                                            <tr>
                                                <td>{{ $immobilisation->code }}</td>
                                                <td>{{ $immobilisation->designation }}</td>
                                                <td>{{ $immobilisation->date_acquisition ? $immobilisation->date_acquisition->format('d/m/Y') : 'N/A' }}</td>
                                                <td>{{ number_format($immobilisation->valeur_acquisition, 2, ',', ' ') }} DH</td>
                                                <td>
                                                    @if($immobilisation->est_actif)
                                                        <span class="badge bg-success">Actif</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('immobilisations.show', $immobilisation->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucune immobilisation associée à ce dossier.</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Contrats</h6>
                            @if($dossier->contrats->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Référence</th>
                                                <th>Type</th>
                                                <th>Date début</th>
                                                <th>Date fin</th>
                                                <th>Montant</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dossier->contrats as $contrat)
                                            <tr>
                                                <td>{{ $contrat->reference }}</td>
                                                <td>{{ $contrat->type }}</td>
                                                <td>{{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : 'N/A' }}</td>
                                                <td>{{ $contrat->date_fin ? $contrat->date_fin->format('d/m/Y') : 'N/A' }}</td>
                                                <td>{{ number_format($contrat->montant, 2, ',', ' ') }} DH</td>
                                                <td>
                                                    <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucun contrat associé à ce dossier.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
