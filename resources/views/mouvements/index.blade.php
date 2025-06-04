@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Liste des mouvements</h5>
                    <a href="{{ route('mouvements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouveau mouvement
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Immobilisation</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Localisation</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Commentaire</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mouvements as $mouvement)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $mouvement->date_mouvement ? $mouvement->date_mouvement->format('d/m/Y') : '-' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">
                                                {{ $mouvement->immobilisation ? $mouvement->immobilisation->code_barre : '-' }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $mouvement->immobilisation ? $mouvement->immobilisation->designation : '-' }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $mouvement->type_mouvement == 'entree' ? 'success' : ($mouvement->type_mouvement == 'sortie' ? 'danger' : 'info') }}">
                                                {{ $mouvement->type_formatted }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">
                                                {{ $mouvement->site ? $mouvement->site->libelle : '-' }}
                                                @if($mouvement->service)
                                                    / {{ $mouvement->service->libelle }}
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm text-secondary mb-0">
                                                {{ Str::limit($mouvement->commentaire, 30) ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex">
                                                <a href="{{ route('mouvements.show', $mouvement->id) }}" class="btn btn-sm btn-info mx-1" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('mouvements.edit', $mouvement->id) }}" class="btn btn-sm btn-warning mx-1" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('mouvements.destroy', $mouvement->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger mx-1" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce mouvement ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Aucun mouvement trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $mouvements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
