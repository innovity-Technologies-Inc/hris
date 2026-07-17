<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Enums\UserType;
use App\Imports\Employee\EmployeeEligiblePlanImport;
use App\Imports\Leave\LeavesImport;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\EmployeeLeavePlan;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Holiday;
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
            $employees = Employee::where('id', auth()->user()->employee_id)->whereHas('assignedLeavePlans')->get();
        } else {
            $employees = Employee::where('status', 'active')->whereHas('assignedLeavePlans')->orderBy('full_name')->get();
        }
        
        return view('leave.create', compact('employees', 'title', 'section', 'sub_section', 'isEmployee'));
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
            'leave_count' => 'required|numeric|min:0.5',
            'day_type' => 'nullable|in:full_day,half_day'
        ]);

        $validator->after(function ($validator) use ($employee_id, $plan_id, $request) {
            $plan = LeavePlan::find($plan_id);
            if (!$plan) {
                return;
            }

            $max_days = $plan->max_no_of_days;

            if ($max_days < $request->input('leave_count')){
                $validator->errors()->add('leave_count', 'You cannot request more than '.$max_days.' days per leave');
            }

            $currentYear = now()->year;
            $takenThisYear = Leave::where('employee_id', $employee_id)
                ->where('plan_id', $plan_id)
                ->where('status', 'approved')
                ->whereYear('from', $currentYear)
                ->sum('leave_count');

            $remaining_leaves = $plan->leave_limit - $takenThisYear;

            if ($request->leave_count > $remaining_leaves) {
                $validator->errors()->add(
                    'leave_count',
                    "You only have {$remaining_leaves} leave(s) remaining for {$plan->name} plan."
                );
            }
        });

        $validator->validate();

        try {
            DB::transaction(function () use ($request, $employee_id, $plan_id) {
                Log::info('Saving leave request for '.$employee_id);
                $newLeave = Leave::create($request->all());
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
                } else {
                    $newLeave->startWorkflow('leave');
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

    public function show($id)
    {
        $title = 'Leave Data';
        $section = 'Leave Management';
        $section_url = route('leave.index');
        $sub_section = 'View';
        $leaveData = Leave::find($id);
        return view('leave.view', compact('title', 'section', 'sub_section', 'section_url', 'leaveData'));
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

        $leaveDetails = EmployeeLeavePlan::with('leaveCount')->where('employee_id', $id)->get();
        
        $currentYear = now()->year;
        foreach ($leaveDetails as $detail) {
            $detail->taken_current_year = (int) Leave::where('employee_id', $id)
                ->where('plan_id', $detail->plan_id)
                ->where('status', 'approved')
                ->whereYear('from', $currentYear)
                ->sum('leave_count');
        }

        $leaveHistory = Leave::where('employee_id', $id)->orderBy('id', 'desc')->get();
        return view('employee.profile', compact('title', 'section', 'sub_section', 'employee', 'leaveDetails', 'leaveHistory', 'section_url'));

    }

    public function calculateEndDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'plan_id' => 'required',
            'start_date' => 'required|date',
            'leave_count' => 'required|numeric|min:0.5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $employeeId = $request->input('employee_id');
        $planId = $request->input('plan_id');
        $startDateStr = $request->input('start_date');
        $leaveCount = (float) $request->input('leave_count');

        $plan = LeavePlan::find($planId);
        if (!$plan) {
            return response()->json(['error' => 'Leave plan not found'], 404);
        }

        $startDate = Carbon::parse($startDateStr);
        
        if ($plan->off_day_include === 'yes') {
            $daysToAdd = ceil($leaveCount) - 1;
            $endDate = $startDate->copy()->addDays($daysToAdd);
            return response()->json([
                'success' => true,
                'end_date' => $endDate->format('Y-m-d')
            ]);
        }

        // Get employee weekends
        $officeInfo = EmployeeOfficeInfo::where('employee_id', $employeeId)->first();
        $weekends = $officeInfo ? ($officeInfo->weekends ?? []) : ['Friday', 'Saturday'];

        // Get holidays
        $holidaysList = Holiday::all();
        $holidayDates = collect();
        foreach ($holidaysList as $holiday) {
            $period = \Carbon\CarbonPeriod::create(
                Carbon::parse($holiday->start_date),
                Carbon::parse($holiday->end_date)
            );
            foreach ($period as $date) {
                $holidayDates->push($date->format('Y-m-d'));
            }
        }
        $holidayDates = $holidayDates->unique();

        $validDaysNeeded = ceil($leaveCount);
        $validDaysCount = 0;
        $currentDate = $startDate->copy();
        
        // Loop safety limit of 365 iterations
        for ($i = 0; $i < 365; $i++) {
            $currentDayOfWeek = $currentDate->format('l');
            $currentDateStr = $currentDate->format('Y-m-d');

            $isWeekend = in_array($currentDayOfWeek, $weekends);
            $isHoliday = $holidayDates->contains($currentDateStr);

            if (!$isWeekend && !$isHoliday) {
                $validDaysCount++;
            }

            if ($validDaysCount >= $validDaysNeeded) {
                break;
            }

            $currentDate->addDay();
        }

        return response()->json([
            'success' => true,
            'end_date' => $currentDate->format('Y-m-d')
        ]);
    }
}

