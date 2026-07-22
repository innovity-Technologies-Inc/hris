<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\Payroll\Decrement;
use App\Http\Requests\Payroll\DecrementRequest;
use App\Services\Payroll\PayrollServices;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DecrementController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Employee Decrement';
        $section = 'Employee Decrement';
        $sub_section = 'Index';
        $decrements = $this->payrollService->searchResult($request, Decrement::class, $flexSearch);
        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.decrement.partials.search-results', compact('decrements'))->render();
        }
        return view('payroll.decrement.index', compact('title', 'section', 'sub_section', 'decrements'));
    }

    public function create()
    {
        $title = 'Add Employee Decrement';
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $movementTypes = \App\Models\Company\MovementType::where('status', 'active')->get();
        $section = 'Employee Decrement';
        $section_url = route('decrement.index');
        $sub_section = 'Add';
        return view('payroll.decrement.form', compact('title', 'section', 'sub_section', 'section_url', 'employees', 'payScales', 'movementTypes'));
    }

    public function edit($id)
    {
        $title = 'Edit Decrement Data';
        $section = 'Employee Decrement';
        $section_url = route('decrement.index');
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $movementTypes = \App\Models\Company\MovementType::where('status', 'active')->get();
        $sub_section = 'Edit';
        $decrementData = Decrement::find($id);
        return view('payroll.decrement.form', compact('title', 'section', 'sub_section', 'section_url', 'decrementData', 'employees', 'payScales', 'movementTypes'));
    }

    public function save(DecrementRequest $request, $decrementData = null)
    {
        $validated = $request->validated();

        try {
            Log::info('Saving decrement request.');
            $result = $this->payrollService->decrementRequestData($request);
            $data = $result['data'];

            if (!empty($decrementData)) {
                $this->payrollService->decrementDataUpdate($decrementData, $data);
                $message = 'Updated Successfully';
            } else {
                $decrement = $this->payrollService->decrementDataStore($data);
                $decrement->startWorkflow('decrement');
                $message = 'Added Successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('decrement.index')
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
        $title = 'Decrement Data';
        $section = 'Employee Decrement';
        $section_url = route('decrement.index');
        $sub_section = 'View';
        $decrementData = Decrement::find($id);
        return view('payroll.decrement.view', compact('title', 'section', 'sub_section', 'section_url', 'decrementData'));
    }

    public function delete($id)
    {
        $decrementData = Decrement::find($id);
        $decrementData->delete();
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);
        $data = Decrement::find($id);
        if ($request->status == 'approved') {
            $data->update([
                'status' => $request->status,
                'is_adjustment' => 1
            ]);
        } else {
            $data->update(['status' => $request->status]);
        }

        return redirect()->route('decrement.index')->with([
            'message' => 'Updated Successfully',
        ]);
    }

    public function adjustment()
    {
        $decrements = Decrement::where('is_adjustment', 1)
            ->whereDate('effective_from', '<=', Carbon::today())
            ->get();

        DB::transaction(function () use ($decrements) {
            foreach ($decrements as $decrement) {
                $this->payrollService->updateSalaryData($decrement);
                $decrement->update(['is_adjustment' => 2]);

                \App\Models\Employee\EmployeeLifecycle::create([
                    'employee_id' => $decrement->employee_id,
                    'type' => 'salary_decrement',
                    'status_date' => $decrement->effective_from,
                    'description' => 'Salary decrement of ' . $decrement->salary_decrease_amount . ' applied.'
                ]);
            }
        });

        return redirect()->route('decrement.index')->with([
            'message' => 'Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function exportExcel(Request $request, FlexSearch $flexSearch)
    {
        $decrements = $this->payrollService->searchResult($request, Decrement::class, $flexSearch, false);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Payroll\DecrementExport($decrements), 'employee_decrements_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function printIndex(Request $request, FlexSearch $flexSearch)
    {
        $records = $this->payrollService->searchResult($request, Decrement::class, $flexSearch, false);
        return view('payroll.decrement.print_index', compact('records'));
    }
}
