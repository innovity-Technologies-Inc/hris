<x-guest-layout>
    <div class="mb-4 text-muted fw-semibold">
        {{ __('Secure your account by choosing a strong new password. Ensure it includes a mix of characters for maximum protection.') }}
    </div>

    <!-- General Errors (Manipulation / Expired Links) -->
    @if ($errors->has('email') || $errors->has('token'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 py-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3 fs-5"></i>
                <div class="small fw-bold">
                    {{ $errors->first('email') ?: $errors->first('token') }}
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="email" value="{{ encrypt($request->email) }}">

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label small">New Password</label>
            <div class="input-group-custom">
                <input id="password" 
                       type="password" 
                       name="password" 
                       class="form-control-custom @error('password') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="new-password">
                <i class="fas fa-key"></i>
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label small">Confirm New Password</label>
            <div class="input-group-custom">
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation" 
                       class="form-control-custom @error('password_confirmation') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="new-password">
                <i class="fas fa-shield-alt"></i>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <div class="form-check custom-checkbox mb-3 ps-1">
                <input class="form-check-input" type="checkbox" id="show_passwords">
                <label class="form-check-label small text-muted fw-semibold" for="show_passwords">
                    Show Passwords
                </label>
            </div>

            <button type="submit" class="btn-premium">
                {{ __('Reset Password') }} <i class="fas fa-sync-alt ms-2 fs-13"></i>
            </button>
        </div>

        <script>
            document.getElementById('show_passwords').addEventListener('change', function() {
                const passwordInput = document.getElementById('password');
                const passwordConfirmInput = document.getElementById('password_confirmation');
                const type = this.checked ? 'text' : 'password';
                passwordInput.type = type;
                passwordConfirmInput.type = type;
            });
        </script>
    </form>
</x-guest-layout>
