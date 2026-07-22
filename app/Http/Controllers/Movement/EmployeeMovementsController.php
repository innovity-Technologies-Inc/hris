<?php

namespace App\Http\Controllers\Movement;

use App\Enums\UserType;
use App\Exports\Movement\MovementExport;
use App\Http\Controllers\Controller;
use App\Models\Plan\DAPlan;
use App\Models\Employee\Employee;
use App\Models\Movement\EmployeeMovement;
use App\Models\Plan\TAPlan;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeMovementsController extends Controller
{

    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Employee Travel Movement';
        $section = 'Travel Movement';
        $query = EmployeeMovement::with('getEmployee');

        $searchableColumns = ['getEmployee.full_name'];
        $keyword = $request->input('keyword');

        $filters = [];

        if ($request->filled('from')) {
            $filters['from_date>='] = Carbon::parse($request->input('from'))->copy()->startOfDay();
        }

        if ($request->filled('to')) {
            $filters['from_date<='] = Carbon::parse($request->input('to'))->copy()->endOfDay();
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }

        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $movements = $flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);
        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('movement.partials.search_results', compact('movements'))->render();
        }
        return view('movement.index', compact('title', 'movements', 'section'));
    }

    public function form($id = null)
    {
        $title = (!empty($id) ? 'Edit' : 'Add') . ' Employee Travel Movement Information';
        $section = 'Travel Movement';
        $sub_section = !empty($id) ? 'Edit' : 'Add';
        $section_url = route('movement.index');
        
        $isEmployee = auth()->user()->user_type === UserType::Employee;
        
        if ($isEmployee) {
            $employees = Employee::where('id', auth()->user()->employee_id)->get();
        } else {
            $employees = Employee::where('status', 'active')->orderBy('full_name')->get();
        }

        $taPlans = TAPlan::where('status', 'active')->get();
        $daPlans = DAPlan::where('status', 'active')->get();
        $statusOptions = [
            ['value'=>'pending', 'label'=>'Pending'],
            ['value'=>'approved', 'label'=>'Approved'],
            ['value'=>'rejected', 'label'=>'Rejected'],
        ];

        if (!empty($id)){
            $movement = EmployeeMovement::findorFail($id);
            // Security: Employees can only edit their own movements
            if ($isEmployee && $movement->employee_id != auth()->user()->employee_id) {
                abort(403, 'Unauthorized access.');
            }
            return view('movement.form', compact(
                'employees', 'taPlans', 'daPlans', 'statusOptions', 'title', 'section', 'sub_section', 'section_url'
            , 'movement', 'isEmployee'));
        }else{
            return view('movement.form', compact(
                'employees', 'taPlans', 'daPlans', 'statusOptions', 'title', 'section', 'sub_section', 'section_url', 'isEmployee'
            ));
        }
    }

    public function update($id)
    {
        $title = 'Edit Employee Travel Movement Information';
        $section = 'Travel Movement';
        $sub_section = 'Edit';
        $section_url = route('movement.index');
        $employees = Employee::all();
        $taPlans = TAPlan::where('status', 'active')->get();
        $daPlans = DAPlan::where('status', 'active')->get();
        $statusOptions = [
            ['value'=>'pending', 'label'=>'Pending'],
            ['value'=>'approved', 'label'=>'Approved'],
            ['value'=>'rejected', 'label'=>'Rejected'],
        ];


    }

    public function save(Request $request, $id=null){
        $isEmployee = auth()->user()->user_type === UserType::Employee;

        // Security: Employees can only submit for themselves
        if ($isEmployee && $request->input('employee_id') != auth()->user()->employee_id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([

                'employee_id' => ['required', 'exists:employees,id'],

                'from_date' => ['required', 'date'],
                'to_date'   => ['required', 'date', 'after_or_equal:from_date'],

                'source_address' => ['required', 'string', 'max:255'],
                'source_lat'     => ['required', 'numeric', 'between:-90,90'],
                'source_lng'     => ['required', 'numeric', 'between:-180,180'],

                'destination_address' => ['required', 'string', 'max:255'],
                'dest_lat'            => ['required', 'numeric', 'between:-90,90'],
                'dest_lng'            => ['required', 'numeric', 'between:-180,180'],

                'distance' => ['required', 'numeric', 'min:0'],

                'ta_plan_id' => ['nullable', 'exists:ta_plans,id'],
                'da_plan_id' => ['nullable', 'exists:da_plans,id'],

                'reason' => ['nullable', 'string', 'max:1000'],
                'total_days' => ['required', 'numeric'],
                'total_ta' => ['nullable', 'numeric'],
            'total_da' => ['nullable', 'numeric'],
            'total_allowance' => ['nullable', 'numeric'],

                'status' => ['required', 'in:pending,approved,rejected'],
            ],

            [
                'employee_id.required' => 'Please select an employee.',
                'employee_id.exists'   => 'Selected employee is invalid.',

                'from_date.required' => 'From date and time is required.',
                'from_date.date'     => 'From date must be a valid date.',
                'to_date.required'   => 'To date and time is required.',
                'to_date.date'       => 'To date must be a valid date.',
                'to_date.after_or_equal' => 'To date must be later than or equal to From date.',

                'source_address.required' => 'Source address is required.',
                'source_lat.required'     => 'Please select a valid source location from the map.',
                'source_lng.required'     => 'Source longitude is missing.',
                'source_lat.numeric'      => 'Source latitude must be numeric.',
                'source_lng.numeric'      => 'Source longitude must be numeric.',

                'destination_address.required' => 'Destination address is required.',
                'dest_lat.required'            => 'Please select a valid destination location from the map.',
                'dest_lng.required'            => 'Destination longitude is missing.',
                'dest_lat.numeric'             => 'Destination latitude must be numeric.',
                'dest_lng.numeric'             => 'Destination longitude must be numeric.',

                'distance.required' => 'Distance must be calculated before submitting.',
                'distance.numeric'  => 'Distance must be a valid number.',
                'distance.min'      => 'Distance cannot be negative.',

                'ta_plan_id.required' => 'Please select a TA plan.',
                'ta_plan_id.exists'   => 'Selected TA plan is invalid.',
                'da_plan_id.required' => 'Please select a DA plan.',
                'da_plan_id.exists'   => 'Selected DA plan is invalid.',

                'reason.required' => 'Please provide a reason for the movement.',
                'reason.max'      => 'Reason cannot exceed 1000 characters.',
                'status.required' => 'Please select a status.',
                'status.in'       => 'Selected status is invalid.',
                'total_days.required' => 'Total days must be calculated before submitting.',
                'total_days.numeric'  => 'Total days must be a valid number.',
                'total_allowance.numeric'  => 'Total allowance must be a valid number.',
                'total_ta.numeric'  => 'Total TA must be a valid number.',
                'total_da.numeric'  => 'Total DA must be a valid number.',
            ]
        );

        try{
            if ($id){
                $movement = EmployeeMovement::findOrFail($id);
                $movement->update($validated);
                return redirect()->route('movement.index')->with([
                    'message' => 'Updated successfully.',
                    'alert-type' => 'success'
                ]);
            }else{
                EmployeeMovement::create($validated);
                return redirect()->route('movement.index')->with([
                    'message' => 'Created successfully.',
                    'alert-type' => 'success'
                ]);
            }
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }
    }

    public function destroy($id){
        $movement = EmployeeMovement::find($id);
        $movement->delete();

        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function changeStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        
        try {
            $movement = EmployeeMovement::findOrFail($id);
            $movement->status = $status;
            $movement->save();
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Status Changed Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function changePaymentStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('payment_status');
        
        try {
            $movement = EmployeeMovement::findOrFail($id);
            $movement->payment_status = $status;
            $movement->save();
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Payment Status Changed Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Export movements to Excel, respecting active filters.
     */
    public function exportExcel(Request $request, FlexSearch $flexsearch): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $query = EmployeeMovement::with(['getEmployee', 'getTaPlan', 'getDaPlan']);
        $searchableColumns = ['getEmployee.full_name'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('from')) {
            $filters['from_date>='] = Carbon::parse($request->input('from'))->copy()->startOfDay();
        }
        if ($request->filled('to')) {
            $filters['from_date<='] = Carbon::parse($request->input('to'))->copy()->endOfDay();
        }
        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }
        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->get();

        return Excel::download(new MovementExport($records), 'travel_movements_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Open a printable PDF-style view of movement records.
     */
    public function printIndex(Request $request, FlexSearch $flexsearch): \Illuminate\View\View
    {
        $query = EmployeeMovement::with(['getEmployee', 'getTaPlan', 'getDaPlan']);
        $searchableColumns = ['getEmployee.full_name'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('from')) {
            $filters['from_date>='] = Carbon::parse($request->input('from'))->copy()->startOfDay();
        }
        if ($request->filled('to')) {
            $filters['from_date<='] = Carbon::parse($request->input('to'))->copy()->endOfDay();
        }
        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }
        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->get();

        return view('movement.print_index', compact('records'));
    }
}
