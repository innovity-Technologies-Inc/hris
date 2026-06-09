<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Support\Facades\View;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject' => function($morphTo) {
            $morphTo->morphWith([
                \App\Models\Employee\EmployeeSalaryBreakdown::class => ['getEmployee'],
                \App\Models\Employee\EmployeeOfficeInfo::class => ['employee'],
                \App\Models\Employee\EmployeeEligiblePlan::class => ['employee'],
                \App\Models\Employee\EmployeeBankAccount::class => ['getEmployee'],
                \App\Models\Employee\EmployeeNominee::class => ['employee'],
            ]);
        }])->latest();

        if ($request->ajax()) {
            $flexSearch = new FlexSearch($query);
            $logs = $flexSearch->paginate(15);
            
            $view = View::make('setting.audit_logs.search_results', compact('logs'))->render();
            return response()->json(['html' => $view]);
        }
        
        $logs = $query->paginate(15);
        return view('setting.audit_logs.index', compact('logs'));
    }
}
