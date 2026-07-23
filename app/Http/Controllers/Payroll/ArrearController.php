<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\PayGroup;
use App\Models\Payroll\PayrollProcess;
use App\Services\Payroll\PayrollServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payroll\Arrear;
use App\Exports\Payroll\ArrearProcessExport;
use App\Exports\Payroll\ArrearDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

use App\Http\Requests\Payroll\StoreArrearRequest;

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

    public function save(StoreArrearRequest $request, $id = null)
    {
        Log::info('Processing Arrear request via StoreArrearRequest.', [
            'process_id' => $id,
            'input' => $request->all()
        ]);

        try {
            $validated = $request->validated();
            $process = $this->payrollService->arrearProcess($validated, $id);
            if (!$id && $process) {
                $process->startWorkflow('arrear');
            }
            
            Log::info('Arrear processing completed successfully.', ['process_id' => $id]);
            return response()->json([
                'success' => true,
                'message' => 'Arrear processed successfully!',
                'redirect_url' => route('arrear.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Critical error in Arrear processing.', [
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Processing failed: ' . $e->getMessage()
            ], 400);
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

    public function exportExcel(Request $request, FlexSearch $flexsearch)
    {
        $searchResult = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexsearch);
        $records = $searchResult->where('type', 'arrear')->orderBy('id', 'desc')->get();

        return Excel::download(new ArrearProcessExport($records), 'arrear_adjustment_processes_' . now()->format('YmdHis') . '.xlsx');
    }

    public function printIndex(Request $request, FlexSearch $flexsearch)
    {
        $searchResult = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexsearch);
        $records = $searchResult->where('type', 'arrear')->orderBy('id', 'desc')->get();

        $title = 'Arrear Adjustment Processes';
        return view('payroll.arrear.print_index', compact('records', 'title'));
    }

    public function exportProcessExcel($id)
    {
        $process = PayrollProcess::findOrFail($id);
        $records = Arrear::where('process_id', $process->id)->with('employee')->orderBy('id', 'desc')->get();

        return Excel::download(new ArrearDetailExport($records), 'arrear_adjustments_' . $process->batch_id . '_' . now()->format('YmdHis') . '.xlsx');
    }

    public function printProcess($id)
    {
        $process = PayrollProcess::findOrFail($id);
        $records = Arrear::where('process_id', $process->id)->with('employee')->orderBy('id', 'desc')->get();

        $title = 'Arrear Adjustments for Batch ' . $process->batch_id;
        $salary_month = $process->salary_month;
        return view('payroll.arrear.print_process', compact('records', 'title', 'process', 'salary_month'));
    }
}
