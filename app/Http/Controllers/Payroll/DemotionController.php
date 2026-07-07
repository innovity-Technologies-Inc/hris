<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Company\Designation;
use App\Models\Employee\Employee;
use App\Models\Payroll\Demotion;
use App\Http\Requests\Payroll\DemotionRequest;
use App\Services\Payroll\PayrollServices;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemotionController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Employee Demotion';
        $section = 'Employee Demotion';
        $sub_section = 'Index';
        $demotions = $this->payrollService->searchResult($request, Demotion::class, $flexSearch);

        if ($request->ajax()) {
            return view('payroll.demotion.partials.search-results', compact('demotions'));
        }
        return view('payroll.demotion.index', compact('title', 'section', 'sub_section', 'demotions'));
    }

    public function create()
    {
        $title = 'Add Employee Demotion';
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $designations = Designation::all();
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $section = 'Employee Demotion';
        $section_url = route('demotion.index');
        $sub_section = 'Add';
        return view('payroll.demotion.form', compact('title', 'section', 'sub_section', 'section_url', 'designations', 'employees', 'payScales'));
    }

    public function edit($id)
    {
        $title = 'Edit Demotion Data';
        $section = 'Employee Demotion';
        $section_url = route('demotion.index');
        $designations = Designation::all();
        $employees = Employee::all()->where('status', 'active');
        $payScales = \App\Models\Company\PayScale::with(['grade', 'payGroup'])->where('status', 'active')->get();
        $sub_section = 'Edit';
        $demotionData = Demotion::find($id);
        return view('payroll.demotion.form', compact('title', 'section', 'sub_section', 'section_url', 'demotionData', 'designations', 'employees', 'payScales'));
    }

    public function save(DemotionRequest $request, $demotionData = null)
    {
        $validated = $request->validated();

        try {
            Log::info('Saving demotion request.');
            $result = $this->payrollService->demotionRequestData($request);
            $data = $result['data'];

            if (!empty($demotionData)) {
                $this->payrollService->demotionDataUpdate($demotionData, $data);
                $message = 'Updated Successfully';
            } else {
                $demotion = $this->payrollService->demotionDataStore($data);
                $demotion->startWorkflow('demotion');
                $message = 'Added Successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('demotion.index')
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
        $title = 'Demotion Data';
        $section = 'Employee Demotion';
        $section_url = route('demotion.index');
        $sub_section = 'View';
        $demotionData = Demotion::find($id);
        return view('payroll.demotion.view', compact('title', 'section', 'sub_section', 'section_url', 'demotionData'));
    }

    public function delete($id)
    {
        $demotionData = Demotion::find($id);
        $demotionData->delete();
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
        $data = Demotion::find($id);
        if ($request->status == 'approved') {
            $data->update([
                'status' => $request->status,
                'is_adjustment' => 1
            ]);
        } else {
            $data->update(['status' => $request->status]);
        }

        return redirect()->route('demotion.index')->with([
            'message' => 'Updated Successfully',
        ]);
    }

    public function adjustment()
    {
        $demotions = Demotion::where('is_adjustment', 1)
            ->whereDate('effective_from', '<=', Carbon::today())
            ->get();

        try {
            DB::transaction(function () use ($demotions) {
                foreach ($demotions as $demotion) {
                    $this->payrollService->updateSalaryData($demotion);
                    $this->payrollService->designationUpdate($demotion);
                    $demotion->update(['is_adjustment' => 2]);

                    \App\Models\Employee\EmployeeLifecycle::create([
                        'employee_id' => $demotion->employee_id,
                        'type' => 'demotion',
                        'status_date' => $demotion->effective_from,
                        'description' => 'Demoted to a new designation.'
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('demotion.index')->with([
            'message' => 'Updated Successfully',
            'alert-type' => 'success'
        ]);
    }
}
