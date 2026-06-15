<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\PayGroup;
use App\Models\Payroll\PayrollProcess;
use App\Services\Payroll\PayrollServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class ArrearController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        Log::info('Accessing Arrear index.', ['query' => $request->all()]);
        try {
            $data = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexsearch)
                ->where('type', 'arrear')
                ->orderBy('id', 'desc')
                ->paginate(20);

            return view('payroll.arrear.index', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error loading Arrear index.', ['message' => $e->getMessage()]);
            return redirect()->back()->with(['alert-type' => 'error', 'message' => 'Failed to load list.']);
        }
    }

    public function create()
    {
        Log::info('Accessing Arrear creation form.');
        try {
            $companies = Company::all();
            $payGroups = PayGroup::all();
            $generalSettings = \App\HelperClass::getGeneralSetting();
            return view('payroll.arrear.create', compact('companies', 'payGroups', 'generalSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading Arrear create view.', ['message' => $e->getMessage()]);
            return redirect()->back()->with(['alert-type' => 'error', 'message' => 'Failed to load creation form.']);
        }
    }

    public function save(Request $request, $id = null)
    {
        Log::info('Processing Arrear request.', [
            'process_id' => $id,
            'input' => $request->all()
        ]);

        $rules = [
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'pay_group_id' => 'required|exists:pay_groups,id',
            'employee_id' => 'nullable|exists:employees,id',
            'payment_month' => 'required',
            'amount_type' => 'required|in:fixed,percentage',
            'amount_value' => 'required|numeric|min:0.01',
            'percentage_base' => 'required_if:amount_type,percentage|in:basic_salary,gross_salary',
            'reason' => 'nullable|string',
        ];

        // Dynamic validation based on Pay Group frequency
        $payGroup = PayGroup::find($request->pay_group_id);
        if ($payGroup) {
            $frequency = strtolower($payGroup->payroll_frequency);
            if ($frequency === 'monthly') {
                $rules['salary_month'] = 'required';
            } else {
                $rules['start_date'] = 'required|date';
                $rules['end_date'] = 'required|date|after_or_equal:start_date';
            }
        }

        try {
            $validated = $request->validate($rules);
            $this->payrollService->arrearProcess($validated, $id);
            
            Log::info('Arrear processing completed successfully.', ['process_id' => $id]);
            return redirect()->route('arrear.index')->with([
                'alert-type' => 'success',
                'message' => 'Arrear processed successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Critical error in Arrear processing.', [
                'message' => $e->getMessage()
            ]);
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Processing failed: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        Log::info('Accessing Arrear edit form.', ['process_id' => $id]);
        try {
            $arrearData = PayrollProcess::with('arrears.employee')->findOrFail($id);
            if ($arrearData->type !== 'arrear') {
                abort(404);
            }
            
            $firstItem = $arrearData->arrears->first();
            $companies = Company::all();
            $payGroups = PayGroup::all();
            $generalSettings = \App\HelperClass::getGeneralSetting();
            
            return view('payroll.arrear.create', compact('arrearData', 'companies', 'payGroups', 'firstItem', 'generalSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading Arrear edit view.', ['process_id' => $id, 'message' => $e->getMessage()]);
            return redirect()->route('arrear.index')->with(['alert-type' => 'error', 'message' => 'Batch not found.']);
        }
    }

    public function show($id)
    {
        Log::info('Accessing Arrear detail view.', ['process_id' => $id]);
        try {
            $process = PayrollProcess::with([
                'arrears.employee.officeInfo.getCurrentCompany',
                'arrears.employee.officeInfo.getCurrentBusinessUnit',
                'arrears.employee.officeInfo.getCurrentDivision',
                'arrears.employee.officeInfo.getCurrentDepartment',
                'arrears.employee.officeInfo.getCurrentSection',
                'arrears.employee.officeInfo.getCurrentDesignation',
                'getCompany', 
                'getBranch', 
                'getDepartment', 
                'generatedBy'
            ])->findOrFail($id);
            
            if ($process->type !== 'arrear') {
                abort(404);
            }

            return view('payroll.arrear.show', compact('process'));
        } catch (\Exception $e) {
            Log::error('Error loading Arrear details.', ['process_id' => $id, 'message' => $e->getMessage()]);
            return redirect()->route('arrear.index')->with(['alert-type' => 'error', 'message' => 'Details not found.']);
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        try {
            $process = PayrollProcess::findOrFail($id);
            
            DB::transaction(function () use ($process, $request) {
                $process->update([
                    'approval_status' => $request->status,
                    'approved_by' => \Illuminate\Support\Facades\Auth::id(),
                    'approved_at' => now()
                ]);
                
                \App\Models\Payroll\Arrear::where('process_id', $process->id)
                    ->update(['status' => $request->status]);
            });

            return redirect()->route('arrear.index')->with([
                'alert-type' => 'success',
                'message' => 'Status Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Failed to update status: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->payrollService->arrearDelete($id);
            return redirect()->route('arrear.index')->with([
                'alert-type' => 'success',
                'message' => 'Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Deletion failed: ' . $e->getMessage()
            ]);
        }
    }
}
