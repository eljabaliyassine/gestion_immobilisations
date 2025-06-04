@extends("layouts.app")

@section("title", "Sessions d'Inventaire")

@section("content")
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Sessions d'Inventaire</h1>
        <a href="{{ route('inventaires.create') }}" class="btn btn-primary">Nouvelle Session d'Inventaire</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">Liste des sessions</div>
        <div class="card-body">
            @if($inventaires->isEmpty())
                <p>Aucune session d'inventaire n'a été créée pour ce dossier.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <th>Description</th>
                            <th>Créé par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inventaires as $inventaire)
                            <tr>
                                <td>{{ $inventaire->reference }}</td>
                                <td>{{ $inventaire->date_debut->format('d/m/Y') }}</td>
                                <td>{{ $inventaire->date_fin ? $inventaire->date_fin->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $inventaire->statut == 'Terminé' ? 'success' : ($inventaire->statut == 'En cours' ? 'warning' : 'secondary') }}">
                                        {{ $inventaire->statut }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($inventaire->description, 50) }}</td>
                                <td>{{ $inventaire->user->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('inventaires.show', $inventaire) }}" class="btn btn-sm btn-info" title="Voir"><i class="bi bi-eye"></i></a>
                                    @if($inventaire->statut != 'Terminé')
                                        <a href="{{ route('inventaires.edit', $inventaire) }}" class="btn btn-sm btn-warning" title="Modifier"><i class="bi bi-pencil"></i></a>
                                        {{-- Add delete button if needed, with confirmation --}}
                                        {{-- <form action="{{ route('inventaires.destroy', $inventaire) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette session ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                                        </form> --}}
                                    @endif
                                     <a href="{{ route('inventaires.results', $inventaire) }}" class="btn btn-sm btn-secondary" title="Résultats"><i class="bi bi-clipboard-data"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination Links --}}
                <div class="d-flex justify-content-center">
                     {{ $inventaires->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

