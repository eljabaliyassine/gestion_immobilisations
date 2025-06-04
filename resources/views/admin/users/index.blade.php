@extends("layouts.app")

@section("title", "Gestion des Utilisateurs")

@section("content")
<div class="container">
    <h1>Gestion des Utilisateurs</h1>

    <div class="mb-3">
        {{-- Add permission check if needed --}}
        <a href="{{ route("admin.users.create") }}" class="btn btn-success">Nouvel Utilisateur</a>
    </div>

    @if (session("success"))
        <div class="alert alert-success">{{ session("success") }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Compte</th>
                <th>Rôle</th>
                <th>Dossier Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->compte->nom ?? "N/A" }}</td>
                    <td>{{ $user->role->nom ?? "N/A" }}</td>
                    <td>{{ $user->currentDossier->nom ?? "N/A" }}</td>
                    <td>
                        <a href="{{ route("admin.users.show", $user) }}" class="btn btn-info btn-sm">Voir</a>
                        <a href="{{ route("admin.users.edit", $user) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route("admin.users.destroy", $user) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");">
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
