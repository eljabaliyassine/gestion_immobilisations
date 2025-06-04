@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails du client</h5>
                    <div>
                        @if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->societe_id == $client->societe_id))
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @endif
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">
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
                                    <td>{{ $client->code }}</td>
                                </tr>
                                <tr>
                                    <th>Nom :</th>
                                    <td>{{ $client->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Société :</th>
                                    <td>{{ $client->societe->nom ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>ICE :</th>
                                    <td>{{ $client->identifiant_unique ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Statut :</th>
                                    <td>
                                        @if($client->actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Coordonnées</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 30%">Adresse :</th>
                                    <td>{{ $client->adresse ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Code postal :</th>
                                    <td>{{ $client->code_postal ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ville :</th>
                                    <td>{{ $client->ville ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Pays :</th>
                                    <td>{{ $client->pays ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone :</th>
                                    <td>{{ $client->telephone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td>{{ $client->email ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Dossiers associés</h6>
                            @if($client->dossiers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Nom</th>
                                                <th>Exercice</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($client->dossiers as $dossier)
                                            <tr>
                                                <td>{{ $dossier->code }}</td>
                                                <td>{{ $dossier->nom }}</td>
                                                <td>
                                                    @if($dossier->exercice_debut && $dossier->exercice_fin)
                                                        {{ $dossier->exercice_debut->format('d/m/Y') }} - {{ $dossier->exercice_fin->format('d/m/Y') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($dossier->est_cloture)
                                                        <span class="badge bg-danger">Clôturé</span>
                                                    @else
                                                        <span class="badge bg-success">Actif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('dossiers.show', $dossier->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucun dossier associé à ce client.</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Utilisateurs associés</h6>
                            @if($client->users->count() > 0)
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
                                            @foreach($client->users as $user)
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
                                <p class="text-muted">Aucun utilisateur associé à ce client.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
