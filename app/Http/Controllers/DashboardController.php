<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Department;
use App\Models\Section;
use App\Models\Division;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index(){
        $title = 'Dashboard';

        // Fetch statistics
        $stats = [
            'companies' => Company::count(),
            'business_units' => CompanyLocation::count(),
            'departments' => Department::count(),
            'sections' => Section::count(),
            'divisions' => Division::count(),
            'employees' => Employee::count(),
        ];

        return view('dashboard', compact('title', 'stats'));
    }
}
