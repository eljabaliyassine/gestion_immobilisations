@extends("layouts.app")

@section("content")
<div class="container">
    <h1>Gestion des Comptes</h1>
    <div class="mb-3">
        @can("create", App\Models\Compte::class)
            <a href="{{ route("comptes.create") }}" class="btn btn-success">Nouveau Compte</a>
        @endcan
    </div>

    @if (session("success"))
        <div class="alert alert-success">{{ session("success") }}</div>
    @endif
    @if (session("error"))
        <div class="alert alert-danger">{{ session("error") }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Identifiant Unique</th>
                <th>Nb. Dossiers</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($comptes as $compte)
                <tr>
                    <td>{{ $compte->id }}</td>
                    <td>{{ $compte->nom }}</td>
                    <td>{{ $compte->identifiant_unique }}</td>
                    <td>{{ $compte->dossiers_count }}</td>
                    <td>{{ $compte->actif ? "Oui" : "Non" }}</td>
                    <td>
                        @can("view", $compte)
                            <a href="{{ route("comptes.show", $compte) }}" class="btn btn-info btn-sm">Voir</a>
                        @endcan
                        @can("update", $compte)
                            <a href="{{ route("comptes.edit", $compte) }}" class="btn btn-warning btn-sm">Modifier</a>
                        @endcan
                        @can("delete", $compte)
                            <form action="{{ route("comptes.destroy", $compte) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce compte ?");">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucun compte trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $comptes->links() }} 
</div>
@endsection
