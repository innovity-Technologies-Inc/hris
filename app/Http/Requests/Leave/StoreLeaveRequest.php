<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserType;
use App\Models\Plan\LeavePlan;
use App\Models\Leave\Leave;
use App\Models\Employee\EmployeeCompOff;

class StoreLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($user->user_type === UserType::Employee) {
            return (int) $this->input('employee_id') === (int) $user->employee_id;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $isEmployee = $this->user()?->user_type === UserType::Employee;

        return [
            'employee_id' => 'required|exists:employees,id',
            'leave_category_type' => 'required|in:standard,compensatory',
            'plan_id' => 'required_if:leave_category_type,standard|nullable|exists:leave_plans,id',
            'from' => 'required|date',
            'to' => 'required|date',
            'status' => $isEmployee ? 'nullable' : 'required',
            'reason' => 'required|string',
            'leave_count' => 'required|numeric|min:0.5',
            'day_type' => 'nullable|in:full_day,half_day',
        ];
    }

    /**
     * Custom validation logic for leave limits and comp-off balance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $employeeId = $this->input('employee_id');
            $categoryType = $this->input('leave_category_type', 'standard');
            $requestedCount = (float) $this->input('leave_count');

            if ($categoryType === 'compensatory') {
                $compOff = EmployeeCompOff::where('employee_id', $employeeId)->first();
                $available = $compOff ? (float) $compOff->balance_days : 0;

                if ($requestedCount > $available) {
                    $validator->errors()->add(
                        'leave_count',
                        "You do not have enough Compensatory Leave balance. Available: {$available} days, Requested: {$requestedCount} days."
                    );
                }
                return;
            }

            // Standard leave plan checks
            $planId = $this->input('plan_id');
            $plan = LeavePlan::find($planId);
            if (!$plan) {
                return;
            }

            $maxDays = $plan->max_no_of_days;
            if ($maxDays < $requestedCount) {
                $validator->errors()->add('leave_count', 'You cannot request more than ' . $maxDays . ' days per leave');
            }

            $currentYear = now()->year;
            $takenThisYear = Leave::where('employee_id', $employeeId)
                ->where('plan_id', $planId)
                ->where('status', 'approved')
                ->whereYear('from', $currentYear)
                ->sum('leave_count');

            $remainingLeaves = $plan->leave_limit - $takenThisYear;

            if ($requestedCount > $remainingLeaves) {
                $validator->errors()->add(
                    'leave_count',
                    "You only have {$remainingLeaves} leave(s) remaining for {$plan->name} plan."
                );
            }
        });
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'plan_id.required_if' => 'Please select a leave plan.',
            'from.required' => 'The start date is required.',
            'to.required' => 'The end date is required.',
            'reason.required' => 'Please enter a reason for your leave application.',
            'leave_count.required' => 'Please enter the number of leave days.',
        ];
    }
}
