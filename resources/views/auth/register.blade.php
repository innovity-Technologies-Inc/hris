<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label small">Full Name</label>
            <div class="input-group-custom">
                <input id="name" 
                       type="text" 
                       name="name" 
                       class="form-control-custom @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus 
                       placeholder="John Doe"
                       autocomplete="name">
                <i class="fas fa-user"></i>
            </div>
            @error('name')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

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
            <label for="password" class="form-label small">Password</label>
            <div class="input-group-custom">
                <input id="password" 
                       type="password" 
                       name="password" 
                       class="form-control-custom @error('password') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="new-password">
                <i class="fas fa-shield-alt"></i>
                <i class="fas fa-eye password-toggle"></i>
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label small">Confirm Password</label>
            <div class="input-group-custom">
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation" 
                       class="form-control-custom @error('password_confirmation') is-invalid @enderror" 
                       required 
                       placeholder="••••••••"
                       autocomplete="new-password">
                <i class="fas fa-check-circle"></i>
                <i class="fas fa-eye password-toggle"></i>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <a class="small link-premium" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn-premium">
                Create Account <i class="fas fa-user-plus ms-2 fs-13"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
