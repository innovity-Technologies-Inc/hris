<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeavePlan;
use Illuminate\Http\Request;

class LeavesController extends Controller
{
    public function index(){
        $leaves = Leave::all();

    }
    public function create(){
        $employees = Employee::all();
        $plans = LeavePlan::all();
    }
}
