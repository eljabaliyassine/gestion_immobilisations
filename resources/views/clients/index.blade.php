@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des clients</h5>
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nouveau client
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
                                    <th>Société</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                <tr>
                                    <td>{{ $client->code }}</td>
                                    <td>{{ $client->nom }}</td>
                                    <td>{{ $client->societe->nom ?? 'N/A' }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->telephone }}</td>
                                    <td>
                                        @if($client->actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info btn-sm" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && Auth::user()->societe_id == $client->societe_id))
                                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(Auth::user()->isSuperAdmin())
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
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
                                    <td colspan="7" class="text-center">Aucun client trouvé</td>
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
