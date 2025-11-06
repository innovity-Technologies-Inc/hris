<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeBankAccountDetailsImport;
use App\Models\EmployeeBankAccount;
use App\Models\Employee;
use App\Models\Bank;
use App\Models\Branch;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeBankAccountController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $title = 'Add Employees Bank Account Details';
        $section = 'Employees';
        $sub_section = 'Bank Account Details / Add ';
        $section_url = route('employees.index');
        $banks = Bank::all();
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.bank_accounts.form', compact('title', 'section', 'sub_section', 'section_url', 'banks', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->empServices->employeeBankAccountsInfoValidation($request);

        $employee = $this->empServices->employeeBankAccountsInfoSave($validated);

        /*$employeeNominee = EmployeeNominee::where('employee_id', $employee->employee_id)->first();
        if(empty($employeeNominee)){
            return redirect()->route('employees.nominee_information.create', $employee->employee_id)->with([
                'message' => 'Employee eligible plans added successfully.',
                'alert-type' => 'success'
            ]);
        }
        else{*/
            return redirect()->route('employees.profile.bank_accounts', $employee->employee_id)->with([
                'message' => 'Employee bank account details added successfully.',
                'alert-type' => 'success'
            ]);
//        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Employees Bank Accounts Details';
        $section = 'Employees';
        $sub_section = 'Profile - Bank Account Details';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        $employeeData = EmployeeBankAccount::where('employee_id', $id)->first();
        return view('employees.profile', compact('employeeData', 'employee', 'title', 'section', 'sub_section', 'section_url'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = $this->empServices->getEmployeeById($id);
        $employeeData = EmployeeBankAccount::where('employee_id', $id)->first();
        $title = 'Edit Employee Bank Account Details';
        $section = 'Employees';
        $sub_section = 'Bank Account Details/Edit';
        $section_url = route('employees.index');
        $banks = Bank::all();
        return view('employees.bank_accounts.form', compact('employeeData', 'employee', 'title', 'section', 'sub_section', 'section_url', 'banks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $this->empServices->employeeBankAccountsInfoValidation($request);
        $employeeData = EmployeeBankAccount::findOrFail($id);
        try {
            $employeeData = $this->empServices->employeeBankAccountsInfoSave($validated, $employeeData);
            $employee = $employeeData->employee_id;
            return redirect()
                ->route('employees.profile.bank_accounts', $employee)
                ->with(['message' => 'Employee bank account details updated successfully.',
                    'alert-type' => 'success']);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with(['message' => 'Something went wrong. Please try again later.',
                    'alert-type' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeBankAccountDetailsImport(), $request->file('file'));
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
