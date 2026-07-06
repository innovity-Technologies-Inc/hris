<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeEligiblePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->user_type !== \App\Enums\UserType::Employee;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'employee_id' => 'required',

            // Shift Plan
            'shift_plan_from' => 'nullable|date',
            'shift_plan_to' => 'nullable|date|after_or_equal:shift_plan_from',
            'shift_plan_status' => 'nullable|in:active,inactive',

            // Leave Plan
            'leave_plan_from' => 'nullable|date',
            'leave_plan_to' => 'nullable|date|after_or_equal:leave_plan_from',
            'leave_plan_status' => 'nullable|in:active,inactive',

            // OT Plan
            'ot_plan_from' => 'nullable|date',
            'ot_plan_to' => 'nullable|date|after_or_equal:ot_plan_from',
            'ot_plan_status' => 'nullable|in:active,inactive',

            // Day Off Work Plan
            'day_off_work_plan_from' => 'nullable|date',
            'day_off_work_plan_to' => 'nullable|date|after_or_equal:day_off_work_plan_from',
            'day_off_work_plan_status' => 'nullable|in:active,inactive',

            // Roster Plans
            'roster_plans_from' => 'nullable|date',
            'roster_plans_to' => 'nullable|date|after_or_equal:roster_plans_from',
            'roster_plans_status' => 'nullable|in:active,inactive',

            // Bonus Plan
            'bonus_plan_from' => 'nullable|date',
            'bonus_plan_to' => 'nullable|date|after_or_equal:bonus_plan_from',
            'bonus_plan_status' => 'nullable|in:active,inactive',

            // Allowance Plan
            'allowance_plan_from' => 'nullable|date',
            'allowance_plan_to' => 'nullable|date|after_or_equal:allowance_plan_from',
            'allowance_plan_status' => 'nullable|in:active,inactive',

            // Late Deduction Plan
            'late_deduction_plan_from' => 'nullable|date',
            'late_deduction_plan_to' => 'nullable|date|after_or_equal:late_deduction_plan_from',
            'late_deduction_plan_status' => 'nullable|in:active,inactive',

            // Early Out Deduction Plan
            'early_out_deduction_plan_from' => 'nullable|date',
            'early_out_deduction_plan_to' => 'nullable|date|after_or_equal:early_out_deduction_plan_from',
            'early_out_deduction_plan_status' => 'nullable|in:active,inactive',

            // Medical Plan
            'medical_plan_from' => 'nullable|date',
            'medical_plan_to' => 'nullable|date|after_or_equal:medical_plan_from',
            'medical_plan_status' => 'nullable|in:active,inactive',

            // Excessive Late Plan
            'excessive_late_plan_from' => 'nullable|date',
            'excessive_late_plan_to' => 'nullable|date|after_or_equal:excessive_late_plan_from',
            'excessive_late_plan_status' => 'nullable|in:active,inactive',

            // Meal Plan
            'meal_plan_from' => 'nullable|date',
            'meal_plan_to' => 'nullable|date|after_or_equal:meal_plan_from',
            'meal_plan_status' => 'nullable|in:active,inactive',
        ];

        return app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'employee-policy');
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'The employee field is required.',
            'employee_id.exists' => 'The selected employee is invalid.',
            'employee_id.unique' => 'The employee has already been assigned a plan.',
            'shift_plan_from.date' => 'The shift plan from date is invalid.',
            'shift_plan_to.date' => 'The shift plan to date is invalid.',
            'shift_plan_to.after_or_equal' => 'The shift plan to date must be after or equal to the from date.',
            'shift_plan_status.in' => 'The shift plan status must be either active or inactive.',
            'leave_plan_from.date' => 'The leave plan from date is invalid.',
            'leave_plan_to.date' => 'The leave plan to date is invalid.',
            'leave_plan_to.after_or_equal' => 'The leave plan to date must be after or equal to the from date.',
            'leave_plan_status.in' => 'The leave plan status must be either active or inactive.',
            'ot_plan_from.date' => 'The OT plan from date is invalid.',
            'ot_plan_to.date' => 'The OT plan to date is invalid.',
            'ot_plan_to.after_or_equal' => 'The OT plan to date must be after or equal to the from date.',
            'ot_plan_status.in' => 'The OT plan status must be either active or inactive.',
            'day_off_work_plan_from.date' => 'The day off work plan from date is invalid.',
            'day_off_work_plan_to.date' => 'The day off work plan to date is invalid.',
            'day_off_work_plan_to.after_or_equal' => 'The day off work plan to date must be after or equal to the from date.',
            'day_off_work_plan_status.in' => 'The day off work plan status must be either active or inactive.',
            'roster_plans_from.date' => 'The roster plans from date is invalid.',
            'roster_plans_to.date' => 'The roster plans to date is invalid.',
            'roster_plans_to.after_or_equal' => 'The roster plans to date must be after or equal to the from date.',
            'roster_plans_status.in' => 'The roster plans status must be either active or inactive.',
            'bonus_plan_from.date' => 'The bonus plan from date is invalid.',
            'bonus_plan_to.date' => 'The bonus plan to date is invalid.',
            'bonus_plan_to.after_or_equal' => 'The bonus plan to date must be after or equal to the from date.',
            'bonus_plan_status.in' => 'The bonus plan status must be either active or inactive.',
            'allowance_plan_from.date' => 'The allowance plan from date is invalid.',
            'allowance_plan_to.date' => 'The allowance plan to date is invalid.',
            'allowance_plan_to.after_or_equal' => 'The allowance plan to date must be after or equal to the from date.',
            'allowance_plan_status.in' => 'The allowance plan status must be either active or inactive.',
            'late_deduction_plan_from.date' => 'The late deduction plan from date is invalid.',
            'late_deduction_plan_to.date' => 'The late deduction plan to date is invalid.',
            'late_deduction_plan_to.after_or_equal' => 'The late deduction plan to date must be after or equal to the from date.',
            'late_deduction_plan_status.in' => 'The late deduction plan status must be either active or inactive.',
            'early_out_deduction_plan_from.date' => 'The early out deduction plan from date is invalid.',
            'early_out_deduction_plan_to.date' => 'The early out deduction plan to date is invalid.',
            'early_out_deduction_plan_to.after_or_equal' => 'The early out deduction plan to date must be after or equal to the from date.',
            'early_out_deduction_plan_status.in' => 'The early out deduction plan status must be either active or inactive.',
            'meal_plan_from.date' => 'The meal plan from date is invalid.',
            'meal_plan_to.date' => 'The meal plan to date is invalid.',
            'meal_plan_to.after_or_equal' => 'The meal plan to date must be after or equal to the from date.',
            'meal_plan_status.in' => 'The meal plan status must be either active or inactive.',
        ];
    }
}
