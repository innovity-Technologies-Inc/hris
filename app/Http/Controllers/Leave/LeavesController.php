<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Enums\UserType;
use App\Imports\Employee\EmployeeEligiblePlanImport;
use App\Imports\Leave\LeavesImport;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\EmployeeLeavePlan;
use App\Models\Leave\Leave;
use App\Models\Leave\LeaveCount;
use App\Models\Plan\LeavePlan;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LeavesController extends Controller
{
    public function index(FlexSearch $flexsearch, Request $request){
        $title = 'Leave Logs';
        $section = 'Leave Management';
        $sub_section = 'Logs';
//        dd($request->all());
        $query = Leave::with('getEmployee', 'getPlan');
        $searchableColumns = ['getEmployee.full_name', 'getPlan.name', 'getPlan.leave_type', 'leave_count'];
        $keyword = $request->input('keyword');
        $filters = [
            'from>=' => $request->input('from'),
            'from<=' => $request->input('to'),
        ];


        $leaves = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->paginate(10);
        if($request->ajax()){
            return view('leave.search_results', compact('leaves'))->render();
        }
        return view('leave.index', compact('leaves', 'title', 'section', 'sub_section'));
    }

    public function create(){
        $title = 'Leave Application';
        $section = 'Leave Management';
        $sub_section = 'Application';
        
        $isEmployee = auth()->user()->user_type === UserType::Employee;
        
        if ($isEmployee) {
            $employees = Employee::where('id', auth()->user()->employee_id)->get();
        } else {
            $employees = Employee::where('status', 'active')->orderBy('full_name')->get();
        }
        
        return view('leave.create', compact('employees', 'title', 'section', 'sub_section'));
    }

    public function store(Request $request){
        $isEmployee = auth()->user()->user_type === UserType::Employee;
        
        // Security: Employees can only submit for themselves
        if ($isEmployee && $request->input('employee_id') != auth()->user()->employee_id) {
            abort(403, 'Unauthorized access.');
        }

        $employee_id = $request->input('employee_id');
        $plan_id = $request->input('plan_id');

        Log::info('Requesting leave for '.$employee_id);
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'plan_id' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
            'status' => $isEmployee ? 'nullable' : 'required',
            'reason' => 'required',
            'leave_count' => 'required|integer|min:1'
        ]);

        $validator->after(function ($validator) use ($employee_id, $plan_id, $request) {

            $leave = LeaveCount::where('employee_id', $employee_id)
                ->where('plan_id', $plan_id)
                ->first();

            $max_days = LeavePlan::where('id', $plan_id)->first()->max_no_of_days;

            if ($max_days < $request->input('leave_count')){
                $validator->errors()->add('leave_count', 'You cannot request more than '.$max_days.' days per leave');
            }

            if ($leave) {
                $remaining_leaves = $leave->getPlan->leave_limit - $leave->leave_taken;
                $plan_name = $leave->getPlan->name;

                if ($request->leave_count > $remaining_leaves) {
                    $validator->errors()->add(
                        'leave_count',
                        "You only have {$remaining_leaves} leave(s) remaining for {$plan_name} plan."
                    );
                }
            }
        });

        $validator->validate();

        try {
            DB::transaction(function () use ($request, $employee_id, $plan_id) {
                Log::info('Saving leave request for '.$employee_id);
                Leave::create($request->all());
                if ($request->status == 'approved'){
                    $leave = LeaveCount::where('employee_id', $employee_id)
                        ->where('plan_id', $plan_id)
                        ->first();

                    if ($leave) {
                        Log::info('Updating leave count for '.$employee_id);
                        $leave->increment('leave_taken', $request->leave_count);
                    }else{
                        Log::info('Creating leave count for '.$employee_id);
                        LeaveCount::create([
                            'employee_id' => $employee_id,
                            'plan_id' => $plan_id,
                            'leave_taken' => $request->leave_count
                        ]);
                    }
                }
            });

            return redirect()->back()->with([
                'message' => 'Leave Requested Successfully',
                'alert-type' => 'success'
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }
    }

    public function destroy($id){
        // Restricted for Employees
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $leave_request = Leave::find($id);

        $leave = LeaveCount::where('employee_id', $leave_request->employee_id)
            ->where('plan_id', $leave_request->plan_id)->first();

        DB::transaction(function () use ($leave, $leave_request) {
            if ($leave && $leave_request->status == 'approved'){
                $leave->decrement('leave_taken', $leave_request->leave_count);
            }
            $leave_request->delete();
        });

        return redirect()->back()->with([
            'message' => 'Leave Request Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function changeStatus(Request $request){
        // Restricted for Employees
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $id = $request->input('id');
        $status = $request->input('status');
        $leave_request = Leave::find($id);

        try {
            if($status == 'approved') {
                DB::transaction(function () use ($leave_request) {
                    $leave = LeaveCount::where('employee_id', $leave_request->employee_id)
                        ->where('plan_id', $leave_request->plan_id)->first();

                    if ($leave){
                        $leave->increment('leave_taken', $leave_request->leave_count);
                    }else{
                        LeaveCount::create([
                            'employee_id' => $leave_request->employee_id,
                            'plan_id' => $leave_request->plan_id,
                            'leave_taken' => $leave_request->leave_count
                        ]);
                    }

                    $leave_request->status = 'approved';
                    $leave_request->save();

                });
            }

            if($status == 'rejected') {
                $leave_request->status = 'rejected';
                $leave_request->save();
            }
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Leave Request Status Changed Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function import(Request $request)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        try {
            Excel::import(new LeavesImport(), $request->file('file'));

            return redirect()->back()->with([
                'message' => 'Leave requests imported successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Leave Import Error: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Import failed: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }
    public function showLeaveInfo($id){
        $title = 'Employee Leave Information';
        $section = 'Employees';
        $sub_section = 'Profile - Leave Information';
        $section_url = route('employee.index');
        $employee = Employee::find($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $leaveDetails = EmployeeLeavePlan::with( 'leaveCount')->where('employee_id', $id)->get();
        $leaveHistory = Leave::where('employee_id', $id)->orderBy('id', 'desc')->get();
        return view('employee.profile', compact('title', 'section', 'sub_section', 'employee', 'leaveDetails', 'leaveHistory', 'section_url'));

    }
}

