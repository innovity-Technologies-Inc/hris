<x-guest-layout>
    <div class="mb-4 text-muted fw-semibold">
        {{ __('This is a secure area of the application. Please confirm your password before continuing to protect your sensitive data.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label small">Secure Password</label>
            <div class="input-group-custom">
                <input id="password" 
                       type="password" 
                       name="password" 
                       class="form-control-custom @error('password') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="current-password">
                <i class="fas fa-shield-alt"></i>
                <i class="fas fa-eye password-toggle"></i>
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn-premium">
                {{ __('Confirm Access') }} <i class="fas fa-lock-open ms-2 fs-13"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
