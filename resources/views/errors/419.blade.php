<x-guest-layout title="Session Expired" subtitle="Your secure connection has timed out.">
    <div class="text-center py-4">
        <div class="mb-4">
            <span class="fa-stack fa-3x" style="color: var(--primary-color);">
                <i class="fas fa-circle fa-stack-2x" style="opacity: 0.1;"></i>
                <i class="fas fa-clock fa-stack-1x"></i>
            </span>
        </div>
        <p class="text-muted mb-4 fs-15 fw-semibold">
            For your security, sessions automatically expire after a period of inactivity. Please return home or sign in again.
        </p>
        <div class="d-grid gap-2">
            <a href="{{ auth()->check() ? url('/') : route('login') }}" class="btn-premium text-center text-decoration-none">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In Again
            </a>
        </div>
    </div>
</x-guest-layout>
