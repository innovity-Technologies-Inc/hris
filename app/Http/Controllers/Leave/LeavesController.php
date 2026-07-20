<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\CalculateEndDateRequest;
use App\Http\Requests\Leave\ImportLeavesRequest;
use App\Http\Requests\Leave\StoreLeaveRequest;
use App\Services\Leave\LeaveServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeavesController extends Controller
{
    protected LeaveServices $leaveService;

    public function __construct(LeaveServices $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Leave Logs';
        $section = 'Leave Management';
        $sub_section = 'Logs';

        $leaves = $this->leaveService->getLeavesPaginated($flexsearch, $request);

        if ($request->ajax()) {
            return view('leave.search_results', compact('leaves'))->render();
        }

        return view('leave.index', compact('leaves', 'title', 'section', 'sub_section'));
    }

    public function create()
    {
        $title = 'Leave Application';
        $section = 'Leave Management';
        $sub_section = 'Application';

        $formData = $this->leaveService->getCreateFormData(auth()->user());

        return view('leave.create', array_merge($formData, compact('title', 'section', 'sub_section')));
    }

    public function store(StoreLeaveRequest $request)
    {
        try {
            $leave = $this->leaveService->storeLeave($request->validated(), auth()->user());

            if ($request->expectsJson() || $request->ajax()) {
                return $this->createdResponse('Leave Requested Successfully', [
                    'redirect' => route('leave.index'),
                    'leave' => $leave
                ]);
            }

            return redirect()->route('leave.index')->with([
                'message' => 'Leave Requested Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Leave store error: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return $this->errorResponse($e->getMessage(), 400);
            }

            return redirect()->back()->with([
                'message' => $e->getMessage() ?: 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $this->leaveService->deleteLeave((int) $id, auth()->user());

            if ($request->expectsJson() || $request->ajax()) {
                return $this->deletedResponse('Leave Request Deleted Successfully');
            }

            return redirect()->back()->with([
                'message' => 'Leave Request Deleted Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Leave delete error: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return $this->errorResponse($e->getMessage(), 400);
            }

            return redirect()->back()->with([
                'message' => 'Failed to delete leave request.',
                'alert-type' => 'error'
            ]);
        }
    }

    public function show($id)
    {
        $title = 'Leave Data';
        $section = 'Leave Management';
        $section_url = route('leave.index');
        $sub_section = 'View';

        $leaveData = $this->leaveService->getLeaveById((int) $id);

        return view('leave.view', compact('title', 'section', 'sub_section', 'section_url', 'leaveData'));
    }

    public function import(ImportLeavesRequest $request)
    {
        try {
            $this->leaveService->importLeaves($request->file('file'));

            if ($request->expectsJson() || $request->ajax()) {
                return $this->successResponse('Leave requests imported successfully');
            }

            return redirect()->back()->with([
                'message' => 'Leave requests imported successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Leave Import Error: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return $this->errorResponse('Import failed: ' . $e->getMessage(), 400);
            }

            return redirect()->back()->with([
                'message' => 'Import failed: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    public function showLeaveInfo($id)
    {
        $title = 'Employee Leave Information';
        $section = 'Employees';
        $sub_section = 'Profile - Leave Information';
        $section_url = route('employee.index');

        $profileData = $this->leaveService->getEmployeeLeaveProfileData((int) $id, auth()->user());

        return view('employee.profile', array_merge($profileData, compact('title', 'section', 'sub_section', 'section_url')));
    }

    public function calculateEndDate(CalculateEndDateRequest $request)
    {
        try {
            $endDate = $this->leaveService->calculateEndDate($request->validated());

            return $this->successResponse('End date calculated successfully.', [
                'end_date' => $endDate
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }
}
