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


use App\Http\Requests\Payroll\PromotionRequest;

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

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.promotion.partials.search-results', compact('promotions'))->render();
        }
        return view('payroll.promotion.index', compact('title', 'section', 'sub_section',
            'promotions'));
    }

    public function create(){
        $title = 'Add Employee Promotion';
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $designations = Designation::all();
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $movementTypes = \App\Models\Company\MovementType::where('status', 'active')->get();
        $section = 'Employee Promotion';
        $section_url = route('promotion.index');
        $sub_section = 'Add';
        return view('payroll.promotion.form', compact('title', 'section', 'sub_section', 'section_url',
            'designations', 'employees', 'payScales', 'movementTypes'));
    }

    public function edit($id){
        $title = 'Edit Promotion Data';
        $section = 'Employee Promotion';
        $section_url = route('promotion.index');
        $designations = Designation::all();
        $employees = Employee::all()->where('status', 'active');
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $movementTypes = \App\Models\Company\MovementType::where('status', 'active')->get();
        $sub_section = 'Edit';
        $promotionData = Promotion::find($id);
        return view('payroll.promotion.form', compact('title', 'section', 'sub_section',
            'section_url', 'promotionData', 'designations', 'employees', 'payScales', 'movementTypes'));
    }

    public function save(PromotionRequest $request, $promotionData = null){
        try{
            Log::info('Adding ');
            $result= $this->payrollService->promotionRequestData($request);
            $data = $result['data'];
            Log::info($data);

            if (!empty($promotionData)) {
                $this->payrollService->promotionDataUpdate($promotionData, $data);
                $message = 'Updated Successfully';
            } else {
                $promotion = $this->payrollService->promotionDataStore($data);
                $promotion->startWorkflow('promotion');
                $message = 'Added Successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('promotion.index')
            ]);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
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

    public function exportExcel(Request $request, FlexSearch $flexSearch)
    {
        $promotions = $this->payrollService->searchResult($request, Promotion::class, $flexSearch, false);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Payroll\PromotionExport($promotions), 'employee_promotions_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function printIndex(Request $request, FlexSearch $flexSearch)
    {
        $records = $this->payrollService->searchResult($request, Promotion::class, $flexSearch, false);
        return view('payroll.promotion.print_index', compact('records'));
    }
}

