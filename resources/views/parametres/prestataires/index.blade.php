@extends("layouts.app")

@section("title", "Prestataires")

@section("content")
<div class="container">
    <h1>Prestataires</h1>
    <p>Gérez les prestataires de services (maintenance, etc.).</p>

    <div class="mb-3">
        {{-- Add permission check if needed --}}
        <a href="{{ route("parametres.prestataires.create") }}" class="btn btn-success">Nouveau Prestataire</a>
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
            @forelse ($prestataires as $prestataire)
                <tr>
                    <td>{{ $prestataire->id }}</td>
                    <td>{{ $prestataire->code }}</td>
                    <td>{{ $prestataire->nom }}</td>
                    <td>{{ $prestataire->adresse }}</td>
                    <td>{{ $prestataire->telephone }}</td>
                    <td>{{ $prestataire->email }}</td>
                    <td>
                        {{-- <a href="{{ route("parametres.prestataires.show", $prestataire) }}" class="btn btn-info btn-sm">Voir</a> --}}
                        <a href="{{ route("parametres.prestataires.edit", $prestataire) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route("parametres.prestataires.destroy", $prestataire) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce prestataire ?");">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucun prestataire trouvé pour ce dossier.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $prestataires->links() }}
</div>
@endsection
