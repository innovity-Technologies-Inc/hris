<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\BonusPlan;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Payroll\Bonus;
use App\Models\Payroll\PayrollProcess;
use App\Services\PayrollServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BonusController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollServices $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        $title = 'Employee Bonus';
        $section = 'Employee Bonus';
        $sub_section = 'Index';
        $employees = Employee::where('status', 'active')->get();
        $payrollProcesses = PayrollProcess::where('type', 'bonus')->orderBy('created_at', 'desc')->paginate(10);
        return view('payroll.bonus.index', compact('title', 'section', 'sub_section',
            'payrollProcesses', 'employees'));
    }

    public function create()
    {
        $title = 'Add Employee Bonus';
        $section = 'Employee Bonus';
        $section_url = route('bonus.index');
        $sub_section = 'Add';
        $bonusPlans = BonusPlan::where('status', 'active')->get();
        $companies = Company::all();
        return view('payroll.bonus.create', compact('title', 'section',
            'sub_section', 'section_url', 'bonusPlans', 'companies'));
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
        $bonusData = PayrollProcess::find($id);
        return view('payroll.bonus.create', compact('title', 'section',
            'section_url', 'bonusData', 'employees', 'sub_section', 'bonusPlans', 'companies'));
    }

    public function save(Request $request, $id=null){
        $data = $this->payrollService->bonusDataValidation($request);
        try{
            if ($id == null) {
                $this->payrollService->bonusProcess($data);
            }else{
                DB::transaction(function () use ($id, $data) {
                    $this->payrollService->bonusDelete($id);
                    $this->payrollService->bonusProcess($data, $id);
                });
            }
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => $exception->getMessage() == 'Eligible Employees not found.'?  $exception->getMessage() : 'Something went wrong! '
            ]);
        }
        return redirect()->route('bonus.index')->with([
            'alert-type' => 'success',
            'message' => 'Bonus Processed successfully! Wait for approval.'
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
            'bonuses', 'salary_month', 'sub_section'));
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $this->payrollService->bonusDelete($id);
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

        return redirect()->route('bonus.index')->with([
            'message' => 'Updated Successfully',
        ]);
    }

}
