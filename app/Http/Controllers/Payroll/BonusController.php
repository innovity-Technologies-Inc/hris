<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Plan\BonusPlan;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Payroll\Bonus;
use App\Models\Payroll\PayrollProcess;
use App\Services\Payroll\PayrollServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\Payroll\BonusProcessExport;
use App\Exports\Payroll\BonusDetailExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Requests\Payroll\StoreBonusRequest;

class BonusController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Employee Bonus';
        $section = 'Employee Bonus';
        $sub_section = 'Index';
        $searchResult = $this->payrollService->payrollProcessSearchResult($request,PayrollProcess::class,
            $flexSearch);
        $payrollProcesses = $searchResult->where('type', 'bonus')->orderBy('created_at', 'desc')->paginate(20);
        if ($request->ajax()) {
            return view('payroll.bonus.partials.search_results', compact('payrollProcesses'));
        }
        return view('payroll.bonus.index', compact('title', 'section', 'sub_section',
            'payrollProcesses'));
    }

    public function create()
    {
        $title = 'Add Employee Bonus';
        $section = 'Employee Bonus';
        $section_url = route('bonus.index');
        $sub_section = 'Add';
        $bonusPlans = BonusPlan::where('status', 'active')->get();
        $companies = Company::all();
        $payGroups = \App\Models\Company\PayGroup::where('status', 'active')->get();
        return view('payroll.bonus.create', compact('title', 'section',
            'sub_section', 'section_url', 'bonusPlans', 'companies', 'payGroups'));
    }

    public function edit($id)
    {
        $title = 'Edit Bonus Data';
        $section = 'Employee Bonus';
        $section_url = route('bonus.index');
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $sub_section = 'Edit';
        $bonusPlans = BonusPlan::where('status', 'active')->get();
        $companies = Company::all();
        $payGroups = \App\Models\Company\PayGroup::where('status', 'active')->get();
        $bonusData = PayrollProcess::find($id);
        return view('payroll.bonus.create', compact('title', 'section',
            'section_url', 'bonusData', 'employees', 'sub_section', 'bonusPlans', 'companies', 'payGroups'));
    }

    public function save(StoreBonusRequest $request, $id=null){
        $data = $this->payrollService->payrollProcessDataValidation($request, $flag='bonus');
        try{
            if ($id == null) {
                $process = $this->payrollService->bonusProcess($data);
                $process->startWorkflow('bonus');
            }else{
                $process = $this->payrollService->bonusProcess($data, $id);
                $process->startWorkflow('bonus');
            }
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage() == 'Eligible Employees not found.' ? $exception->getMessage() : 'Something went wrong!'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Bonus Processed successfully! Wait for approval.',
            'redirect_url' => route('bonus.index')
        ]);
    }

    public function show($id)
    {
        $title = 'Eligible Employees';
        $section = 'Employee Bonus';
        $section_url = route('bonus.index');
        $sub_section = 'Eligible Employee';
        $process = PayrollProcess::find($id);
        $salary_month = $process->salary_month;
        $batch_id = $process->batch_id;
        $bonuses = Bonus::where('batch_id', $batch_id)->orderBy('created_at', 'desc')->paginate(10);
        return view('payroll.bonus.view', compact('title', 'section', 'section_url', 'sub_section',
            'bonuses', 'salary_month', 'sub_section', 'process'));
    }

    public function individualBonusView($id)
    {
        $bonus = Bonus::with(['getEmployee', 'getBatch'])->findOrFail($id);
        $title = 'Bonus Details';
        $section = 'Employee Bonus';
        $section_url = route('bonus.index');
        $sub_section = 'Bonus Detail';
        
        // Get the plans from the batch
        $planIds = $bonus->getBatch->bonus_plan_ids ?? [];
        $bonusPlans = BonusPlan::whereIn('id', $planIds)->get();

        return view('payroll.bonus.individual_view', compact(
            'title', 'section', 'section_url', 'sub_section', 'bonus', 'bonusPlans'
        ));
    }

    public function delete($id)
    {
        $this->payrollService->bonusDelete($id);
        
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function exportExcel(Request $request, FlexSearch $flexSearch)
    {
        $searchResult = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexSearch);
        $records = $searchResult->where('type', 'bonus')->orderBy('created_at', 'desc')->get();

        return Excel::download(new BonusProcessExport($records), 'bonus_and_reward_processes_' . now()->format('YmdHis') . '.xlsx');
    }

    public function printIndex(Request $request, FlexSearch $flexSearch)
    {
        $searchResult = $this->payrollService->payrollProcessSearchResult($request, PayrollProcess::class, $flexSearch);
        $records = $searchResult->where('type', 'bonus')->orderBy('created_at', 'desc')->get();

        $title = 'Bonus and Reward Processes';
        return view('payroll.bonus.print_index', compact('records', 'title'));
    }

    public function exportProcessExcel($id)
    {
        $process = PayrollProcess::findOrFail($id);
        $records = Bonus::where('batch_id', $process->batch_id)->with('getEmployee')->orderBy('created_at', 'desc')->get();

        return Excel::download(new BonusDetailExport($records), 'bonus_eligible_employees_' . $process->batch_id . '_' . now()->format('YmdHis') . '.xlsx');
    }

    public function printProcess($id)
    {
        $process = PayrollProcess::findOrFail($id);
        $records = Bonus::where('batch_id', $process->batch_id)->with('getEmployee')->orderBy('created_at', 'desc')->get();

        $title = 'Eligible Employees for Bonus Batch ' . $process->batch_id;
        $salary_month = $process->salary_month;
        return view('payroll.bonus.print_process', compact('records', 'title', 'process', 'salary_month'));
    }

}

