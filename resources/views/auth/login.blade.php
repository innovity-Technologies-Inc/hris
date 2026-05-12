<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="fas fa-envelope text-muted"></i>
                </span>
                <input id="email" 
                       type="email" 
                       name="email" 
                       class="form-control border-start-0 @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="Enter your email"
                       autocomplete="username">
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label small fw-semibold">Password</label>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="fas fa-lock text-muted"></i>
                </span>
                <input id="password" 
                       type="password" 
                       name="password" 
                       class="form-control border-start-0 @error('password') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="current-password">
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-2">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4">
            <div class="form-check custom-checkbox">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label small text-muted" for="remember_me">
                    Keep me logged in
                </label>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary py-2 shadow-sm">
                Sign In
            </button>
        </div>
    </form>
</x-guest-layout>
