<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    protected $attendancesService;
    public function __construct(AttendanceServices $attendancesService){
        $this->attendancesService = $attendancesService;
    }
    public function index(FlexSearch $flexsearch, Request $request){
        $title = 'Employee Attendance';
        $section = 'Attendance';
        $sub_section = 'Records';
        $query = Attendance::with('getEmployee');
        $searchableColumns = ['getEmployee.full_name', ];
        $keyword = $request->input('keyword');
        $filters = [];

        $attendance = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->paginate(10);
        if($request->ajax()){
            return view('attendance.search_results', compact('attendance'))->render();
        }
        return view('attendance.index', compact('attendance', 'title', 'section', 'sub_section'));
    }

    public function create(){
        $title = 'Employee Attendance';
        $section = 'Employee Attendance';
        $sub_section = 'Register';
        $employees = Employee::has('shift')->get();
        return view('attendance.attendance_form', compact('title', 'section', 'sub_section', 'employees'));
    }
    public function store(Request $request){
        $result = $this->attendancesService->attendanceStore($request);
    }



}
