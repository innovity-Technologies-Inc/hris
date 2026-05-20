<x-guest-layout>
    <div class="mb-4 text-muted fw-semibold">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label small">Corporate Email</label>
            <div class="input-group-custom">
                <input id="email" 
                       type="email" 
                       name="email" 
                       class="form-control-custom @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="name@company.com">
                <i class="fas fa-envelope"></i>
            </div>
            @error('email')
                <div class="invalid-feedback d-block mt-n2 mb-3 ps-2">
                    <small class="fw-bold">{{ $message }}</small>
                </div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn-premium">
                {{ __('Email Password Reset Link') }} <i class="fas fa-paper-plane ms-2 fs-13"></i>
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="small link-premium">
                <i class="fas fa-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>

