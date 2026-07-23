<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\Payroll\Increment;
use App\Services\Payroll\PayrollServices;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use App\Http\Requests\Payroll\IncrementRequest;

class IncrementController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Employee Increment';
        $section = 'Employee Increment';
        $sub_section = 'Index';
        $increments = $this->payrollService->searchResult($request, Increment::class, $flexSearch);
        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.increment.partials.search-results', compact('increments'))->render();
        }
        return view('payroll.increment.index', compact('title', 'section', 'sub_section',
            'increments'));
    }

    public function create()
    {
        $title = 'Add Employee Increment';
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $movementTypes = \App\Models\Company\MovementType::where('status', 'active')->get();
        $section = 'Employee Increment';
        $section_url = route('increment.index');
        $sub_section = 'Add';
        return view('payroll.increment.form', compact('title', 'section',
            'sub_section', 'section_url', 'employees', 'payScales', 'movementTypes'));
    }

    public function edit($id)
    {
        $title = 'Edit Increment Data';
        $section = 'Employee Increment';
        $section_url = route('increment.index');
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $movementTypes = \App\Models\Company\MovementType::where('status', 'active')->get();
        $sub_section = 'Edit';
        $incrementData = Increment::find($id);
        return view('payroll.increment.form', compact('title', 'section', 'sub_section',
            'section_url', 'incrementData', 'employees', 'payScales', 'movementTypes'));
    }

    public function save(IncrementRequest $request, $incrementData = null)
    {
        try {
            Log::info('Adding ');
            $result = $this->payrollService->incrementRequestData($request);

            $data = $result['data'];

            if (!empty($incrementData)) {
                $this->payrollService->incrementDataUpdate($incrementData, $data);
                $message = 'Updated Successfully';
            } else {
                $increment = $this->payrollService->incrementDataStore($data);
                $increment->startWorkflow('increment');
                $message = 'Added Successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('increment.index')
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $title = 'Increment Data';
        $section = 'Employee Increment';
        $section_url = route('increment.index');
        $sub_section = 'View';
        $incrementData = Increment::find($id);
        return view('payroll.increment.view', compact('title', 'section', 'sub_section',
            'section_url', 'incrementData'));
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

    public function statusUpdate(Request $request, $id){
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);
        $data = Increment::find($id);
            if ($request->status == 'approved') {
                $data->update([
                    'status' => $request->status,
                    'is_adjustment' => 1
                    ]);

            }else{
                $data->update(['status' => $request->status]);
            }

        return redirect()->route('increment.index')->with([
            'message' => 'Updated Successfully',
        ]);
    }

    public function adjustment(){

        $increments = Increment::where('is_adjustment', 1)
            ->whereDate('effective_from', '<=', Carbon::today())
            ->get();

//        dd($increments);

        DB::transaction(function () use ($increments) {
            foreach ($increments as $increment) {
                $this->payrollService->updateSalaryData($increment);
                $increment->update(['is_adjustment' => 2]);

                \App\Models\Employee\EmployeeLifecycle::create([
                    'employee_id' => $increment->employee_id,
                    'type' => 'salary_increment',
                    'status_date' => $increment->effective_from,
                    'description' => 'Salary increment of ' . $increment->salary_increase_amount . ' applied.'
                ]);
            }
        });
        return redirect()->route('increment.index')->with([
            'message' => 'Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function exportExcel(Request $request, FlexSearch $flexSearch)
    {
        $increments = $this->payrollService->searchResult($request, Increment::class, $flexSearch, false);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Payroll\IncrementExport($increments), 'employee_increments_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function printIndex(Request $request, FlexSearch $flexSearch)
    {
        $records = $this->payrollService->searchResult($request, Increment::class, $flexSearch, false);
        return view('payroll.increment.print_index', compact('records'));
    }
}

