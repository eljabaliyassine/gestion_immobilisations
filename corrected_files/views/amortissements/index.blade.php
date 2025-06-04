@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Liste des amortissements</h5>
                    <a href="{{ route('amortissements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouvel amortissement
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Montant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Commentaire</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($amortissements as $amortissement)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $amortissement->date_amortissement ? $amortissement->date_amortissement->format('d/m/Y') : '-' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">
                                                {{ $amortissement->immobilisation ? $amortissement->immobilisation->code_barre : '-' }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $amortissement->immobilisation ? $amortissement->immobilisation->designation : '-' }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $amortissement->type_amortissement == 'normal' ? 'primary' : 'warning' }}">
                                                {{ $amortissement->type_amortissement == 'normal' ? 'Normal' : 'Exceptionnel' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm font-weight-bold mb-0">{{ number_format($amortissement->montant, 2, ',', ' ') }} DH</p>
                                        </td>
                                        <td class="align-middle">
                                            <p class="text-sm text-secondary mb-0">
                                                {{ Str::limit($amortissement->commentaire, 30) ?? '-' }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex">
                                                <a href="{{ route('amortissements.show', $amortissement->id) }}" class="btn btn-sm btn-info mx-1" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('amortissements.edit', $amortissement->id) }}" class="btn btn-sm btn-warning mx-1" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('amortissements.destroy', $amortissement->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger mx-1" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet amortissement ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Aucun amortissement trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $amortissements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
