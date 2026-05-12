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
            <label for="email" class="form-label small">Email Address</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope fs-14"></i>
                </span>
                <input id="email" 
                       type="email" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="name@company.com"
                       autocomplete="username">
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-2 ps-1">
                    <small>{{ $message }}</small>
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label small">Password</label>
                @if (Route::has('password.request'))
                    <a class="small forgot-link" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-shield-alt fs-14"></i>
                </span>
                <input id="password" 
                       type="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="current-password">
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-2 ps-1">
                    <small>{{ $message }}</small>
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 ps-1">
            <div class="form-check custom-checkbox">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label small text-muted" for="remember_me">
                    Keep me signed in
                </label>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary shadow-sm">
                Sign In <i class="fas fa-arrow-right ms-2 fs-12"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
