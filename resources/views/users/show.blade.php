@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de l'utilisateur</h5>
                    <div>
                        @if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->client_id == $user->client_id))
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
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
                                    <th style="width: 30%">Nom :</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Rôle :</th>
                                    <td>{{ $user->role->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Créé le :</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Mis à jour le :</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Affiliations</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 30%">Société :</th>
                                    <td>
                                        @if($user->societe)
                                            <a href="{{ route('societes.show', $user->societe->id) }}">{{ $user->societe->nom }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Client :</th>
                                    <td>
                                        @if($user->client)
                                            <a href="{{ route('clients.show', $user->client->id) }}">{{ $user->client->nom }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dossier actif :</th>
                                    <td>
                                        @if($user->currentDossier)
                                            <a href="{{ route('dossiers.show', $user->currentDossier->id) }}">{{ $user->currentDossier->nom }}</a>
                                        @else
                                            Aucun
                                        @endif
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Dossiers accessibles</h6>
                            @if($user->dossiers->count() > 0)
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
                                            @foreach($user->dossiers as $dossier)
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
                                <p class="text-muted">Aucun dossier accessible pour cet utilisateur.</p>
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">Gestion des accès</h6>
                            <form action="{{ route('users.update_dossiers', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label">Dossiers accessibles</label>
                                    <div class="row">
                                        @foreach($allDossiers as $dossier)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="dossiers[]" value="{{ $dossier->id }}" id="dossier_{{ $dossier->id }}"
                                                        {{ $user->dossiers->contains($dossier->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="dossier_{{ $dossier->id }}">
                                                        {{ $dossier->nom }} ({{ $dossier->code }})
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
