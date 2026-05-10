<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PayrollProcess;
use App\Services\PayrollServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalaryController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Employee Payroll';
        $section = 'Employee Payroll';
        $sub_section = 'Index';
        $searchResult = $this->payrollService->payrollProcessSearchResult($request,PayrollProcess::class,
            $flexSearch);
        $payrollProcesses = $searchResult->where('type', 'salary')->orderBy('created_at', 'desc')->paginate(20);
        if ($request->ajax()) {
            return view('payroll.salary.partials.search_results', compact('payrollProcesses'));
        }
        return view('payroll.salary.index', compact('title', 'section', 'sub_section',
            'payrollProcesses'));
    }

    public function create()
    {
        $title = 'Add Employee Payroll';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $sub_section = 'Add';
        $companies = Company::all();
        return view('payroll.salary.create', compact('title', 'section',
            'sub_section', 'section_url', 'companies'));
    }

    public function edit($id)
    {
        $title = 'Edit Payroll Data';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $sub_section = 'Edit';
        $companies = Company::all();
        $salaryData = PayrollProcess::find($id);
        return view('payroll.salary.create', compact('title', 'section',
            'section_url', 'salaryData', 'employees', 'sub_section', 'companies'));
    }

    public function save(Request $request, $id=null){
        $data = $this->payrollService->payrollProcessDataValidation($request);
        try{
            if ($id == null) {
                $this->payrollService->salaryProcess($data);
            }else{
                DB::transaction(function () use ($id, $data) {
                    $this->payrollService->salaryDelete($id);
                    $this->payrollService->salaryProcess($data, $id);
                });
            }
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => $exception->getMessage() == 'Eligible Employees not found.'?  $exception->getMessage() : 'Something went wrong! '
            ]);
        }
        return redirect()->route('salary.index')->with([
            'alert-type' => 'success',
            'message' => 'Salary Generated successfully! Wait for approval.'
        ]);
    }

    public function show($id)
    {
        $title = 'Eligible Employees';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $sub_section = 'Eligible Employee';
        $process = PayrollProcess::findOrFail($id);
        $salary_month = $process->salary_month;
        $salaryes = Payroll::where('process_id', $id)->orderBy('created_at', 'desc')->paginate(10);
        return view('payroll.salary.view', compact('title', 'section', 'section_url', 'sub_section',
            'salaryes', 'salary_month'));
    }

    public function showPayroll($id)
    {
        $payroll = Payroll::with(['getEmployee', 'getBatch'])->findOrFail($id);
        $title = 'Payroll Details';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $sub_section = 'Payroll Detail';
        
        return view('payroll.salary.payroll_view', compact('payroll', 'title', 'section', 'section_url', 'sub_section'));
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $this->payrollService->salaryDelete($id);
            $this->payrollService->processDelete($id);
        });
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function statusUpdate(Request $request, $id){
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);
        $process = PayrollProcess::find($id);
        $process->update([
            'approval_status' => $request->status,
        ]);

        return redirect()->route('salary.index')->with([
            'message' => 'Updated Successfully',
        ]);
    }
}
