<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Employee\Employee;

class EnsureNotOffboarded
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->employee_id) {
                $employee = Employee::withoutGlobalScopes()->find($user->employee_id);

                if ($employee && in_array($employee->status, ['resigned', 'terminated'])) {
                    // Allowed routes for offboarded employees
                    $allowedRoutes = ['offboarding.my_offboarding', 'logout'];

                    if (!in_array($request->route()?->getName(), $allowedRoutes)) {
                        if ($request->expectsJson() || $request->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Your employee account has been offboarded. Access is restricted to offboarding details.'
                            ], 403);
                        }

                        return redirect()->route('offboarding.my_offboarding');
                    }
                }
            }
        }

        return $next($request);
    }
}
