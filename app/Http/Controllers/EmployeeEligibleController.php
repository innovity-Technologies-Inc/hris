<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEligiblePlan;
use App\Models\Employee;
use App\Services\EmployeeServices;

use Illuminate\Http\Request;

class EmployeeEligibleController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }
    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Index';
        $employees = Employee::all();
        return view('employees.eligible_plans.form', compact('employees', 'title', 'section', 'sub_section'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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

            // Attendance Bonus Plan
            'attendance_bonus_plan_from' => 'nullable|date',
            'attendance_bonus_plan_to' => 'nullable|date|after_or_equal:attendance_bonus_plan_from',
            'attendance_bonus_plan_status' => 'nullable|in:active,inactive',

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

            // Production Plan
            'production_plan_from' => 'nullable|date',
            'production_plan_to' => 'nullable|date|after_or_equal:production_plan_from',
            'production_plan_status' => 'nullable|in:active,inactive',

            // Early Out Deduction Plan
            'early_out_deduction_plan_from' => 'nullable|date',
            'early_out_deduction_plan_to' => 'nullable|date|after_or_equal:early_out_deduction_plan_from',
            'early_out_deduction_plan_status' => 'nullable|in:active,inactive',

            // Salary Breakdown Plan
            'salary_breakdown_plan_from' => 'nullable|date',
            'salary_breakdown_plan_to' => 'nullable|date|after_or_equal:salary_breakdown_plan_from',
            'salary_breakdown_plan_status' => 'nullable|in:active,inactive',

            // Medical Plan
            'medical_plan_from' => 'nullable|date',
            'medical_plan_to' => 'nullable|date|after_or_equal:medical_plan_from',
            'medical_plan_status' => 'nullable|in:active,inactive',

            // Night Bill Plan
            'night_bill_plan_from' => 'nullable|date',
            'night_bill_plan_to' => 'nullable|date|after_or_equal:night_bill_plan_from',
            'night_bill_plan_status' => 'nullable|in:active,inactive',

            // Tiffin Plan
            'tiffin_plan_from' => 'nullable|date',
            'tiffin_plan_to' => 'nullable|date|after_or_equal:tiffin_plan_from',
            'tiffin_plan_status' => 'nullable|in:active,inactive',

            // Dinner Plan
            'dinner_plan_from' => 'nullable|date',
            'dinner_plan_to' => 'nullable|date|after_or_equal:dinner_plan_from',
            'dinner_plan_status' => 'nullable|in:active,inactive',

            // Breakfast Plan
            'breakfast_plan_from' => 'nullable|date',
            'breakfast_plan_to' => 'nullable|date|after_or_equal:breakfast_plan_from',
            'breakfast_plan_status' => 'nullable|in:active,inactive',

            // Food Com Plan
            'food_com_plan_from' => 'nullable|date',
            'food_com_plan_to' => 'nullable|date|after_or_equal:food_com_plan_from',
            'food_com_plan_status' => 'nullable|in:active,inactive',

            // Excessive Late Plan
            'excessive_late_plan_from' => 'nullable|date',
            'excessive_late_plan_to' => 'nullable|date|after_or_equal:excessive_late_plan_from',
            'excessive_late_plan_status' => 'nullable|in:active,inactive',

            // Lunch Plan
            'lunch_plan_from' => 'nullable|date',
            'lunch_plan_to' => 'nullable|date|after_or_equal:lunch_plan_from',
            'lunch_plan_status' => 'nullable|in:active,inactive',

            // Snacks Plan
            'snacks_plan_from' => 'nullable|date',
            'snacks_plan_to' => 'nullable|date|after_or_equal:snacks_plan_from',
            'snacks_plan_status' => 'nullable|in:active,inactive',
        ],
    [
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
            'attendance_bonus_plan_from.date' => 'The attendance bonus  plan from date is invalid.',
            'attendance_bonus_plan_to.date' => 'The attendance bonus plan to date is invalid.',
            'attendance_bonus_plan_to.after_or_equal' => 'The attendance bonus plan to date must be after or equal to the from date.',
            'attendance_bonus_plan_status.in' => 'The attendance bonus plan status must be either active or inactive.',
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
            'production_plan_from.date' => 'The production plan from date is invalid.',
            'production_plan_to.date' => 'The production plan to date is invalid.',
            'production_plan_to.after_or_equal' => 'The production plan to date must be after or equal to the from date.',
            'production_plan_status.in' => 'The production plan status must be either active or inactive.',
            'early_out_deduction_plan_from.date' => 'The early out deduction plan from date is invalid.',
            'early_out_deduction_plan_to.date' => 'The early out deduction plan to date is invalid.',
            'early_out_deduction_plan_to.after_or_equal' => 'The early out deduction plan to date must be after or equal to the from date.',
            'early_out_deduction_plan_status.in' => 'The early out deduction plan status must be either active or inactive.',
            'salary_breakdown_plan_from.date' => 'The salary breakdown plan from date is invalid.',
            'salary_breakdown_plan_to.date' => 'The salary breakdown plan to date is invalid.',
            'salary_breakdown_plan_to.after_or_equal' => 'The salary breakdown plan to date must be after or equal to the from date.',
            'salary_breakdown_plan_status.in' => 'The salary breakdown plan status must be either active or inactive.',
    ]);

        try {
            EmployeeEligiblePlan::create($validated);

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee eligible plans created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create employee eligible plans. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Index';
        $employees = $this->empServices->getEmployeeById($id);
        $employeePlan = EmployeeEligiblePlan::where('employee_id', $id)->first();
        return view('employees.eligible_plans.info', compact('employeePlan', 'employees', 'title', 'section', 'sub_section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employeePlans = $this->empServices->getEmployeeById($id);
        $employees = Employee::orderBy('name')->get();

        return view('employees.eligible_plans.form', compact('employeePlans', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeEligiblePlan $employeeEligiblePlan)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id|unique:employee_eligible_plans,employee_id,' . $employeeEligiblePlan->id,

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

            // Attendance Bonus Plan
            'attendance_bonus_plan_from' => 'nullable|date',
            'attendance_bonus_plan_to' => 'nullable|date|after_or_equal:attendance_bonus_plan_from',
            'attendance_bonus_plan_status' => 'nullable|in:active,inactive',

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

            // Production Plan
            'production_plan_from' => 'nullable|date',
            'production_plan_to' => 'nullable|date|after_or_equal:production_plan_from',
            'production_plan_status' => 'nullable|in:active,inactive',

            // Early Out Deduction Plan
            'early_out_deduction_plan_from' => 'nullable|date',
            'early_out_deduction_plan_to' => 'nullable|date|after_or_equal:early_out_deduction_plan_from',
            'early_out_deduction_plan_status' => 'nullable|in:active,inactive',

            // Salary Breakdown Plan
            'salary_breakdown_plan_from' => 'nullable|date',
            'salary_breakdown_plan_to' => 'nullable|date|after_or_equal:salary_breakdown_plan_from',
            'salary_breakdown_plan_status' => 'nullable|in:active,inactive',

            // Medical Plan
            'medical_plan_from' => 'nullable|date',
            'medical_plan_to' => 'nullable|date|after_or_equal:medical_plan_from',
            'medical_plan_status' => 'nullable|in:active,inactive',

            // Night Bill Plan
            'night_bill_plan_from' => 'nullable|date',
            'night_bill_plan_to' => 'nullable|date|after_or_equal:night_bill_plan_from',
            'night_bill_plan_status' => 'nullable|in:active,inactive',

            // Tiffin Plan
            'tiffin_plan_from' => 'nullable|date',
            'tiffin_plan_to' => 'nullable|date|after_or_equal:tiffin_plan_from',
            'tiffin_plan_status' => 'nullable|in:active,inactive',

            // Dinner Plan
            'dinner_plan_from' => 'nullable|date',
            'dinner_plan_to' => 'nullable|date|after_or_equal:dinner_plan_from',
            'dinner_plan_status' => 'nullable|in:active,inactive',

            // Breakfast Plan
            'breakfast_plan_from' => 'nullable|date',
            'breakfast_plan_to' => 'nullable|date|after_or_equal:breakfast_plan_from',
            'breakfast_plan_status' => 'nullable|in:active,inactive',

            // Food Com Plan
            'food_com_plan_from' => 'nullable|date',
            'food_com_plan_to' => 'nullable|date|after_or_equal:food_com_plan_from',
            'food_com_plan_status' => 'nullable|in:active,inactive',

            // Excessive Late Plan
            'excessive_late_plan_from' => 'nullable|date',
            'excessive_late_plan_to' => 'nullable|date|after_or_equal:excessive_late_plan_from',
            'excessive_late_plan_status' => 'nullable|in:active,inactive',

            // Lunch Plan
            'lunch_plan_from' => 'nullable|date',
            'lunch_plan_to' => 'nullable|date|after_or_equal:lunch_plan_from',
            'lunch_plan_status' => 'nullable|in:active,inactive',

            // Snacks Plan
            'snacks_plan_from' => 'nullable|date',
            'snacks_plan_to' => 'nullable|date|after_or_equal:snacks_plan_from',
            'snacks_plan_status' => 'nullable|in:active,inactive',
        ],[
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
            'attendance_bonus_plan_from.date' => 'The attendance bonus  plan from date is invalid.',
            'attendance_bonus_plan_to.date' => 'The attendance bonus plan to date is invalid.',
            'attendance_bonus_plan_to.after_or_equal' => 'The attendance bonus plan to date must be after or equal to the from date.',
            'attendance_bonus_plan_status.in' => 'The attendance bonus plan status must be either active or inactive.',
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
            'production_plan_from.date' => 'The production plan from date is invalid.',
            'production_plan_to.date' => 'The production plan to date is invalid.',
            'production_plan_to.after_or_equal' => 'The production plan to date must be after or equal to the from date.',
            'production_plan_status.in' => 'The production plan status must be either active or inactive.',
            'early_out_deduction_plan_from.date' => 'The early out deduction plan from date is invalid.',
            'early_out_deduction_plan_to.date' => 'The early out deduction plan to date is invalid.',
            'early_out_deduction_plan_to.after_or_equal' => 'The early out deduction plan to date must be after or equal to the from date.',
            'early_out_deduction_plan_status.in' => 'The early out deduction plan status must be either active or inactive.',
            'salary_breakdown_plan_from.date' => 'The salary breakdown plan from date is invalid.',
            'salary_breakdown_plan_to.date' => 'The salary breakdown plan to date is invalid.',
            'salary_breakdown_plan_to.after_or_equal' => 'The salary breakdown plan to date must be after or equal to the from date.',
            'salary_breakdown_plan_status.in' => 'The salary breakdown plan status must be either active or inactive.',
    ]);

        try {
            $employeeEligiblePlan->update($validated);

            return redirect()
                ->route('employees.eligible_plans.show', $employeeEligiblePlan)
                ->with('success', 'Employee eligible plans updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update employee eligible plans. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeEligiblePlan $employeeEligiblePlan)
    {
        try {
            $employeeEligiblePlan->delete();

            return redirect()
                ->route('employees.eligible_plans.index')
                ->with('success', 'Employee eligible plans deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete employee eligible plans. ' . $e->getMessage());
        }
    }
}
