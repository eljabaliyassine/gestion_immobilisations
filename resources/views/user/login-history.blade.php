@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('Historique des connexions') }}</h1>
                    <p class="text-muted mb-0">{{ __('Consultez vos dernières connexions') }}</p>
                </div>
                <a href="{{ route('user.profile.edit') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    {{ __('Retour au profil') }}
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2" style="color: var(--secondary-color);"></i>
                        {{ __('Dernières connexions') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($loginHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Date & Heure') }}</th>
                                        <th>{{ __('Adresse IP') }}</th>
                                        <th>{{ __('Navigateur') }}</th>
                                        <th>{{ __('Statut') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loginHistory as $login)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium">{{ $login['login_at']->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ $login['login_at']->format('H:i:s') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <code class="bg-light px-2 py-1 rounded">{{ $login['ip'] }}</code>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(str_contains($login['user_agent'], 'iPhone'))
                                                        <i class="fab fa-apple me-2 text-secondary"></i>
                                                    @elseif(str_contains($login['user_agent'], 'Chrome'))
                                                        <i class="fab fa-chrome me-2 text-warning"></i>
                                                    @else
                                                        <i class="fas fa-globe me-2 text-info"></i>
                                                    @endif
                                                    <span class="small">{{ Str::limit($login['user_agent'], 50) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($login['status'] === 'success')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        {{ __('Réussie') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>
                                                        {{ __('Échouée') }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('Aucun historique disponible') }}</h5>
                            <p class="text-muted">{{ __('Vos connexions futures apparaîtront ici') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations de sécurité -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="card-title text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('Sécurité') }}
                    </h6>
                    <p class="card-text text-muted mb-0">
                        {{ __('Si vous remarquez une activité suspecte, changez immédiatement votre mot de passe et contactez notre support.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge.bg-success {
    background-color: var(--success-color) !important;
}
.badge.bg-danger {
    background-color: var(--danger-color) !important;
}
.text-warning {
    color: var(--warning-color) !important;
}
</style>
@endsection