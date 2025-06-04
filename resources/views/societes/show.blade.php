@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de la société</h5>
                    <div>
                        @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('societes.edit', $societe->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @endif
                        <a href="{{ route('societes.index') }}" class="btn btn-secondary btn-sm">
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
                                    <td>{{ $societe->code }}</td>
                                </tr>
                                <tr>
                                    <th>Nom :</th>
                                    <td>{{ $societe->nom }}</td>
                                </tr>
                                <tr>
                                    <th>SIRET :</th>
                                    <td>{{ $societe->siret ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Statut :</th>
                                    <td>
                                        @if($societe->est_actif)
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
                                    <td>{{ $societe->adresse ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Code postal :</th>
                                    <td>{{ $societe->code_postal ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ville :</th>
                                    <td>{{ $societe->ville ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Pays :</th>
                                    <td>{{ $societe->pays ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone :</th>
                                    <td>{{ $societe->telephone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td>{{ $societe->email ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Clients associés</h6>
                            @if($societe->clients->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Téléphone</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($societe->clients as $client)
                                            <tr>
                                                <td>{{ $client->code }}</td>
                                                <td>{{ $client->nom }}</td>
                                                <td>{{ $client->email }}</td>
                                                <td>{{ $client->telephone }}</td>
                                                <td>
                                                    @if($client->est_actif)
                                                        <span class="badge bg-success">Actif</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Aucun client associé à cette société.</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Dossiers associés</h6>
                            @if($societe->dossiers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Nom</th>
                                                <th>Client</th>
                                                <th>Exercice</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($societe->dossiers as $dossier)
                                            <tr>
                                                <td>{{ $dossier->code }}</td>
                                                <td>{{ $dossier->nom }}</td>
                                                <td>{{ $dossier->client->nom ?? 'N/A' }}</td>
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
                                <p class="text-muted">Aucun dossier associé à cette société.</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Utilisateurs associés</h6>
                            @if($societe->users->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Client</th>
                                                <th>Rôle</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($societe->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->client->nom ?? 'N/A' }}</td>
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
                                <p class="text-muted">Aucun utilisateur associé à cette société.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
