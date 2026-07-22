<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\PayGroup;
use App\Models\Payroll\PayrollProcess;
use App\Services\Payroll\PayrollServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use App\Models\Payroll\AdvanceSalary;
use App\Exports\Payroll\AdvanceSalaryProcessExport;
use App\Exports\Payroll\AdvanceSalaryDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class AdvanceSalaryController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        Log::info('Accessing Advance Salary index.', ['query' => $request->all()]);
        try {
            $data = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexsearch)
                ->where('type', 'advance')
                ->orderBy('id', 'desc')
                ->paginate(20);

            return view('payroll.advance_salary.index', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error loading Advance Salary index.', ['message' => $e->getMessage()]);
            return redirect()->back()->with(['alert-type' => 'error', 'message' => 'Failed to load list.']);
        }
    }

    public function create()
    {
        Log::info('Accessing Advance Salary creation form.');
        try {
            $companies = Company::all();
            $payGroups = PayGroup::all();
            $generalSettings = \App\HelperClass::getGeneralSetting();
            return view('payroll.advance_salary.create', compact('companies', 'payGroups', 'generalSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading Advance Salary create view.', ['message' => $e->getMessage()]);
            return redirect()->back()->with(['alert-type' => 'error', 'message' => 'Failed to load creation form.']);
        }
    }

    public function save(Request $request, $id = null)
    {
        Log::info('Processing Advance Salary request.', [
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
            'deduction_month' => 'required',
            'amount_type' => 'required|in:fixed,percentage',
            'amount_value' => 'required|numeric|min:0.01',
            'percentage_base' => 'required_if:amount_type,percentage|in:basic_salary,gross_salary',
            'reason' => 'nullable|string',
        ];

        // Dynamic validation based on Pay Group frequency
        $payGroup = PayGroup::find($request->pay_group_id);
        if ($payGroup) {
            $frequency = strtolower($payGroup->payroll_frequency);
            Log::info('Determined Pay Group frequency for Advance.', ['frequency' => $frequency]);
            
            if ($frequency === 'monthly') {
                $rules['salary_month'] = 'required';
            } else {
                $rules['start_date'] = 'required|date';
                $rules['end_date'] = 'required|date|after_or_equal:start_date';
            }
        }

        try {
            $validated = $request->validate($rules);
            Log::info('Advance Salary validation successful.', ['validated_data' => $validated]);
            
            $process = $this->payrollService->advanceProcess($validated, $id);
            if (!$id && $process) {
                $process->startWorkflow('advance-salary');
            }
            
            Log::info('Advance Salary processing completed successfully.', ['process_id' => $id]);
            return redirect()->route('advance-salary.index')->with([
                'alert-type' => 'success',
                'message' => 'Advance Salary processed successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Advance Salary validation failed.', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Critical error in Advance Salary processing.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Processing failed: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        Log::info('Accessing Advance Salary edit form.', ['process_id' => $id]);
        try {
            $advanceData = PayrollProcess::with('advanceSalaries')->findOrFail($id);
            if ($advanceData->type !== 'advance') {
                Log::warning('Unauthorized access attempt to non-advance process via Advance Controller.', ['process_id' => $id, 'actual_type' => $advanceData->type]);
                abort(404);
            }
            
            $firstItem = $advanceData->advanceSalaries->first();
            $companies = Company::all();
            $payGroups = PayGroup::all();
            $generalSettings = \App\HelperClass::getGeneralSetting();
            
            return view('payroll.advance_salary.create', compact('advanceData', 'companies', 'payGroups', 'firstItem', 'generalSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading Advance Salary edit view.', ['process_id' => $id, 'message' => $e->getMessage()]);
            return redirect()->route('advance-salary.index')->with(['alert-type' => 'error', 'message' => 'Batch not found.']);
        }
    }

    public function show($id)
    {
        Log::info('Accessing Advance Salary detail view.', ['process_id' => $id]);
        try {
            $process = PayrollProcess::with([
                'advanceSalaries.employee.officeInfo.getCurrentCompany',
                'advanceSalaries.employee.officeInfo.getCurrentBusinessUnit',
                'advanceSalaries.employee.officeInfo.getCurrentDivision',
                'advanceSalaries.employee.officeInfo.getCurrentDepartment',
                'advanceSalaries.employee.officeInfo.getCurrentSection',
                'advanceSalaries.employee.officeInfo.getCurrentDesignation',
                'getCompany', 
                'getBranch', 
                'getDepartment', 
                'generatedBy'
            ])->findOrFail($id);
            
            if ($process->type !== 'advance') {
                abort(404);
            }

            return view('payroll.advance_salary.show', compact('process'));
        } catch (\Exception $e) {
            Log::error('Error loading Advance Salary details.', ['process_id' => $id, 'message' => $e->getMessage()]);
            return redirect()->route('advance-salary.index')->with(['alert-type' => 'error', 'message' => 'Details not found.']);
        }
    }



    public function destroy($id)
    {
        Log::info('Request to delete Advance Salary process.', ['process_id' => $id]);
        try {
            $this->payrollService->advanceDelete($id);
            Log::info('Advance Salary process deleted successfully.', ['process_id' => $id]);
            return redirect()->route('advance-salary.index')->with([
                'alert-type' => 'success',
                'message' => 'Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Deletion of Advance Salary process failed.', [
                'process_id' => $id,
                'message' => $e->getMessage()
            ]);
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Deletion failed: ' . $e->getMessage()
            ]);
        }
    }

    public function exportExcel(Request $request, FlexSearch $flexsearch)
    {
        $searchResult = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexsearch);
        $records = $searchResult->where('type', 'advance')->orderBy('id', 'desc')->get();

        return Excel::download(new AdvanceSalaryProcessExport($records), 'advance_salary_processes_' . now()->format('YmdHis') . '.xlsx');
    }

    public function printIndex(Request $request, FlexSearch $flexsearch)
    {
        $searchResult = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexsearch);
        $records = $searchResult->where('type', 'advance')->orderBy('id', 'desc')->get();

        $title = 'Advance Salary Processes';
        return view('payroll.advance_salary.print_index', compact('records', 'title'));
    }

    public function exportProcessExcel($id)
    {
        $process = PayrollProcess::findOrFail($id);
        $records = AdvanceSalary::where('process_id', $process->id)->with('employee')->orderBy('id', 'desc')->get();

        return Excel::download(new AdvanceSalaryDetailExport($records), 'advance_salary_allocations_' . $process->batch_id . '_' . now()->format('YmdHis') . '.xlsx');
    }

    public function printProcess($id)
    {
        $process = PayrollProcess::findOrFail($id);
        $records = AdvanceSalary::where('process_id', $process->id)->with('employee')->orderBy('id', 'desc')->get();

        $title = 'Advance Salary Allocations for Batch ' . $process->batch_id;
        $salary_month = $process->salary_month;
        return view('payroll.advance_salary.print_process', compact('records', 'title', 'process', 'salary_month'));
    }
}
