@extends("layouts.app")

@section("title", "Fournisseurs")

@section("content")
<div class="container">
    <h1>Fournisseurs</h1>
    <p>Gérez les fournisseurs de vos immobilisations.</p>

    <div class="mb-3">
        {{-- Add permission check if needed --}}
        <a href="{{ route("parametres.fournisseurs.create") }}" class="btn btn-success">Nouveau Fournisseur</a>
    </div>

    @if (session("success"))
        <div class="alert alert-success">{{ session("success") }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fournisseurs as $fournisseur)
                <tr>
                    <td>{{ $fournisseur->id }}</td>
                    <td>{{ $fournisseur->code }}</td>
                    <td>{{ $fournisseur->nom }}</td>
                    <td>{{ $fournisseur->adresse }}</td>
                    <td>{{ $fournisseur->telephone }}</td>
                    <td>{{ $fournisseur->email }}</td>
                    <td>
                        {{-- <a href="{{ route("parametres.fournisseurs.show", $fournisseur) }}" class="btn btn-info btn-sm">Voir</a> --}}
                        <a href="{{ route("parametres.fournisseurs.edit", $fournisseur) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route("parametres.fournisseurs.destroy", $fournisseur) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce fournisseur ?");">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucun fournisseur trouvé pour ce dossier.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $fournisseurs->links() }}
</div>
@endsection
