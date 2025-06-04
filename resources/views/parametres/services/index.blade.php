@extends("layouts.app")

@section("title", "Services")

@section("content")
<div class="container">
    <h1>Services</h1>
    <p>Gérez les services auxquels vos immobilisations sont affectées.</p>

    <div class="mb-3">
        {{-- Add permission check if needed --}}
        <a href="{{ route("parametres.services.create") }}" class="btn btn-success">Nouveau Service</a>
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
                <th>Site Associé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->code }}</td>
                    <td>{{ $service->libelle }}</td>
                    <td>{{ $service->site->libelle ?? "N/A" }}</td> {{-- Assuming relation exists --}}
                    <td>
                        {{-- <a href="{{ route("parametres.services.show", $service) }}" class="btn btn-info btn-sm">Voir</a> --}}
                        <a href="{{ route("parametres.services.edit", $service) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route("parametres.services.destroy", $service) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer ce service ?");">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucun service trouvé pour ce dossier.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $services->links() }}
</div>
@endsection
