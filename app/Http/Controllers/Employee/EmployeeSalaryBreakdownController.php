<?php

namespace App\Http\Controllers\Employee;

use App\Enums\UserType;
use App\Http\Controllers\Controller;

use App\Imports\Employee\SalaryBreakdownImport;
use App\Models\Employee\EmployeeBankAccount;
use App\Services\Employee\EmployeeServices;
use Illuminate\Http\Request;
use App\Models\Employee\EmployeeSalaryBreakdown;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeSalaryBreakdownController extends Controller
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

        $title = 'Add Employees Salary Breakdown';
        $section = 'Employees';
        $sub_section = 'Salary Breakdown / Add';
        $section_url = route('employee.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();

        return view('employee.salary_breakdown.form', compact('employee', 'title', 'section', 'sub_section', 'section_url', 'payScales'));
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

        $validated = $this->empServices->employeeSalaryBreakdownValidation($request);

        try {
            $employee = $this->empServices->employeeSalaryBreakdownInfoSave($validated);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }

        $employeeBankDetails = EmployeeBankAccount::where('employee_id', $employee->employee_id)->first();
        if(empty($employeeBankDetails)){
            return redirect()->route('employee.bank_accounts.create', $employee->employee_id)->with([
                'message' => 'Employee Salary Breakdown added successfully.',
                'alert-type' => 'success'
            ]);
        }
        else{
            return redirect()->route('employee.profile.salary_breakdown', $employee->employee_id)->with([
                'message' => 'Employee Salary Breakdown added successfully.',
                'alert-type' => 'success'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $title = 'Employees Salary Breakdown';
        $section = 'Employees';
        $sub_section = 'Employees Salary Breakdown';
        $section_url = route('employee.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $employeeData = EmployeeSalaryBreakdown::where('employee_id', $id)->first();
//        dd($employeeData);
        return view('employee.profile', compact('employeeData', 'employee', 'title', 'section', 'sub_section', 'section_url'));
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

        $employeeData = EmployeeSalaryBreakdown::where('employee_id', $id)->first();
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();

        $title = 'Edit Employee Salary Breakdown';
        $section = 'Employees';
        $sub_section = 'Salary Breakdown / Edit';
        $section_url = route('employee.index');
        return view('employee.salary_breakdown.form', compact('employeeData', 'employee', 'title', 'section', 'sub_section', 'section_url', 'payScales'));
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

        $validated = $this->empServices->employeeSalaryBreakdownValidation($request);
        $employeeData = EmployeeSalaryBreakdown::findOrFail($id);
        try {
            $employeeData = $this->empServices->employeeSalaryBreakdownInfoSave($validated, $employeeData);
            $employee = $employeeData->employee_id;
            return redirect()
                ->route('employee.profile.salary_breakdown', $employee)
                ->with(['message' => 'Employee salary breakdown updated successfully.',
                    'alert-type' => 'success']);
        } catch (\Exception $e) {
            return redirect()
                ->back()
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
            Excel::import(new SalaryBreakdownImport(), $request->file('file'));
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

