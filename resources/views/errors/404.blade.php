<x-guest-layout title="404 - Not Found" subtitle="The page you are looking for does not exist.">
    <div class="text-center py-4">
        <div class="mb-4">
            <span class="fa-stack fa-3x" style="color: var(--primary-color);">
                <i class="fas fa-circle fa-stack-2x" style="opacity: 0.1;"></i>
                <i class="fas fa-exclamation-triangle fa-stack-1x"></i>
            </span>
        </div>
        <p class="text-muted mb-4 fs-15 fw-semibold">
            It looks like you've taken a wrong turn. The URL might be misspelled, or the page has been moved or deleted.
        </p>
        <div class="d-grid gap-2">
            <a href="{{ auth()->check() ? url('/') : route('login') }}" class="btn-premium text-center text-decoration-none">
                <i class="fas fa-home me-2"></i>Return Home
            </a>
        </div>
    </div>
</x-guest-layout>
