<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeOfficeInfo;
use App\Models\Payroll\Increment;
use App\Models\Payroll\Promotion;
use App\Services\PayrollServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PromotionController extends Controller
{
    protected $payrollService;
    public function __construct(PayrollServices $payrollService){
        $this->payrollService = $payrollService;
    }
    public function index(){
        $title = 'Employee Promotion';
        $section = 'Employee Promotion';
        $sub_section = 'Index';
        $designations = Designation::where('status', 'active')->get();
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $promotions = Promotion::latest()->paginate(10);
        return view('payroll.promotion.index', compact('title', 'section', 'sub_section',
            'promotions', 'employees', 'designations'));
    }

    public function create(){
        $title = 'Add Employee Promotion';
        $employees = Employee::has('salary')->where('status', 'active')->get();
//        dd($employees);
        $designations = Designation::all();
        $section = 'Employee Promotion';
        $section_url = route('promotion.index');
        $sub_section = 'Add';
        return view('payroll.promotion.form', compact('title', 'section', 'sub_section', 'section_url',
            'designations', 'employees'));
    }

    public function edit($id){
        $title = 'Edit Promotion Data';
        $section = 'Employee Promotion';
        $section_url = route('promotion.index');
        $designations = Designation::all();
        $employees = Employee::all()->where('status', 'active');
        $sub_section = 'Edit';
        $promotionData = Promotion::find($id);
        return view('payroll.promotion.form', compact('title', 'section', 'sub_section',
            'section_url', 'promotionData', 'designations', 'employees'));
    }

    public function save(Request $request, $promotionData = null){
        $request->validate([
            'employee_id'              => 'required|exists:employees,id',
            'previous_designation'     => 'required|exists:designations,id',
            'new_designation'          => 'required|exists:designations,id|different:previous_designation',
            'increment_base'           => 'required|in:basic_salary,gross_salary',
            'increment_method'         => 'required|in:fixed,percentage',
            'salary_increase_amount'   => 'required|numeric|min:0',
            'effective_from'           => 'required|date',
            'effective_to'             => 'nullable|date|after_or_equal:effective_from',
            'status'                   => 'nullable|in:approved,pending,rejected',
        ], [
            'employee_id.required'              => 'Please Select An Employee',
            'employee_id.exists'                => 'Selected Employee Is Invalid',

            'previous_designation.required'     => 'Please Select Previous Designation',
            'previous_designation.exists'       => 'Previous Designation Is Invalid',

            'new_designation.required'          => 'Please Select New Designation',
            'new_designation.exists'            => 'New Designation Is Invalid',
            'new_designation.different'         => 'New Designation Must Be Different From Previous Designation',

            'increment_base.required'           => 'Please Select Increment Base',
            'increment_base.in'                 => 'Selected Increment Base Is Invalid',

            'increment_method.required'         => 'Please Select Increment Method',
            'increment_method.in'               => 'Selected Increment Method Is Invalid',

            'salary_increase_amount.required'   => 'Please Enter Salary Increase Amount',
            'salary_increase_amount.numeric'    => 'Increase Amount Must Be A Number',

            'effective_from.required'           => 'Please Select Effective Date',
            'effective_from.date'               => 'Please Enter A Valid Date',

            'effective_to.date'                 => 'Please Enter A Valid Date',
            'effective_to.after_or_equal'       => 'Effective To Date Must Be After Or Equal To Effective From Date',
        ]);

        try{
            Log::info('Adding ');
            $result= $this->payrollService->promotionRequestData($request);
            $data = $result['data'];
            Log::info($data);

                if (!empty($promotionData)) {

                    $this->payrollService->promotionDataUpdate($promotionData, $data);
                } else {
                    $this->payrollService->promotionDataStore($data);

                }
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Added Successfully');

        return redirect()->route('promotion.index')->with([
            'message' => 'Added Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function show($id)
    {
        $title = 'Promotion Data';
        $section = 'Employee Promotion';
        $section_url = route('promotion.index');
        $sub_section = 'View';
        $promotionData = Promotion::find($id);
        return view('payroll.promotion.view', compact('title', 'section', 'sub_section',
            'section_url', 'promotionData'));
    }

    public function delete($id){
        $promotionData = Promotion::find($id);
        $promotionData->delete();
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
