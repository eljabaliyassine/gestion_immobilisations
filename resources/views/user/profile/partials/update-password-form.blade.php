<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1">{{ __('Update Password') }}</h4>
        <p class="card-text text-muted">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <!-- Mot de passe actuel -->
            <div class="mb-3">
                <label for="update_password_current_password" class="form-label">
                    {{ __('Current Password') }}
                </label>
                <input type="password"
                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                       id="update_password_current_password"
                       name="current_password"
                       autocomplete="current-password">

                @if($errors->updatePassword->get('current_password'))
                    <div class="invalid-feedback">
                        @foreach($errors->updatePassword->get('current_password') as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Nouveau mot de passe -->
            <div class="mb-3">
                <label for="update_password_password" class="form-label">
                    {{ __('New Password') }}
                </label>
                <input type="password"
                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                       id="update_password_password"
                       name="password"
                       autocomplete="new-password">

                @if($errors->updatePassword->get('password'))
                    <div class="invalid-feedback">
                        @foreach($errors->updatePassword->get('password') as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Confirmation du mot de passe -->
            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label">
                    {{ __('Confirm Password') }}
                </label>
                <input type="password"
                       class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                       id="update_password_password_confirmation"
                       name="password_confirmation"
                       autocomplete="new-password">

                @if($errors->updatePassword->get('password_confirmation'))
                    <div class="invalid-feedback">
                        @foreach($errors->updatePassword->get('password_confirmation') as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-dark">
                    {{ __('Save') }}
                </button>

                @if (session('status') === 'password-updated')
                    <div class="text-success fade-message">
                        <small>{{ __('Saved.') }}</small>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<style>
.fade-message {
    animation: fadeOut 2s ease-in-out 2s forwards;
}

@keyframes fadeOut {
    0% { opacity: 1; }
    100% { opacity: 0; }
}
</style>
