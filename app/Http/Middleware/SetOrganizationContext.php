<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetOrganizationContext
{
    /**
     * Handle an incoming request.
     * Stores the authenticated user's organization_id in the app container
     * so it is available globally without re-querying the user model.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $orgId = Auth::user()->organization_id;
            // Make it available app-wide via app('current.organization_id')
            app()->instance('current.organization_id', $orgId);
        }

        return $next($request);
    }
}
