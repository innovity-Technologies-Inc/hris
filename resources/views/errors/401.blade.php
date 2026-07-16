<x-guest-layout title="401 - Unauthorized" subtitle="Authentication is required to access this page.">
    <div class="text-center py-4">
        <div class="mb-4">
            <span class="fa-stack fa-3x" style="color: var(--primary-color);">
                <i class="fas fa-circle fa-stack-2x" style="opacity: 0.1;"></i>
                <i class="fas fa-user-lock fa-stack-1x"></i>
            </span>
        </div>
        <p class="text-muted mb-4 fs-15 fw-semibold">
            Please log in with your credentials to access this section of the portal.
        </p>
        <div class="d-grid gap-2">
            <a href="{{ route('login') }}" class="btn-premium text-center text-decoration-none">
                <i class="fas fa-sign-in-alt me-2"></i>Log In
            </a>
        </div>
    </div>
</x-guest-layout>
