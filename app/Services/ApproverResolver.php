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
        // Get the requesting user from the approvable model
        $requestingUser = null;
        if (method_exists($approvable, 'user')) {
            $requestingUser = $approvable->user;
        } elseif (method_exists($approvable, 'getEmployee') && $approvable->getEmployee) {
            $requestingUser = $approvable->getEmployee->user ?? null;
        } elseif (method_exists($approvable, 'creator')) {
            $requestingUser = $approvable->creator;
        }
        
        // Ensure we have the user and their organizational office info
        if (!$requestingUser || !$requestingUser->employee || !$requestingUser->employee->officeInfo) {
            return [];
        }

        $officeInfo = $requestingUser->employee->officeInfo;

        // Convert the required string from the workflow step into our UserType enum
        $enumValue = UserType::tryFrom($requiredUserType);

        if ($enumValue) {
            $query = User::where('user_type', $enumValue->value);

            // For 'Group', they see everything, so we don't need to filter by officeInfo.
            if ($enumValue !== UserType::Group) {
                // Apply organization scope filtering to find the correct UserType
                // that belongs to the same organizational level as the requesting user.
                $query->whereHas('employee.officeInfo', function ($q) use ($enumValue, $officeInfo) {
                    match ($enumValue) {
                        UserType::Company => $q->where('current_company_id', $officeInfo->current_company_id),
                        UserType::Division => $q->where('current_division_id', $officeInfo->current_division_id),
                        UserType::Department => $q->where('current_department_id', $officeInfo->current_department_id),
                        UserType::Section => $q->where('current_section_id', $officeInfo->current_section_id),
                        UserType::BusinessUnit => $q->where('current_business_unit_id', $officeInfo->current_business_unit_id),
                        default => $q,
                    };
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
