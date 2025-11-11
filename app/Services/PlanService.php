<?php

namespace App\Services;

use App\Models\MealPlan;

class PlanService
{
    public function planSave($validated, $modelName, $id = null){
        if (isset($id)){
            $plan = $modelName::findOrFail($id);
            $plan->update($validated);
        }else{
            $plan = $modelName::create($validated);
        }
        return $plan;
    }
    public function planDelete($modelName, $id){
        $plan = $modelName::findOrFail($id);
        $plan->delete();
    }

    public function getPlans($modelName, $paginate){
        $plans = $modelName::latest()->paginate($paginate);
        return $plans;
    }

    public function getPlanByID($id, $modelName){
        $plan = $modelName::findOrFail($id);
        return $plan;
    }

    public function getMealPlans($type){
        $mealPlans = MealPlan::where('type', $type)->get();
        return $mealPlans;
    }

    public function mealPlanValidation($request){
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
            'ot_type' => 'required|in:regular,holiday,night_shift,weekend,other',
            'overtime_rate_type' => 'required|in:multiplier,per_hour',
            'overtime_rate' => 'required|numeric|min:0',
            'minimum_overtime_hours' => 'nullable|numeric|min:0',
            'maximum_overtime_hours' => 'nullable|numeric|min:0|gt:minimum_overtime_hours',
            'overtime_start_time' => 'nullable|date_format:H:i',
            'overtime_end_time' => 'nullable|date_format:H:i|after:overtime_start_time',
            'max_ot_limit' => 'nullable|numeric|min:0',
            'max_ot_period' => 'nullable|in:daily,weekly,monthly,yearly|required_with:max_ot_limit',
            'notes' => 'nullable|string',
            'active_ind' => 'required|in:active,inactive',
        ], [
            'name.required' => 'OT plan name is required.',
            'name.string' => 'OT plan name must be a string.',
            'name.max' => 'OT plan name may not exceed 255 characters.',

            'ot_type.required' => 'Please select an overtime type.',
            'ot_type.in' => 'The selected overtime type is invalid.',

            'overtime_rate_type.required' => 'Please select a rate type.',
            'overtime_rate_type.in' => 'The selected rate type is invalid.',

            'overtime_rate.required' => 'Overtime rate is required.',
            'overtime_rate.numeric' => 'Overtime rate must be a number.',
            'overtime_rate.min' => 'Overtime rate must be at least 0.',

            'minimum_overtime_hours.numeric' => 'Minimum hours must be a number.',
            'minimum_overtime_hours.min' => 'Minimum hours must be at least 0.',

            'maximum_overtime_hours.numeric' => 'Maximum hours must be a number.',
            'maximum_overtime_hours.min' => 'Maximum hours must be at least 0.',
            'maximum_overtime_hours.gt' => 'Maximum hours must be greater than minimum hours.',

            'overtime_start_time.date_format' => 'Start time must be in the format HH:MM.',
            'overtime_end_time.date_format' => 'End time must be in the format HH:MM.',
            'overtime_end_time.after' => 'End time must be after start time.',

            'max_ot_limit.numeric' => 'OT limit must be a number.',
            'max_ot_limit.min' => 'OT limit must be at least 0.',

            'max_ot_period.in' => 'The selected period is invalid.',
            'max_ot_period.required_with' => 'Period is required when OT limit is set.',

            'active_ind.required' => 'Please select the plan status.',
            'active_ind.in' => 'The selected status is invalid.',
        ]);

        return $validated;
    }

}
