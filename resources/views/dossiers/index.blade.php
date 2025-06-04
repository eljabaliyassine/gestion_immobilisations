@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sélection du dossier</h5>
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <a href="{{ route('dossiers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nouveau dossier
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

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
                                @forelse ($dossiers as $dossier)
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('dossiers.select', $dossier->id) }}" class="btn btn-success btn-sm" title="Sélectionner">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="{{ route('dossiers.show', $dossier->id) }}" class="btn btn-info btn-sm" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->client_id == $dossier->client_id))
                                            <a href="{{ route('dossiers.edit', $dossier->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(Auth::user()->isSuperAdmin())
                                            <form action="{{ route('dossiers.destroy', $dossier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucun dossier trouvé</td>
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
@endsection
