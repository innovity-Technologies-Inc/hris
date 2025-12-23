<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendancesController extends Controller
{
    protected $attendancesService;
    public function __construct(AttendanceServices $attendancesService){
        $this->attendancesService = $attendancesService;
    }
    public function index(FlexSearch $flexsearch, Request $request){

        $query = Attendance::with('getEmployee');
        $searchableColumns = ['getEmployee.full_name', ];
        $keyword = $request->input('keyword');
        $filters = [];

        $title = 'Employee Attendance';
        $section = 'Attendance';
        $sub_section = 'Records';


        $attendanceRecords = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->paginate(10);
        if($request->ajax()){
            return view('attendance.index', compact('attendanceRecords'))->render();
        }
        return view('attendance.index', compact('attendanceRecords', 'title', 'section', 'sub_section'));
    }

    public function create(){
        $title = 'Employee Attendance';
        $section = 'Employee Attendance';
        $sub_section = 'Register';
        $employees = Employee::has('shift')->get();
        return view('attendance.attendance_form', compact('title', 'section', 'sub_section', 'employees'));
    }
    public function store(Request $request){
        try{
            $this->attendancesService->attendanceStore($request);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error'
            ]);
        }
            return redirect()->route('attendance.index')->with([
                'message' => 'Attendance Created Successfully',
                'alert-type' => 'success'
            ]);
    }
}
