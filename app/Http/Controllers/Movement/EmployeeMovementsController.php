<?php

namespace App\Http\Controllers\Movement;

use App\Enums\UserType;
use App\Exports\Movement\MovementExport;
use App\Http\Controllers\Controller;
use App\Models\Plan\DAPlan;
use App\Models\Employee\Employee;
use App\Models\Movement\EmployeeMovement;
use App\Models\Plan\TAPlan;
use App\Http\Requests\Movement\StoreTravelMovementRequest;
use App\Http\Requests\Movement\UpdateTravelMovementRequest;
use App\Services\Movement\EmployeeMovementServices;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeMovementsController extends Controller
{
    protected $movementServices;

    public function __construct(EmployeeMovementServices $movementServices)
    {
        $this->movementServices = $movementServices;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Employee Travel Movement';
        $section = 'Travel Movement';
        $query = EmployeeMovement::with(['getEmployee', 'details'])->latest();

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

        $taPlans = TAPlan::where('status', 'active')->get();
        $daPlans = DAPlan::where('status', 'active')->get();

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('movement.partials.search_results', compact('movements', 'taPlans', 'daPlans'))->render();
        }
        return view('movement.index', compact('title', 'movements', 'section', 'taPlans', 'daPlans'));
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
            $movement = EmployeeMovement::with('details')->findOrFail($id);
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

    public function store(StoreTravelMovementRequest $request)
    {
        $isEmployee = auth()->user()->user_type === UserType::Employee;
        if ($isEmployee && $request->input('employee_id') != auth()->user()->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        try {
            $movement = $this->movementServices->saveMovement($request->validated(), $request);
            return response()->json([
                'success' => true,
                'message' => 'Resource created successfully.',
                'data' => $movement
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error storing travel movement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function update(UpdateTravelMovementRequest $request, $id)
    {
        $isEmployee = auth()->user()->user_type === UserType::Employee;
        if ($isEmployee && $request->input('employee_id') != auth()->user()->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        try {
            $movement = $this->movementServices->saveMovement($request->validated(), $request, $id);
            return response()->json([
                'success' => true,
                'message' => 'Resource updated successfully.',
                'data' => $movement
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating travel movement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->movementServices->deleteMovement($id);
            return response()->json([
                'success' => true,
                'message' => 'Resource deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting travel movement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        
        try {
            $movement = EmployeeMovement::findOrFail($id);
            $movement->status = $status;
            $movement->save();

            return response()->json([
                'success' => true,
                'message' => 'Status Changed Successfully',
                'data' => $movement
            ], 200);
        }catch (\Exception $e){
            Log::error('Error changing movement status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function changePaymentStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('payment_status');
        
        try {
            $movement = EmployeeMovement::findOrFail($id);
            $movement->payment_status = $status;
            $movement->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment Status Changed Successfully',
                'data' => $movement
            ], 200);
        }catch (\Exception $e){
            Log::error('Error changing movement payment status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function saveAllowances(Request $request, $id)
    {
        // Only HR/Admin can save/edit allowances
        if (auth()->user()->user_type === UserType::Employee) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $validated = $request->validate([
            'ta_plan_id' => ['nullable', 'exists:ta_plans,id'],
            'da_plan_id' => ['nullable', 'exists:da_plans,id'],
            'total_ta' => ['required', 'numeric', 'min:0'],
            'total_da' => ['required', 'numeric', 'min:0'],
            'total_allowance' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $movement = $this->movementServices->saveAllowances($validated, $id);
            return response()->json([
                'success' => true,
                'message' => 'Allowances updated successfully.',
                'data' => $movement
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error saving allowances: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function exportExcel(Request $request, FlexSearch $flexsearch): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $query = EmployeeMovement::with(['getEmployee', 'getTaPlan', 'getDaPlan', 'details'])->latest();
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

    public function printIndex(Request $request, FlexSearch $flexsearch): \Illuminate\View\View
    {
        $query = EmployeeMovement::with(['getEmployee', 'getTaPlan', 'getDaPlan', 'details'])->latest();
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
