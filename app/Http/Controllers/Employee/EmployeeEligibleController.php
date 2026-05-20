<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Imports\Employee\EmployeeEligiblePlanImport;
use App\Models\Employee\EmployeeEducationExperienceTraining;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeNominee;
use App\Services\Employee\EmployeeServices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeEligibleController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }


    public function create($id)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === 'Employee') {
            abort(403, 'Unauthorized access.');
        }

        $title = 'Add Employees Eligible Plan';
        $section = 'Employees';
        $sub_section = 'Eligible Plan / Create';
        $section_url = route('employee.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        return view('employee.eligible_plans.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === 'Employee') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $this->empServices->employeeEligiblePlanValidation($request);

        try {
            $employee = $this->empServices->employeeEligiblePanInfoSave($validated);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                    'message' => 'Something went wrong. Please try again later.',
                    'alert-type' => 'error'
                    ]);
        }

        $employeeNominee = EmployeeNominee::where('employee_id', $employee->employee_id)->first();
        if(empty($employeeNominee)){
            return redirect()->route('employee.nominee_information.create', $employee->employee_id)->with([
                'message' => 'Employee eligible plans added successfully.',
                'alert-type' => 'success'
            ]);
        }
        else{
            return redirect()->route('employee.profile.eligible_plans', $employee->employee_id)->with([
                'message' => 'Employee eligible plans added successfully.',
                'alert-type' => 'success'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $title = 'Employees Eligible Plans';
        $section = 'Employees';
        $sub_section = 'Profile - Eligible Plan';
        $section_url = route('employee.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $employeePlan = EmployeeEligiblePlan::where('employee_id', $id)->first();
//        dd($employeePlan);
        return view('employee.profile', compact('employeePlan', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === 'Employee') {
            abort(403, 'Unauthorized access.');
        }

        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $employeePlan = EmployeeEligiblePlan::where('employee_id', $id)->first();
        $title = 'Edit Employee Eligible Plan';
        $section = 'Employees';
        $sub_section = 'Eligible Plan / Edit';
        $section_url = route('employee.index');

        return view('employee.eligible_plans.form', compact('employeePlan', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === 'Employee') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $this->empServices->employeeEligiblePlanValidation($request);
        $employeePlan = EmployeeEligiblePlan::findOrFail($id);
        try {
            $employeeEligiblePlan = $this->empServices->employeeEligiblePanInfoSave($validated, $employeePlan);
            $employee = $employeeEligiblePlan->employee_id;
            return redirect()
                ->route('employee.profile.eligible_plans', $employee)
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

    public function import(Request $request){
        // Restricted for Employees
        if (auth()->user()->user_type === 'Employee') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeEligiblePlanImport(), $request->file('file'));
            return redirect()->route('employee.index')->with([
                'message' => 'Imported Successfully',
                'alert-type' => 'success'
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(). 'Contact with your administrator',
                'alert-type' => 'error'
            ]);
        }

    }
}

