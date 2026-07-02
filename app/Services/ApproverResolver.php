<?php

namespace App\Services;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Innovity\ApprovalEngine\Contracts\ApproverResolverInterface;

class ApproverResolver implements ApproverResolverInterface
{
    /**
     * Resolve the user IDs for a given required user type in the approval workflow.
     *
     * @param string $requiredUserType The string from the workflow step (e.g., 'department', 'company')
     * @param Model $approvable The model being approved
     * @return array Array of User IDs who are allowed to approve this step
     */
    public function resolve(string $requiredUserType, Model $approvable): array
    {
        // 1. Identify if a WorkflowStep ID was passed instead of a raw type string
        $step = null;
        if (is_numeric($requiredUserType)) {
            $step = \Innovity\ApprovalEngine\Models\WorkflowStep::find((int) $requiredUserType);
        }

        // 2. Determine step type
        $type = $step ? ($step->type ?? 'user-type') : 'user-type';

        switch ($type) {
            case 'specific-user':
                return $step && $step->user_id ? [$step->user_id] : [];

            case 'role-user':
                if (!$step || !$step->role_id || !$step->required_user_type) {
                    return [];
                }
                
                // Get users of a specific user type (e.g. Department Head)
                $typeUserIds = $this->resolveUserType($step->required_user_type, $approvable);
                
                if (empty($typeUserIds)) {
                    return [];
                }

                // Filter users to only those who also possess the Spatie Role
                return User::whereIn('id', $typeUserIds)
                    ->whereHas('roles', function($q) use ($step) {
                        $q->where('id', $step->role_id);
                    })
                    ->pluck('id')
                    ->toArray();

            case 'user-type':
            default:
                $userTypeString = $step ? $step->required_user_type : $requiredUserType;
                return $this->resolveUserType($userTypeString, $approvable);
        }
    }

    /**
     * Resolve users by Organizational Hierarchy (Original Code logic)
     */
    protected function resolveUserType(?string $requiredUserType, Model $approvable): array
    {
        if (!$requiredUserType) {
            return [];
        }

        // Get the requesting user from the approvable model
        $requestingUser = null;
        if (method_exists($approvable, 'user')) {
            $requestingUser = $approvable->user;
        } elseif (method_exists($approvable, 'getEmployee')) {
            $emp = $approvable->getEmployee()->withoutGlobalScopes()->first();
            $requestingUser = $emp ? $emp->user : null;
        } elseif (method_exists($approvable, 'employee')) {
            $emp = $approvable->employee()->withoutGlobalScopes()->first();
            $requestingUser = $emp ? $emp->user : null;
        } elseif (method_exists($approvable, 'creator')) {
            $requestingUser = $approvable->creator;
        }
        
        // Ensure we have the user and their organizational office info
        if (!$requestingUser) {
            return [];
        }

        $employee = $requestingUser->employee()->withoutGlobalScopes()->with(['officeInfo' => function($q) {
            $q->withoutGlobalScopes();
        }])->first();

        if (!$employee || !$employee->officeInfo) {
            return [];
        }

        $officeInfo = $employee->officeInfo;

        // Convert the required string from the workflow step into our UserType enum
        $enumValue = UserType::tryFrom($requiredUserType);

        if ($enumValue) {
            $query = User::where('user_type', $enumValue->value);

            // For 'Group', they see everything, so we don't need to filter by officeInfo.
            if ($enumValue !== UserType::Group) {
                // Apply organization scope filtering to find the correct UserType
                // that belongs to the same organizational level as the requesting user.
                $query->whereHas('employee', function ($q) use ($enumValue, $officeInfo) {
                    $q->withoutGlobalScopes()->whereHas('officeInfo', function ($q2) use ($enumValue, $officeInfo) {
                        $q2->withoutGlobalScopes();
                        match ($enumValue) {
                            UserType::Company => $q2->where('current_company_id', $officeInfo->current_company_id),
                            UserType::Division => $q2->where('current_division_id', $officeInfo->current_division_id),
                            UserType::Department => $q2->where('current_department_id', $officeInfo->current_department_id),
                            UserType::Section => $q2->where('current_section_id', $officeInfo->current_section_id),
                            UserType::BusinessUnit => $q2->where('current_business_unit_id', $officeInfo->current_business_unit_id),
                            default => $q2,
                        };
                    });
                });
            }

            return $query->pluck('id')->toArray();
        }

        // Optional Fallbacks just in case you manually define custom steps outside of UserType
        if ($requiredUserType === 'manager' && $requestingUser->manager_id) {
            return [$requestingUser->manager_id];
        }

        if ($requiredUserType === 'hr_admin') {
            return User::role('hr_admin')->pluck('id')->toArray();
        }

        return [];
    }
}
