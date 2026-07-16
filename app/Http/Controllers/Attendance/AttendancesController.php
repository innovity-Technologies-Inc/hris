<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Employee\Employee;
use App\Services\Attendance\AttendanceServices;
use App\Imports\Attendance\AttendanceImport;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AttendancesController extends Controller
{
    protected $attendancesService;
    public function __construct(AttendanceServices $attendancesService){
        $this->attendancesService = $attendancesService;
    }
    public function index(FlexSearch $flexsearch, Request $request)
    {
        $query = Attendance::with('getEmployee');

        $searchableColumns = ['getEmployee.full_name'];
        $keyword = $request->input('keyword');

        $filters = [];

        if ($request->filled('from')) {
            $filters['in_time>='] = Carbon::parse($request->input('from'))->copy()->startOfDay();
        }

        if ($request->filled('to')) {
            $filters['in_time<='] = Carbon::parse($request->input('to'))->copy()->endOfDay();
        }

        $title = 'Employee Attendance';
        $section = 'Attendance';
        $sub_section = 'Records';

        $attendanceRecords = $flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);

        if ($request->ajax()) {
            return view('attendance.partials.search_results', compact('attendanceRecords'))->render();
        }

        return view('attendance.index', compact(
            'attendanceRecords',
            'title',
            'section',
            'sub_section'
        ));
    }


    public function create(){
        $title = 'Employee Attendance';
        $section = 'Employee Attendance';
        $sub_section = 'Register';
        $employees = Employee::has('shift')->get();
        return view('attendance.attendance_form', compact('title', 'section', 'sub_section', 'employees'));
    }

    public function clock_in_out(){
        $title = 'Employee Attendance';
        $section = 'Employee Attendance';
        $sub_section = 'Register';
        
        $loggedInEmployeeId = auth()->user()->employee_id;
        if ($loggedInEmployeeId) {
            $employees = Employee::where('id', $loggedInEmployeeId)
                ->orWhereHas('shift')
                ->get();
        } else {
            $employees = Employee::has('shift')->get();
        }

        return view('attendance.clock_in_out', compact('title', 'section', 'sub_section', 'employees'));
    }


    public function bulkUpload(){
        $title = 'Bulk Attendance Upload';
        $section = 'Employee Attendance';
        $sub_section = 'Bulk Upload';
        return view('attendance.bulk_upload', compact('title', 'section', 'sub_section'));
    }
    public function store(Request $request){


            $this->attendancesService->attendanceStore($request);

            return redirect()->route('attendance.index')->with([
                'message' => 'Attendance Created Successfully',
                'alert-type' => 'success'
            ]);
    }

    public function clockInOutStore(Request $request)
    {
        try {
            $isClockIn = $request->filled('in_time') && !$request->filled('out_time');
            $isClockOut = $request->filled('out_time');

            if ($isClockIn) {
                $validated = $request->validate([
                    'employee_id' => 'required|exists:employees,id',
                    'in_time' => 'required|date',
                    'workstation' => 'required|string',
                ]);

                $attendance = Attendance::create($validated);

                return response()->json([
                    'status' => 'clocked_in',
                    'message' => 'Clocked In Successfully',
                    'attendance_id' => $attendance->id,
                    'in_time' => $attendance->in_time
                ]);
            }

            if ($isClockOut) {
                $request->validate([
                    'employee_id' => 'required|exists:employees,id',
                    'out_time' => 'required|date',
                ]);

                $this->attendancesService->clockOutStore($request);

                return response()->json([
                    'status' => 'clocked_out',
                    'message' => 'Clocked Out Successfully'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid attendance request'
            ], 422);

        } catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);

        try{
            Excel::import(new AttendanceImport($this->attendancesService), $request->file('file'));
            return redirect()->route('attendance.index')->with([
                'message' => 'Attendance Imported Successfully',
                'alert-type' => 'success'
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    public function printIndex(Request $request){
        $query = Attendance::with('getEmployee');
        $attendanceRecords = $query->get();
        return view('attendance.print_index', compact('attendanceRecords'));
    }

    public function printDetail($id){
        $record = Attendance::with('getEmployee', 'getShift')->findOrFail($id);
        return view('attendance.print_detail', compact('record'));
    }

    public function edit($id)
    {
        $title = 'Edit Attendance';
        $section = 'Attendance';
        $sub_section = 'Edit';
        $record = Attendance::findOrFail($id);
        $employees = Employee::has('shift')->get();
        return view('attendance.attendance_form', compact('title', 'section', 'sub_section', 'record', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'clock_in' => 'required|date',
            'clock_out' => 'required|date|after:clock_in',
            'workstation' => 'required|string',
        ]);

        try {
            $this->attendancesService->attendanceUpdate($id, $validated);
            return redirect()->route('attendance.index')->with([
                'message' => 'Attendance Updated Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Error updating attendance: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }
}

