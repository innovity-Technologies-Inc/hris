<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEligiblePlan;
use App\Models\Employee;
use App\Services\EmployeeServices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeEligibleController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }
    /**
     * Show the form for creating a new resource.
     */

    public function create($id)
    {
        $title = 'Add Employees Eligible Plan';
        $section = 'Employees Eligible Plan';
        $sub_section = 'Create';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.eligible_plans.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->empServices->employeeEligiblePlanValidation($request);

        try {
            $this->empServices->employeeEligiblePanInfoSave($validated);
            return redirect()
                ->route('employee.education-experience-training.create')
                ->with([
                    'message' => 'Eligible Plans Added Successfully',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                    'message' => 'Something went wrong. Please try again later.',
                    'alert-type' => 'error'
                    ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Employees Eligible Plan';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        $employeePlan = EmployeeEligiblePlan::where('employee_id', $id)->first();
//        dd($employeePlan);
        return view('employees.profile', compact('employeePlan', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = $this->empServices->getEmployeeById($id);
        $employeePlan = EmployeeEligiblePlan::where('employee_id', $id)->first();
        $title = 'Edit Employee Eligible Plan';
        $section = 'Employees Eligible Plan';
        $sub_section = 'Edit';
        $section_url = route('employees.index');

        return view('employees.eligible_plans.form', compact('employeePlan', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $this->empServices->employeeEligiblePlanValidation($request);
        $employeePlan = EmployeeEligiblePlan::findOrFail($id);
        try {
            $employeeEligiblePlan = $this->empServices->employeeEligiblePanInfoSave($validated, $employeePlan);
            $employee = $employeeEligiblePlan->employee_id;
            return redirect()
                ->route('employees.profile.eligible_plans', $employee)
                ->with(['message' => 'Employee eligible plans updated successfully.',
                    'alert-type' => 'success']);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with(['message' => 'Something went wrong. Please try again later.',
                    'alert-type' => 'error']);
        }
    }
}
