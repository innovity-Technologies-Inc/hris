<?php

namespace App\Services;

use App\Models\MealPlan;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class PlanService
{
    public function search($modelName, $searchableColumns, $relationalModels = null, $filters = null, $searchTerm = null, $paginate = 10)
    {
        $flexSearch = new FlexSearch();
        if ($relationalModels) {
            $query = $modelName::with($relationalModels);
        }else{
            $query = $modelName::query();
        }
        $result = $flexSearch->apply($query, $filters ?? [], $searchTerm, $searchableColumns)->paginate($paginate);
        return $result;
    }

    public function planSave($validated, $modelName, $id = null)
    {
        if (isset($id)) {
            $plan = $modelName::findOrFail($id);
            $plan->update($validated);
        } else {
            $plan = $modelName::create($validated);
        }
        return $plan;
    }

    public function planDelete($modelName, $id)
    {
        $plan = $modelName::findOrFail($id);
        $plan->delete();
    }

    public function getPlans($modelName, $paginate)
    {
        $plans = $modelName::latest()->paginate($paginate);
        return $plans;
    }

    public function getPlanByID($id, $modelName)
    {
        $plan = $modelName::findOrFail($id);
        return $plan;
    }

    public function getMealPlans($type)
    {
        $mealPlans = MealPlan::where('type', $type)->get();
        return $mealPlans;
    }

    public function mealPlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:breakfast,lunch,snacks,dinner',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'The menu name is required.',
            'name.string' => 'The menu name must be a string.',
            'name.max' => 'The menu name may not exceed 255 characters.',

            'type.required' => 'Please select a menu type.',
            'type.in' => 'The selected menu type is invalid.',

            'description.required' => 'Please provide a description for the menu.',
            'description.string' => 'Description must be a string.',

            'cost.required' => 'The cost field is required.',
            'cost.numeric' => 'The cost must be a number.',
            'cost.min' => 'The cost must be at least 0.',

            'start_time.required' => 'Please enter the start time.',
            'start_time.date_format' => 'Start time must be in the format HH:MM.',

            'end_time.required' => 'Please enter the end time.',
            'end_time.date_format' => 'End time must be in the format HH:MM.',
            'end_time.after' => 'End time must be after the start time.',

            'status.required' => 'Please select the menu status.',
            'status.in' => 'The selected status is invalid.',
        ]);
        return $validated;
    }

    public function shiftPlanValidation($request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'required|date_format:H:i',
            'treat_as_full_day_minutes' => 'nullable|integer|min:0',
            'treat_as_half_day_minutes' => 'nullable|integer|min:0',
            'grace_time' => 'nullable|integer|min:0',
            'late_after_minutes' => 'nullable|integer|min:0',
            'excessive_late_after_minutes' => 'nullable|integer|min:0',
            'early_out_grace_minutes' => 'nullable|integer|min:0',
            'early_out_before' => 'nullable|date_format:H:i',

            // Meal fields
            'breakfast_status' => 'required|in:active,inactive',
            'breakfast_start_time' => 'nullable|date_format:H:i|required_if:breakfast_status,active',
            'breakfast_end_time' => 'nullable|date_format:H:i|required_if:breakfast_status,active',

            'lunch_status' => 'required|in:active,inactive',
            'lunch_start_time' => 'nullable|date_format:H:i|required_if:lunch_status,active',
            'lunch_end_time' => 'nullable|date_format:H:i|required_if:lunch_status,active',

            'snacks_status' => 'required|in:active,inactive',
            'snacks_start_time' => 'nullable|date_format:H:i|required_if:snacks_status,active',
            'snacks_end_time' => 'nullable|date_format:H:i|required_if:snacks_status,active',

            'dinner_status' => 'required|in:active,inactive',
            'dinner_start_time' => 'nullable|date_format:H:i|required_if:dinner_status,active',
            'dinner_end_time' => 'nullable|date_format:H:i|required_if:dinner_status,active',

            'active_ind' => 'required|in:active,inactive',
        ], [
            // Custom error messages
            'shift_name.required' => 'Shift name is required.',
            'clock_in_time.required' => 'Clock in time is required.',
            'clock_out_time.required' => 'Clock out time is required.',
            'breakfast_end_time.after' => 'Breakfast end time must be after start time.',
            'lunch_end_time.after' => 'Lunch end time must be after start time.',
            'snacks_end_time.after' => 'Snacks end time must be after start time.',
            'dinner_end_time.after' => 'Dinner end time must be after start time.',
        ]);
        return $validate;
    }

    public function otPlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

            // Configuration fields
            'ot_config_type' => 'required|in:Salary Based,Custom',
            'salary_rate_type' => 'required_if:ot_config_type,Salary Based|nullable|in:Basic Rate,Multiplier',
            'overtime_multiplier' => 'nullable|numeric|min:0',
            'custom_overtime_rate' => 'nullable|numeric|min:0',

            'maximum_overtime' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'OT plan name is required.',
            'name.string' => 'OT plan name must be a string.',
            'name.max' => 'OT plan name may not exceed 255 characters.',

            'ot_config_type.required' => 'Please select a configuration type.',
            'ot_config_type.in' => 'The selected configuration type is invalid.',

            'salary_rate_type.required_if' => 'Please select a rate type when using salary-based configuration.',
            'salary_rate_type.in' => 'The selected rate type is invalid.',

            'overtime_multiplier.numeric' => 'Overtime multiplier must be a number.',
            'overtime_multiplier.min' => 'Overtime multiplier must be at least 0.',

            'custom_overtime_rate.numeric' => 'Custom overtime rate must be a number.',
            'custom_overtime_rate.min' => 'Custom overtime rate must be at least 0.',

            'maximum_overtime.numeric' => 'Maximum overtime must be a number.',
            'maximum_overtime.min' => 'Maximum overtime must be at least 0.',

            'status.required' => 'Please select the plan status.',
            'status.in' => 'The selected status is invalid.',
        ]);

        return $validated;
    }

    public function leavePlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:100',
            'applicable_gender' => 'required|in:Both,Male,Female',
            'day_type' => 'required|in:Calculative,Fixed',
            'leave_type' => 'required',
            'leave_limit' => 'nullable|integer|min:0',
            'max_no_of_days' => 'nullable|integer|min:0',
            'display_serial' => 'nullable|integer|min:0',
            'apply_limit' => 'nullable|integer|min:0',
            'allow_fractional_leave' => 'required|in:active,inactive',
            'off_day_include' => 'nullable|integer|min:0',
            'active_ind' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Leave plan name is required.',
            'name.string' => 'Leave plan name must be a string.',
            'name.max' => 'Leave plan name may not exceed 255 characters.',

            'short_name.string' => 'Short name must be a string.',
            'short_name.max' => 'Short name may not exceed 100 characters.',

            'applicable_gender.required' => 'Please select applicable gender.',
            'applicable_gender.in' => 'The selected gender is invalid.',

            'day_type.required' => 'Please select day type.',
            'day_type.in' => 'The selected day type is invalid.',

            'leave_type.required' => 'Please select leave type.',
            'leave_type.in' => 'The selected leave type is invalid.',

            'leave_limit.integer' => 'Leave limit must be a number.',
            'leave_limit.min' => 'Leave limit must be at least 0.',

            'max_no_of_days.integer' => 'Maximum days must be a number.',
            'max_no_of_days.min' => 'Maximum days must be at least 0.',

            'display_serial.integer' => 'Display serial must be a number.',
            'display_serial.min' => 'Display serial must be at least 0.',

            'apply_limit.required' => 'Please select apply limit option.',
            'apply_limit.in' => 'The selected option is invalid.',

            'allow_fractional_leave.required' => 'Please select fractional leave option.',
            'allow_fractional_leave.in' => 'The selected option is invalid.',

            'off_day_include.required' => 'Please select off day include option.',
            'off_day_include.in' => 'The selected option is invalid.',

            'active_ind.required' => 'Please select the plan status.',
            'active_ind.in' => 'The selected status is invalid.',
        ]);

        return $validated;
    }

    public function rosterPlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:255',
            'swapping' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'first_shift_id' => 'required',
            'second_shift_id' => 'required',
            'third_shift_id' => 'nullable',
        ], [
            'name.required' => 'The name field is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.',

            'first_shift_id.required' => 'First shift is required.',
            'first_shift_id.exists' => 'The selected first shift does not exist.',

            'second_shift_id.required' => 'Second shift is required.',
            'second_shift_id.exists' => 'The selected second shift does not exist.',
        ]);
        return $validated;
    }

    public function offDayPlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:255',

            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',

            'grace_time' => 'required|integer|min:0',
            'grace_time_before' => 'nullable|integer|min:0',

            'remuneration' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|in:active,inactive'
        ], [
            'name.required' => 'Name is required.',
            'start_time.required' => 'Start time is required.',
            'end_time.after' => 'End time must be after start time.',

            'grace_time.required' => 'Grace time is required.',
            'grace_time.integer' => 'Grace time must be a number.',

            'remuneration.required' => 'Remuneration amount is required.',
            'remuneration.numeric' => 'Remuneration must be a valid number.',
        ]);
        return $validated;
    }

    public function bonusPlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bonus_type' => 'required|in:festival,performance,annual,incentive,retention,other',

            // Configuration fields
            'bonus_config_type' => 'required|in:Salary Based,Custom',
            'salary_rate_type' => 'required_if:bonus_config_type,Salary Based|nullable|in:Basic Rate,Multiplier',
            'overtime_multiplier' => 'nullable|numeric|min:0',
            'custom_overtime_rate' => 'nullable|numeric|min:0',

            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Bonus plan name is required.',
            'name.string' => 'Bonus plan name must be a string.',
            'name.max' => 'Bonus plan name may not exceed 255 characters.',

            'bonus_type.required' => 'Please select a bonus type.',
            'bonus_type.in' => 'The selected bonus type is invalid.',

            'bonus_config_type.required' => 'Please select a configuration type.',
            'bonus_config_type.in' => 'The selected configuration type is invalid.',

            'salary_rate_type.required_if' => 'Please select a rate type when using salary-based configuration.',
            'salary_rate_type.in' => 'The selected rate type is invalid.',

            'overtime_multiplier.numeric' => 'Overtime multiplier must be a number.',
            'overtime_multiplier.min' => 'Overtime multiplier must be at least 0.',

            'custom_overtime_rate.numeric' => 'Custom overtime rate must be a number.',
            'custom_overtime_rate.min' => 'Custom overtime rate must be at least 0.',

            'status.required' => 'Please select the plan status.',
            'status.in' => 'The selected status is invalid.',
        ]);

        return $validated;
    }

    public function allowancePlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Allowance plan name is required.',
            'name.string' => 'Allowance plan name must be a string.',
            'name.max' => 'Allowance plan name may not exceed 255 characters.',

            'short_name.string' => 'Short name must be a string.',
            'short_name.max' => 'Short name may not exceed 100 characters.',

            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be at least 0.',

            'description.string' => 'Description must be a string.',

            'status.required' => 'Please select the plan status.',
            'status.in' => 'The selected status is invalid.',
        ]);

        return $validated;
    }

    public function taPlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:100',
            'remuneration' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'TA plan name is required.',
            'name.string' => 'TA plan name must be a string.',
            'name.max' => 'TA plan name may not exceed 255 characters.',

            'short_name.string' => 'Short name must be a string.',
            'short_name.max' => 'Short name may not exceed 100 characters.',

            'remuneration.required' => 'Remuneration per KM is required.',
            'remuneration.numeric' => 'Remuneration must be a valid number.',
            'remuneration.min' => 'Remuneration must be at least 0.',

            'status.required' => 'Please select the plan status.',
            'status.in' => 'The selected status is invalid.',
        ]);

        return $validated;
    }

    public function daPlanValidation($request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:100',
            'remuneration' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'DA plan name is required.',
            'name.string' => 'DA plan name must be a string.',
            'name.max' => 'DA plan name may not exceed 255 characters.',

            'short_name.string' => 'Short name must be a string.',
            'short_name.max' => 'Short name may not exceed 100 characters.',

            'remuneration.required' => 'Remuneration per meal is required.',
            'remuneration.numeric' => 'Remuneration must be a valid number.',
            'remuneration.min' => 'Remuneration must be at least 0.',

            'status.required' => 'Please select the plan status.',
            'status.in' => 'The selected status is invalid.',
        ]);

        return $validated;
    }

    public function deductionPlanValidation($request)
    {
        $validated = $request->validate([
            'late_deduction_days' => 'required|integer|min:0',
            'late_salary_deduction_rate' => 'required|numeric|min:0|max:100',
            'early_out_deduction_days' => 'required|integer|min:0',
            'early_out_salary_deduction_rate' => 'required|numeric|min:0|max:100',
            'excessive_late_deduction_days' => 'required|integer|min:0',
            'excessive_late_salary_deduction_rate' => 'required|numeric|min:0|max:100',
            'absent_deduction_days' => 'required|integer|min:0',
            'absent_salary_deduction_rate' => 'required|numeric|min:0|max:100',
            'calculation_type' => 'required|in:gross_salary,basic_salary',
        ], [
            'late_deduction_days.required' => 'Late deduction days is required.',
            'late_deduction_days.integer' => 'Late deduction days must be a valid number.',
            'late_deduction_days.min' => 'Late deduction days must be at least 0.',

            'late_salary_deduction_rate.required' => 'Late salary deduction rate is required.',
            'late_salary_deduction_rate.numeric' => 'Late salary deduction rate must be a valid number.',
            'late_salary_deduction_rate.min' => 'Late salary deduction rate must be at least 0.',
            'late_salary_deduction_rate.max' => 'Late salary deduction rate cannot exceed 100.',

            'early_out_deduction_days.required' => 'Early out deduction days is required.',
            'early_out_deduction_days.integer' => 'Early out deduction days must be a valid number.',
            'early_out_deduction_days.min' => 'Early out deduction days must be at least 0.',

            'early_out_salary_deduction_rate.required' => 'Early out salary deduction rate is required.',
            'early_out_salary_deduction_rate.numeric' => 'Early out salary deduction rate must be a valid number.',
            'early_out_salary_deduction_rate.min' => 'Early out salary deduction rate must be at least 0.',
            'early_out_salary_deduction_rate.max' => 'Early out salary deduction rate cannot exceed 100.',

            'excessive_late_deduction_days.required' => 'Excessive late deduction days is required.',
            'excessive_late_deduction_days.integer' => 'Excessive late deduction days must be a valid number.',
            'excessive_late_deduction_days.min' => 'Excessive late deduction days must be at least 0.',

            'excessive_late_salary_deduction_rate.required' => 'Excessive late salary deduction rate is required.',
            'excessive_late_salary_deduction_rate.numeric' => 'Excessive late salary deduction rate must be a valid number.',
            'excessive_late_salary_deduction_rate.min' => 'Excessive late salary deduction rate must be at least 0.',
            'excessive_late_salary_deduction_rate.max' => 'Excessive late salary deduction rate cannot exceed 100.',

            'absent_deduction_days.required' => 'Absent deduction days is required.',
            'absent_deduction_days.integer' => 'Absent deduction days must be a valid number.',
            'absent_deduction_days.min' => 'Absent deduction days must be at least 0.',

            'absent_salary_deduction_rate.required' => 'Absent salary deduction rate is required.',
            'absent_salary_deduction_rate.numeric' => 'Absent salary deduction rate must be a valid number.',
            'absent_salary_deduction_rate.min' => 'Absent salary deduction rate must be at least 0.',
            'absent_salary_deduction_rate.max' => 'Absent salary deduction rate cannot exceed 100.',

            'calculation_type.required' => 'Please select the calculation type.',
            'calculation_type.in' => 'The selected calculation type is invalid.',
        ]);

        return $validated;
    }

}
