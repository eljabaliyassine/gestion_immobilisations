<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1 text-danger">{{ __('Delete Account') }}</h4>
        <p class="card-text text-muted">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </div>

    <div class="card-body">
        <button type="button"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#confirmUserDeletionModal">
            {{ __('Delete Account') }}
        </button>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade"
     id="confirmUserDeletionModal"
     tabindex="-1"
     aria-labelledby="confirmUserDeletionModalLabel"
     aria-hidden="true"
     @if($errors->userDeletion->isNotEmpty()) data-bs-show="true" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                    {{ __('Are you sure you want to delete your account?') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="{{ route('user.profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-body">
                    <p class="text-muted mb-4">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mb-3">
                        <label for="password" class="visually-hidden">{{ __('Password') }}</label>
                        <input type="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="{{ __('Password') }}"
                               required>

                        @if($errors->userDeletion->get('password'))
                            <div class="invalid-feedback">
                                @foreach($errors->userDeletion->get('password') as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si il y a des erreurs, ouvrir automatiquement le modal
    @if($errors->userDeletion->isNotEmpty())
        var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
        modal.show();
    @endif
});
</script>
