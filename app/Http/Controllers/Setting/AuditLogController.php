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
        if ($request->ajax()) {
            $query = Activity::with(['causer', 'subject'])->latest();
            
            // Allow searching by exact model/subject class or causer ID if needed, 
            // FlexSearch will handle generic string searches automatically if configured.
            
            $flexSearch = new FlexSearch($query);
            $logs = $flexSearch->paginate(15);
            
            $view = View::make('setting.audit_logs.search_results', compact('logs'))->render();
            return response()->json(['html' => $view]);
        }
        
        $logs = Activity::with(['causer', 'subject'])->latest()->paginate(15);
        return view('setting.audit_logs.index', compact('logs'));
    }
}
