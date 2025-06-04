@extends("layouts.app")

@section("title", "Sites Géographiques")

@section("content")
<div class="container">
    <h1>Sites Géographiques</h1>
    <p>Gérez les sites où se trouvent vos immobilisations.</p>

    <div class="mb-3">
        {{-- Add permission check if needed --}}
        <a href="{{ route("parametres.sites.create") }}" class="btn btn-success">Nouveau Site</a>
    </div>

    @if (session("success"))
        <div class="alert alert-success">{{ session("success") }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Libellé</th>
                <th>Adresse</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sites as $site)
                <tr>
                    <td>{{ $site->id }}</td>
                    <td>{{ $site->code }}</td>
                    <td>{{ $site->libelle }}</td>
                    <td>{{ $site->adresse }}</td>
                    <td>
                        {{-- <a href="{{ route("parametres.sites.show", $site) }}" class="btn btn-info btn-sm">Voir</a> --}}
                        <a href="{{ route("parametres.sites.edit", $site) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route("parametres.sites.destroy", $site) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce site ?");">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucun site trouvé pour ce dossier.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $sites->links() }}
</div>
@endsection
