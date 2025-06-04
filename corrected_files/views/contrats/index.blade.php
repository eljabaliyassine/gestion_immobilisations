@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Liste des contrats</h5>
                    <a href="{{ route('contrats.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouveau contrat
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Référence</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prestataire</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Période</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Montant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Immobilisations</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contrats as $contrat)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $contrat->reference }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $contrat->type == 'maintenance' ? 'info' : ($contrat->type == 'location' ? 'primary' : ($contrat->type == 'leasing' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($contrat->type) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">{{ $contrat->prestataire ? $contrat->prestataire->nom : '-' }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">
                                                {{ $contrat->date_debut ? $contrat->date_debut->format('d/m/Y') : '-' }}
                                                @if($contrat->date_fin)
                                                    au {{ $contrat->date_fin->format('d/m/Y') }}
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">{{ number_format($contrat->montant, 2, ',', ' ') }} DH</p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">{{ $contrat->immobilisations_count ?? 0 }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex">
                                                <a href="{{ route('contrats.show', $contrat->id) }}" class="btn btn-sm btn-info mx-1" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('contrats.edit', $contrat->id) }}" class="btn btn-sm btn-warning mx-1" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('contrats.destroy', $contrat->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger mx-1" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Aucun contrat trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $contrats->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
