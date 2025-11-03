<?php

namespace App\Http\Controllers;

use App\Models\EmployeeBankAccount;
use App\Models\Employee;
use App\Models\Bank;
use App\Models\Branch;
use Illuminate\Http\Request;

class EmployeeBankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeeBankAccounts = EmployeeBankAccount::all();

        return view('employee_bank_accounts.index', compact('employeeBankAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        $banks = Bank::all();
        $branches = Branch::all();

        return view('employees_bank_accounts.form', compact('employees', 'banks', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'bank_id' => 'required',
            'branch_id' => 'nullable',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:employee_bank_accounts,account_number',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ], [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'bank_id.required' => 'Please select a bank.',
            'bank_id.exists' => 'The selected bank does not exist.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'account_holder_name.required' => 'Account holder name is required.',
            'account_holder_name.max' => 'Account holder name cannot exceed 255 characters.',
            'account_number.required' => 'Account number is required.',
            'account_number.unique' => 'This account number already exists in the system.',
            'account_number.max' => 'Account number cannot exceed 255 characters.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Status must be either active or inactive.',
        ]);


        EmployeeBankAccount::create($validated);

        return redirect()->route('employees_bank_accounts.create')
            ->with([
                'message' => 'Employee bank account added successfully',
                'alert-type' => 'success'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employeeBankAccount = EmployeeBankAccount::with(['getEmployee', 'getBank', 'getBranch'])
            ->findOrFail($id);

        return view('employees.bank_accounts.view', compact('employeeBankAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employeeBankAccount = EmployeeBankAccount::findOrFail($id);

        $employees = Employee::all();

        $banks = Bank::all();

        $branches = Branch::all();

        return view('employees.bank_accounts.form', compact('employeeBankAccount', 'employees', 'banks', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employeeBankAccount = EmployeeBankAccount::findOrFail($id);

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bank_id' => 'required|exists:banks,id',
            'branch_id' => 'nullable|exists:branches,id',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:employee_bank_accounts,account_number,' . $id,
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ], [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'bank_id.required' => 'Please select a bank.',
            'bank_id.exists' => 'The selected bank does not exist.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'account_holder_name.required' => 'Account holder name is required.',
            'account_holder_name.max' => 'Account holder name cannot exceed 255 characters.',
            'account_number.required' => 'Account number is required.',
            'account_number.unique' => 'This account number already exists in the system.',
            'account_number.max' => 'Account number cannot exceed 255 characters.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Status must be either active or inactive.',
        ]);

        $employeeBankAccount->update($validated);

        return redirect()->route('employees_bank_accounts.index')
            ->with('success', 'Employee bank account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employeeBankAccount = EmployeeBankAccount::findOrFail($id);

        $employeeBankAccount->delete();

        return redirect()->route('employees_bank_accounts.index')
            ->with('success', 'Employee bank account deleted successfully.');
    }
}
