<x-guest-layout title="500 - Server Error" subtitle="Something went wrong on our end.">
    <div class="text-center py-4">
        <div class="mb-4">
            <span class="fa-stack fa-3x" style="color: var(--primary-color);">
                <i class="fas fa-circle fa-stack-2x" style="opacity: 0.1;"></i>
                <i class="fas fa-server fa-stack-1x"></i>
            </span>
        </div>
        <p class="text-muted mb-4 fs-15 fw-semibold">
            The server encountered an internal error and was unable to complete your request. Our technical team has been notified.
        </p>
        <div class="d-grid gap-2">
            <a href="{{ auth()->check() ? url('/') : route('login') }}" class="btn-premium text-center text-decoration-none">
                <i class="fas fa-home me-2"></i>Return Home
            </a>
        </div>
    </div>
</x-guest-layout>
