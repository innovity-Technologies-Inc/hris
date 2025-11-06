<?php

namespace App\Http\Controllers;

use App\Imports\SalaryBreakdownImport;
use App\Models\EmployeeBankAccount;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeSalaryBreakdown;
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
        $title = 'Add Employees Salary Breakdown';
        $section = 'Employees';
        $sub_section = 'Salary Breakdown / Add';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.salary_breakdown.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            return redirect()->route('employees.bank_accounts.create', $employee->employee_id)->with([
                'message' => 'Employee eligible plans added successfully.',
                'alert-type' => 'success'
            ]);
        }
        else{
            return redirect()->route('employees.profile.salary_breakdown', $employee->employee_id)->with([
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
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        $employeeData = EmployeeSalaryBreakdown::where('employee_id', $id)->first();
//        dd($employeeData);
        return view('employees.profile', compact('employeeData', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = $this->empServices->getEmployeeById($id);
        $employeeData = EmployeeSalaryBreakdown::where('employee_id', $id)->first();
        $title = 'Edit Employee Salary Breakdown';
        $section = 'Employees';
        $sub_section = 'Salary Breakdown / Edit';
        $section_url = route('employees.index');
        return view('employees.salary_breakdown.form', compact('employeeData', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $this->empServices->employeeSalaryBreakdownValidation($request);
        $employeeData = EmployeeSalaryBreakdown::findOrFail($id);
        try {
            $employeeData = $this->empServices->employeeSalaryBreakdownInfoSave($validated, $employeeData);
            $employee = $employeeData->employee_id;
            return redirect()
                ->route('employees.profile.salary_breakdown', $employee)
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
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new SalaryBreakdownImport(), $request->file('file'));
            return redirect()->route('employees.index')->with([
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
