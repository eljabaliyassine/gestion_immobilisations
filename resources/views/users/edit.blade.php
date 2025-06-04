@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier l'utilisateur</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour conserver l'actuel)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="role_id" class="form-label">Rôle</label>
                                <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un rôle</option>
                                    @foreach($roles as $role)
                                        @if(Auth::user()->isSuperAdmin() || (Auth::user()->isAdmin() && $role->name != 'super_admin'))
                                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="societe_id" class="form-label">Société</label>
                                <select name="societe_id" id="societe_id" class="form-control @error('societe_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner une société</option>
                                    @foreach($societes as $societe)
                                        <option value="{{ $societe->id }}" {{ old('societe_id', $user->societe_id) == $societe->id ? 'selected' : '' }}>
                                            {{ $societe->nom }} ({{ $societe->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('societe_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="client_id" class="form-label">Client</label>
                                <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror">
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" data-societe-id="{{ $client->societe_id }}" {{ old('client_id', $user->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }} ({{ $client->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="current_dossier_id" class="form-label">Dossier par défaut</label>
                                <select name="current_dossier_id" id="current_dossier_id" class="form-control @error('current_dossier_id') is-invalid @enderror">
                                    <option value="">Aucun dossier par défaut</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}" data-client-id="{{ $dossier->client_id }}" {{ old('current_dossier_id', $user->current_dossier_id) == $dossier->id ? 'selected' : '' }}>
                                            {{ $dossier->nom }} ({{ $dossier->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('current_dossier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtrer les clients en fonction de la société sélectionnée
        const societeSelect = document.getElementById('societe_id');
        const clientSelect = document.getElementById('client_id');
        const clientOptions = Array.from(clientSelect.options);
        
        societeSelect.addEventListener('change', function() {
            const societeId = this.value;
            
            // Réinitialiser la liste des clients
            clientSelect.innerHTML = '<option value="">Sélectionner un client</option>';
            
            if (societeId) {
                // Filtrer les clients par société
                const filteredClients = clientOptions.filter(option => {
                    return option.dataset.societeId === societeId;
                });
                
                // Ajouter les clients filtrés
                filteredClients.forEach(option => {
                    clientSelect.appendChild(option.cloneNode(true));
                });
            }
            
            // Réinitialiser le dossier
            document.getElementById('current_dossier_id').value = '';
        });
        
        // Filtrer les dossiers en fonction du client sélectionné
        const dossierSelect = document.getElementById('current_dossier_id');
        const dossierOptions = Array.from(dossierSelect.options);
        
        clientSelect.addEventListener('change', function() {
            const clientId = this.value;
            
            // Réinitialiser la liste des dossiers
            dossierSelect.innerHTML = '<option value="">Aucun dossier par défaut</option>';
            
            if (clientId) {
                // Filtrer les dossiers par client
                const filteredDossiers = dossierOptions.filter(option => {
                    return option.dataset.clientId === clientId;
                });
                
                // Ajouter les dossiers filtrés
                filteredDossiers.forEach(option => {
                    dossierSelect.appendChild(option.cloneNode(true));
                });
            }
        });
    });
</script>
@endsection
