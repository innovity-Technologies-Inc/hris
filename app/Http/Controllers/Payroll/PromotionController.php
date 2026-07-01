<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Company\Designation;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Payroll\Increment;
use App\Models\Payroll\Promotion;
use App\Services\Payroll\PayrollServices;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PromotionController extends Controller
{
    protected $payrollService;
    public function __construct(PayrollServices $payrollService){
        $this->payrollService = $payrollService;
    }
    public function index(Request $request, FlexSearch $flexSearch){
        $title = 'Employee Promotion';
        $section = 'Employee Promotion';
        $sub_section = 'Index';
        $promotions = $this->payrollService->searchResult($request, Promotion::class, $flexSearch);

        if ($request->ajax()) {
            return view('payroll.promotion.partials.search-results', compact('promotions'));
        }
        return view('payroll.promotion.index', compact('title', 'section', 'sub_section',
            'promotions'));
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

            'promotion_base.required'           => 'Please Select Increment Base',
            'promotion_base.in'                 => 'Selected Increment Base Is Invalid',

            'promotion_method.required'         => 'Please Select Increment Method',
            'promotion_method.in'               => 'Selected Increment Method Is Invalid',

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
                    $promotion = $this->payrollService->promotionDataStore($data);
                    $promotion->startWorkflow('promotion');
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

    public function statusUpdate(Request $request, $id){
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);
        $data = Promotion::find($id);
        if ($request->status == 'approved') {
            $data->update([
                'status' => $request->status,
                'is_adjustment' => 1
            ]);

        }else{
            $data->update(['status' => $request->status]);
        }

        return redirect()->route('promotion.index')->with([
            'message' => 'Updated Successfully',
        ]);
    }

    public function adjustment(){

        $promotions = Promotion::where('is_adjustment', 1)
            ->whereDate('effective_from', '<=', Carbon::today())
            ->get();
//        dd($promotions);

        try{
            DB::transaction(function () use ($promotions) {
                foreach ($promotions as $promotion) {
                    $this->payrollService->updateSalaryData($promotion);
                    $this->payrollService->designationUpdate($promotion);
                    $promotion->update(['is_adjustment' => 2]);

                    \App\Models\Employee\EmployeeLifecycle::create([
                        'employee_id' => $promotion->employee_id,
                        'type' => 'promotion',
                        'status_date' => $promotion->effective_from,
                        'description' => 'Promoted to a new designation.'
                    ]);
                }
            });

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('promotion.index')->with([
            'message' => 'Updated Successfully',
            'alert-type' => 'success'
        ]);
    }
}

