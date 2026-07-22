<?php

namespace App\Services\Payroll;

use App\Models\Payroll\EmployeePenalty;
use App\Models\Employee\Employee;
use App\Models\User;
use App\Enums\UserType;
use App\Services\Setting\NotificationServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeePenaltyServices
{
    protected $notificationService;

    public function __construct(NotificationServices $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getPenalties(Request $request, FlexSearch $flexsearch, $paginate = true)
    {
        $query = EmployeePenalty::query()->with(['employee', 'penaltyPlan']);
        $keyword = $request->get('keyword');
        $searchableFields = ['cause', 'employee.full_name', 'employee.applicant_id', 'employee.system_id', 'penaltyPlan.title'];

        $applied = $flexsearch->apply($query, [], $keyword, $searchableFields)
            ->orderBy('id', 'desc');

        if ($paginate) {
            return $applied->paginate($request->get('per_page', 10));
        }

        return $applied->get();
    }

    /**
     * Get a single penalty by ID.
     */
    public function getPenaltyById($id)
    {
        return EmployeePenalty::with(['employee', 'penaltyPlan'])->findOrFail($id);
    }

    /**
     * Create or Update a penalty.
     */
    public function savePenalty(array $data, $id = null)
    {
        try {
            if ($id) {
                $penalty = EmployeePenalty::findOrFail($id);
                $penalty->update($data);
                $action = 'Updated';
            } else {
                $penalty = EmployeePenalty::create($data);
                $penalty->startWorkflow('penalty');
                $action = 'Assigned';
            }

            // Notify Employee
            $this->notifyEmployee($penalty, $action);

            return $penalty;
        } catch (\Exception $e) {
            Log::error('Error saving employee penalty: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a penalty.
     */
    public function deletePenalty($id)
    {
        try {
            $penalty = EmployeePenalty::findOrFail($id);
            return $penalty->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting employee penalty: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification to employee.
     */
    private function notifyEmployee(EmployeePenalty $penalty, string $action)
    {
        try {
            $employee = Employee::find($penalty->employee_id);
            if ($employee && $employee->user_id) {
                $user = User::find($employee->user_id);
                if ($user) {
                    $title = "Penalty $action";
                    $message = "A penalty (" . ($penalty->penaltyPlan->title ?? 'Custom') . ") has been $action on your profile for the occurrence date: " . $penalty->occurrence_date . ".";
                    
                    $this->notificationService->createNotification(
                        $user->user_type->value,
                        $user->id,
                        $title,
                        $message,
                        ['penalty_id' => $penalty->id]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify employee about penalty: ' . $e->getMessage());
        }
    }
}
