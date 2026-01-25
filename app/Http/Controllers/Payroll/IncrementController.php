<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll\Increment;
use App\Services\PayrollServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncrementController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        $title = 'Employee Increment';
        $section = 'Employee Increment';
        $sub_section = 'Index';
        $employees = Employee::where('status', 'active')->get();
        $increments = Increment::latest()->paginate(10);
        return view('payroll.increment.index', compact('title', 'section', 'sub_section',
            'increments', 'employees'));
    }

    public function create()
    {
        $title = 'Add Employee Increment';
        $employees = Employee::all()->where('status', 'active');
        $section = 'Employee Increment';
        $section_url = route('increment.index');
        $sub_section = 'Add';
        return view('payroll.increment.form', compact('title', 'section',
            'sub_section', 'section_url', 'employees'));
    }

    public function edit($id)
    {
        $title = 'Edit Increment Data';
        $section = 'Employee Increment';
        $section_url = route('increment.index');
        $employees = Employee::all()->where('status', 'active');
        $sub_section = 'Edit';
        $incrementData = Increment::find($id);
        return view('payroll.increment.form', compact('title', 'section', 'sub_section',
            'section_url', 'incrementData', 'employees'));
    }

    public function save(Request $request, $incrementData = null)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'previous_designation' => 'required|exists:designations,id',
            'new_designation' => 'required|exists:designations,id|different:previous_designation',
            'increment_base' => 'required|in:basic_salary,gross_salary',
            'increment_method' => 'required|in:fixed,percentage',
            'salary_increase_amount' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'status' => 'required|in:pending,approved,rejected',
        ], [
            'employee_id.required' => 'Please Select An Employee',
            'employee_id.exists' => 'Selected Employee Is Invalid',

            'previous_designation.required' => 'Please Select Previous Designation',
            'previous_designation.exists' => 'Previous Designation Is Invalid',

            'new_designation.required' => 'Please Select New Designation',
            'new_designation.exists' => 'New Designation Is Invalid',
            'new_designation.different' => 'New Designation Must Be Different From Previous Designation',

            'increment_base.required' => 'Please Select Increment Base',
            'increment_base.in' => 'Selected Increment Base Is Invalid',

            'increment_method.required' => 'Please Select Increment Method',
            'increment_method.in' => 'Selected Increment Method Is Invalid',

            'salary_increase_amount.required' => 'Please Enter Salary Increase Amount',
            'salary_increase_amount.numeric' => 'Increase Amount Must Be A Number',

            'effective_from.required' => 'Please Select Effective Date',
            'effective_from.date' => 'Please Enter A Valid Date',

            'effective_to.date' => 'Please Enter A Valid Date',
            'effective_to.after_or_equal' => 'Effective To Date Must Be After Or Equal To Effective From Date',

            'status.required' => 'Please Select Status',
            'status.in' => 'Selected Status Is Invalid',
        ]);

        try {
            Log::info('Adding ');
            $result = $this->payrollService->incrementRequestData($request);
            $data = $result['data'];
            $employeeSalary = $result['employee_salary'];

            DB::transaction(function () use ($employeeSalary, $incrementData, $data) {
                $employeeSalary->update([
                    'basic_salary' => $data['basic_salary'],
                    'gross_salary' => $data['gross_salary'],
                ]);

                if (!empty($incrementData)) {

                    $this->payrollService->incrementDataUpdate($incrementData, $data);
                } else {
                    $this->payrollService->incrementDataUpdate($incrementData, $data);

                }
            });

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Added Successfully');

        return redirect()->route('increment.index')->with([
            'message' => 'Added Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function delete($id)
    {
        $incrementData = Increment::find($id);
        $incrementData->delete();
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
