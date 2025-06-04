@extends("layouts.app")

@section("title", "Détails de l'Utilisateur : " . $user->name)

@section("content")
<div class="container">
    <h1>Détails de l'Utilisateur : {{ $user->name }}</h1>

    <div class="card mb-3">
        <div class="card-header">Informations Générales</div>
        <div class="card-body">
            <p><strong>ID :</strong> {{ $user->id }}</p>
            <p><strong>Nom :</strong> {{ $user->name }}</p>
            <p><strong>Email :</strong> {{ $user->email }}</p>
            <p><strong>Compte Associé :</strong> <a href="{{ route("comptes.show", $user->compte) }}">{{ $user->compte->nom ?? "N/A" }}</a></p>
            <p><strong>Rôle :</strong> {{ $user->role->nom ?? "N/A" }}</p>
            <p><strong>Dossier Actif :</strong> {{ $user->currentDossier->nom ?? "N/A" }}</p>
            <p><strong>Email Vérifié le :</strong> {{ $user->email_verified_at ? $user->email_verified_at->format("d/m/Y H:i") : "Non vérifié" }}</p>
            <p><strong>Créé le :</strong> {{ $user->created_at->format("d/m/Y H:i") }}</p>
            <p><strong>Mis à jour le :</strong> {{ $user->updated_at->format("d/m/Y H:i") }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Dossiers Accessibles</div>
        <div class="card-body">
            @if ($user->dossiers->isNotEmpty())
                <ul>
                    @foreach ($user->dossiers as $dossier)
                        <li><a href="{{ route("dossiers.show", $dossier) }}">{{ $dossier->nom }}</a></li>
                    @endforeach
                </ul>
            @else
                <p>Cet utilisateur n'a accès à aucun dossier spécifique via la table pivot (vérifier les accès via le rôle/compte).</p>
            @endif
            {{-- Add link/button to manage dossier access for this user --}}
        </div>
    </div>

    <a href="{{ route("admin.users.index") }}" class="btn btn-secondary">Retour à la liste</a>
    <a href="{{ route("admin.users.edit", $user) }}" class="btn btn-warning">Modifier</a>
    <form action="{{ route("admin.users.destroy", $user) }}" method="POST" style="display:inline;" onsubmit="return confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");">
        @csrf
        @method("DELETE")
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>

</div>
@endsection
