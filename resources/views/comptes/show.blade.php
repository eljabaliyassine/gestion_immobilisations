@extends("layouts.app")

@section("content")
<div class="container">
    <h1>Détails du Compte : {{ $compte->nom }}</h1>

    <div class="card mb-3">
        <div class="card-header">Informations Générales</div>
        <div class="card-body">
            <p><strong>ID :</strong> {{ $compte->id }}</p>
            <p><strong>Nom :</strong> {{ $compte->nom }}</p>
            <p><strong>Identifiant Unique :</strong> {{ $compte->identifiant_unique }}</p>
            <p><strong>Adresse :</strong> {{ $compte->adresse ?? "N/A" }}</p>
            <p><strong>Téléphone :</strong> {{ $compte->telephone ?? "N/A" }}</p>
            <p><strong>Email :</strong> {{ $compte->email ?? "N/A" }}</p>
            <p><strong>Actif :</strong> {{ $compte->actif ? "Oui" : "Non" }}</p>
            <p><strong>Créé le :</strong> {{ $compte->created_at->format("d/m/Y H:i") }}</p>
            <p><strong>Mis à jour le :</strong> {{ $compte->updated_at->format("d/m/Y H:i") }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Dossiers Associés</div>
        <div class="card-body">
            @if ($compte->dossiers->isNotEmpty())
                <ul>
                    @foreach ($compte->dossiers as $dossier)
                        <li><a href="{{ route("dossiers.show", $dossier) }}">{{ $dossier->nom }}</a> ({{ $dossier->actif ? "Actif" : "Inactif" }})</li>
                    @endforeach
                </ul>
            @else
                <p>Aucun dossier associé à ce compte.</p>
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Utilisateurs Associés</div>
        <div class="card-body">
             @if ($compte->users->isNotEmpty())
                <ul>
                    @foreach ($compte->users as $user)
                        <li>{{ $user->name }} ({{ $user->email }}) - Rôle: {{ $user->role->name ?? "N/A" }}</li>
                    @endforeach
                </ul>
            @else
                <p>Aucun utilisateur associé directement à ce compte.</p>
            @endif
            {{-- Note: Users might be linked via dossiers instead --}}
        </div>
    </div>

    <a href="{{ route("comptes.index") }}" class="btn btn-secondary">Retour à la liste</a>
    @can("update", $compte)
        <a href="{{ route("comptes.edit", $compte) }}" class="btn btn-warning">Modifier</a>
    @endcan
</div>
@endsection
