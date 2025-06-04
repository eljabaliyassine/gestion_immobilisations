<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1">{{ __('Profile Information') }}</h4>
        <p class="card-text text-muted">{{ __("Update your account's profile information and email address.") }}</p>
    </div>

    <div class="card-body">
        <form method="post" action="{{ route('user.profile.update') }}">
            @csrf
            @method('patch')

            <!-- Champ Nom -->
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       autofocus
                       autocomplete="name">

                @if($errors->get('name'))
                    <div class="invalid-feedback">
                        @foreach($errors->get('name') as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Champ Email -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       autocomplete="username">

                @if($errors->get('email'))
                    <div class="invalid-feedback">
                        @foreach($errors->get('email') as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Vérification Email -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            {{ __('Your email address is unverified.') }}
                        </div>
                        <div class="ms-3">
                            <form method="post" action="{{ route('verification.send') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-2 text-success">
                            <small>{{ __('A new verification link has been sent to your email address.') }}</small>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Boutons d'action -->
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Save') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <div class="text-success">
                        <small>{{ __('Saved.') }}</small>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
