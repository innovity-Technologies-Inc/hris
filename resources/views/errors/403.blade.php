<x-guest-layout title="403 - Forbidden" subtitle="Access to this resource is restricted.">
    <div class="text-center py-4">
        <div class="mb-4">
            <span class="fa-stack fa-3x" style="color: var(--primary-color);">
                <i class="fas fa-circle fa-stack-2x" style="opacity: 0.1;"></i>
                <i class="fas fa-ban fa-stack-1x"></i>
            </span>
        </div>
        <p class="text-muted mb-4 fs-15 fw-semibold">
            You do not have the required permissions to access this page. If you believe this is an error, please contact your system administrator.
        </p>
        <div class="d-grid gap-2">
            <a href="{{ auth()->check() ? url('/') : route('login') }}" class="btn-premium text-center text-decoration-none">
                <i class="fas fa-home me-2"></i>Return Home
            </a>
        </div>
    </div>
</x-guest-layout>
