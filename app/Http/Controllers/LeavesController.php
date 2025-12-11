<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeEligiblePlanImport;
use App\Models\Employee;
use App\Models\EmployeeEligiblePlan;
use App\Models\EmployeeLeavePlan;
use App\Models\Leave;
use App\Models\LeaveCount;
use App\Models\LeavePlan;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeavesController extends Controller
{
    public function index(FlexSearch $flexsearch, Request $request){
//        dd($request->all());
        $query = Leave::with('getEmployee', 'getPlan');
        $searchableColumns = ['getEmployee.full_name', 'getPlan.name', 'getPlan.leave_type', 'leave_count'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('from')) {
            $filters['from<='] = $request->input('from');
        }

        if ($request->filled('to')) {
            $filters['to>='] = $request->input('to');
        }

        $leaves = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->paginate(10);
        if($request->ajax()){
            return view('leaves.search_results', compact('leaves'))->render();
        }
        return view('leaves.index', compact('leaves'));
    }

    public function create(){
        $employees = Employee::all();
        return view('leaves.create', compact('employees'));
    }

    public function getLeavePlan($employee_id){
        $eligibility = EmployeeEligiblePlan::where('employee_id', $employee_id)->first();
        if (isset($eligibility)){
            if ($eligibility->leave_plan_status == 'active'){
                if ($eligibility->leave_plan_from >= Carbon::today()){
                    $plans = EmployeeLeavePlan::with('getPlan')
                        ->where('employee_id', $employee_id)->get();
                    return response()->json($plans);
                    }
                return response()->json(['error' => 'Leave Plan is not active yet']);
            }
            return response()->json(['error' => 'Leave Plan is not active yet']);
        }
        return response()->json(['error' => 'No Plans Found']);
    }

    public function getLeaveDetails($employee_id, $plan_id){
        $plan_name = LeavePlan::where('id', $plan_id)->first()->name;
        $limit = LeavePlan::where('id', $plan_id)->first()->leave_limit;
        $leave_count_data = LeaveCount::where('employee_id', $employee_id)->where('plan_id', $plan_id)->first();
        if ($leave_count_data){
            $taken = $leave_count_data->leave_taken;
        }else{
            $taken = 0;
        }
        return response()->json([
            'name' => $plan_name,
            'limit' => $limit,
            'taken' => $taken
        ]);
    }

    public function store(Request $request){
        $employee_id = $request->input('employee_id');
        $plan_id = $request->input('plan_id');

        Log::info('Requesting leave for '.$employee_id);
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'plan_id' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
            'status' => 'required',
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

    public function showLeaveInfo($id){
        $title = 'Employee Leave Information';
        $section = 'Employees';
        $sub_section = 'Profile - Leave Information';
        $section_url = route('employees.index');
        $employee = Employee::find($id);
        $leaveDetails = EmployeeLeavePlan::with( 'leaveCount')->where('employee_id', $id)->get();
        $leaveHistory = Leave::where('employee_id', $id)->orderBy('id', 'desc')->get();
        return view('employees.profile', compact('title', 'section', 'sub_section', 'employee', 'leaveDetails', 'leaveHistory', 'section_url'));

    }
}
