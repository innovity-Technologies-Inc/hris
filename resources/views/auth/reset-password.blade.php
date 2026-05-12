<x-guest-layout>
    <div class="mb-4 text-muted fw-semibold">
        {{ __('Secure your account by choosing a strong new password. Ensure it includes a mix of characters for maximum protection.') }}
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label small">Corporate Email</label>
            <div class="input-group-custom">
                <input id="email" 
                       type="email" 
                       name="email" 
                       class="form-control-custom @error('email') is-invalid @enderror" 
                       value="{{ old('email', $request->email) }}" 
                       required 
                       autofocus 
                       placeholder="name@company.com"
                       autocomplete="username">
                <i class="fas fa-envelope"></i>
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

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

        <div class="d-grid">
            <button type="submit" class="btn-premium">
                {{ __('Reset Password') }} <i class="fas fa-sync-alt ms-2 fs-13"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
