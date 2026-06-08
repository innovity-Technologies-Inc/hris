<?php

namespace App\Services\Setting;

use App\Enums\UserType;
use App\Models\Setting\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationServices
{
    /**
     * Create a notification.
     */
    public function createNotification(string $userType, ?int $userId, string $title, string $message, array $data = []): Notification
    {
        try {
            $notification = Notification::create([
                'user_type' => $userType,
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);
            \Illuminate\Support\Facades\Log::info('Notification created successfully.', ['id' => $notification->id, 'user_id' => $userId]);
            return $notification;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FAILED to create notification: ' . $e->getMessage(), [
                'user_type' => $userType,
                'user_id' => $userId,
                'title' => $title
            ]);
            throw $e;
        }
    }

    /**
     * Get notifications visible to the current user.
     */
    public function getVisibleNotifications($user)
    {
        $userType = $user->user_type;
        $employee = $user->employee()->with('officeInfo')->first();

        return Notification::where(function ($query) use ($user, $userType, $employee) {
            // 1. Direct targeted notifications (Always visible to the target)
            $query->where(function($q) use ($user, $userType) {
                $q->where('user_type', $userType)
                  ->where('user_id', $user->id);
            });

            // 2. HR type (Group users show notifications with user_type = 'hr')
            if ($userType === 'Group') {
                $query->orWhere('user_type', 'hr');
            }

            // 3. Supervisor logic (Hierarchical)
            $query->orWhere(function ($q) use ($user, $userType, $employee) {
                $q->where('user_type', 'supervisor');
                
                // We need to check if the current user is the "supervisor" for any notification's target user_id
                // This is complex for a single SQL query. 
                // Instead, we might want to filter these in PHP or use a more optimized approach.
                // For now, let's implement the logic to match the current user's level and scope.
                
                if (!$employee || !$employee->officeInfo) return;
                $office = $employee->officeInfo;

                switch ($userType) {
                    case UserType::Section:
                        // Visible if notification target is an Employee in this section
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_section_id', $office->current_section_id)
                                ->where('u.user_type', UserType::Employee->value);
                        });
                        break;
                    case UserType::Department:
                        // Visible if notification target is a Section level user in this department
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_department_id', $office->current_department_id)
                                ->where('u.user_type', UserType::Section->value);
                        });
                        break;
                    case UserType::Division:
                        // Visible if notification target is a Department level user in this division
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_division_id', $office->current_division_id)
                                ->where('u.user_type', UserType::Department->value);
                        });
                        break;
                    case UserType::BusinessUnit:
                        // Visible if notification target is a Division level user in this BU
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_business_unit_id', $office->current_business_unit_id)
                                ->where('u.user_type', UserType::Division->value);
                        });
                        break;
                    case UserType::Company:
                        // Visible if notification target is a BU level user in this company
                        $q->whereIn('user_id', function($sub) use ($office) {
                            $sub->select('u.id')
                                ->from('users as u')
                                ->join('employee_office_infos as eoi', 'u.employee_id', '=', 'eoi.employee_id')
                                ->where('eoi.current_company_id', $office->current_company_id)
                                ->where('u.user_type', UserType::BusinessUnit->value);
                        });
                        break;
                    case UserType::Group:
                        // Group sees everything for Company level targets
                        $q->whereIn('user_id', function($sub) {
                            $sub->select('id')
                                ->from('users')
                                ->where('user_type', UserType::Company->value);
                        });
                        break;
                }
            });
        })
        ->orderBy('created_at', 'desc');
    }
}

