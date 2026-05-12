<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label small">Corporate Email</label>
            <div class="input-group-custom">
                <input id="email" 
                       type="email" 
                       name="email" 
                       class="form-control-custom @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
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
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label small">Secret Password</label>
            </div>
            <div class="input-group-custom">
                <input id="password" 
                       type="password" 
                       name="password" 
                       class="form-control-custom @error('password') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="current-password">
                <i class="fas fa-shield-alt"></i>
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

        <!-- Extra Actions -->
        <div class="d-flex align-items-center justify-content-between mb-4 ps-1">
            <div class="form-check custom-checkbox">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label small text-muted fw-semibold" for="remember_me">
                    Stay logged in
                </label>
            </div>
            @if (Route::has('password.request'))
                <a class="small link-premium" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            @endif
        </div>

        <div class="d-grid">
            <button type="submit" class="btn-premium">
                Sign In to Portal <i class="fas fa-chevron-right ms-2 fs-13"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
