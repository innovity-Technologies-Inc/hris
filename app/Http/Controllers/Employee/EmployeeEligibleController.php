<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Enums\UserType;
use App\Imports\Employee\EmployeeEligiblePlanImport;
use App\Models\Employee\EmployeeEducationExperienceTraining;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\ProfileUpdateRequest;
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
        if (auth()->user()->user_type === UserType::Employee) {
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
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
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
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $this->empServices->employeeEligiblePlanValidation($request);

        try {
            $employee = $this->empServices->employeeEligiblePanInfoSave($validated);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeEligibleController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
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
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
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
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
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
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $this->empServices->employeeEligiblePlanValidation($request);
        $employeePlan = EmployeeEligiblePlan::findOrFail($id);
        $employee = $employeePlan->employee_id;

        try {
            $workflowActive = \Innovity\ApprovalEngine\Models\Workflow::where('module', 'employee-policy')->where('is_active', true)->exists();

            if ($workflowActive) {
                // Remove employee_id from validated fields for comparison if needed
                $compareFields = $validated;
                unset($compareFields['employee_id']);

                $updateRequest = ProfileUpdateRequest::createAdminRequest($employee, 'employee-policy', $compareFields, $employeePlan);

                if ($updateRequest) {
                    return redirect()
                        ->route('employee.profile.eligible_plans', $employee)
                        ->with(['message' => 'Employee policy tag update request submitted for approval.',
                            'alert-type' => 'success']);
                } else {
                    return redirect()
                        ->route('employee.profile.eligible_plans', $employee)
                        ->with(['message' => 'No changes detected. Profile remains unchanged.',
                            'alert-type' => 'info']);
                }
            }

            $employeeEligiblePlan = $this->empServices->employeeEligiblePanInfoSave($validated, $employeePlan);
            return redirect()
                ->route('employee.profile.eligible_plans', $employee)
                ->with(['message' => 'Employee eligible plans updated successfully.',
                    'alert-type' => 'success']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeEligibleController@update: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()
                ->back()
                ->withInput()
                ->with(['message' => 'Something went wrong. Please try again later.',
                    'alert-type' => 'error']);
        }
    }

    public function import(Request $request){
        // Restricted for Employees
        if (auth()->user()->user_type === UserType::Employee) {
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
            \Illuminate\Support\Facades\Log::error('Error in EmployeeEligibleController@import: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->back()->with([
                'message' => $e->getMessage(). 'Contact with your administrator',
                'alert-type' => 'error'
            ]);
        }

    }
}

