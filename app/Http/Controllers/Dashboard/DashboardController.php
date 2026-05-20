<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Section;
use App\Models\Company\Division;
use App\Models\Employee\Employee;

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

        return view('dashboard.index', compact('title', 'stats'));
    }
}
