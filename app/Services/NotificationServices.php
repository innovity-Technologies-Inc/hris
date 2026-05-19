<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationServices
{
    /**
     * Create a notification.
     */
    public function createNotification(string $userType, ?int $userId, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Get notifications visible to the current user.
     */
    public function getVisibleNotifications($user)
    {
        $userType = $user->user_type;
        $employeeId = $user->employee_id;
        $employee = $user->employee()->with('officeInfo')->first();

        return Notification::where(function ($query) use ($user, $userType, $employeeId, $employee) {
            // 1. Employee type sees their own notifications
            if ($userType === 'Employee') {
                $query->where('user_type', 'Employee')
                      ->where('user_id', $user->id);
            }

            // 2. HR type (Group users show notifications with user_type = 'hr')
            if ($userType === 'Group') {
                $query->orWhere('user_type', 'hr');
            }

            // 3. Supervisor logic (Hierarchical)
            // If a notification is for 'supervisor', it's visible to the level above the targeted user_id.
            $query->orWhere(function ($q) use ($user, $userType, $employee) {
                $q->where('user_type', 'supervisor');
                
                // We need to check if the current user is the "supervisor" for any notification's target user_id
                // This is complex for a single SQL query. 
                // Instead, we might want to filter these in PHP or use a more optimized approach.
                // For now, let's implement the logic to match the current user's level and scope.
                
                if (!$employee || !$employee->officeInfo) return;
                $office = $employee->officeInfo;

                switch ($userType) {
                    case 'Section':
                        // Visible if notification target is an Employee in this section
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_section_id', $office->current_section_id)
                                ->where('u.user_type', 'Employee');
                        });
                        break;
                    case 'Department':
                        // Visible if notification target is a Section level user in this department
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_department_id', $office->current_department_id)
                                ->where('u.user_type', 'Section');
                        });
                        break;
                    case 'Division':
                        // Visible if notification target is a Department level user in this division
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_division_id', $office->current_division_id)
                                ->where('u.user_type', 'Department');
                        });
                        break;
                    case 'Business Unit':
                        // Visible if notification target is a Division level user in this BU
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_business_unit_id', $office->current_business_unit_id)
                                ->where('u.user_type', 'Division');
                        });
                        break;
                    case 'Company':
                        // Visible if notification target is a BU level user in this company
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_company_id', $office->current_company_id)
                                ->where('u.user_type', 'Business Unit');
                        });
                        break;
                    case 'Group':
                        // Group sees everything for Company level targets
                        $q->whereIn('user_id', function($sub) {
                            $sub->select('id')
                                ->from('users')
                                ->where('user_type', 'Company');
                        });
                        break;
                }
            });
        })
        ->orderBy('created_at', 'desc');
    }
}
