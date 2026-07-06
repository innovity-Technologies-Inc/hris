<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\ProfileUpdateRequest;
use App\Services\Employee\ProfileUpdateRequestServices;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class ProfileUpdateRequestController extends Controller
{
    public function __construct(
        private readonly ProfileUpdateRequestServices $service
    ) {}

    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Profile Update Requests';
        $section = 'Employee';
        $sub_section = 'Profile Update Requests';
        $section_url = route('profile_update_requests.index');

        $requests = $this->service->getFilteredRequests(
            $flexSearch,
            $request->input('status'),
            $request->input('type'),
            $request->input('search')
        );

        if ($request->ajax()) {
            return response()->json([
                'html' => view('employee.profile_update_requests.partials.table', compact('requests'))->render(),
                'pagination' => $requests->links()->render(),
            ]);
        }

        return view('employee.profile_update_requests.index', compact('requests', 'title', 'section', 'sub_section', 'section_url'));
    }

    public function show(int $id)
    {
        $title = 'Review Profile Update Request';
        $section = 'Employee';
        $sub_section = 'Review Update Request';
        $section_url = route('profile_update_requests.index');

        $updateRequest = $this->service->findOrFail($id);

        return view('employee.profile_update_requests.show', compact('updateRequest', 'title', 'section', 'sub_section', 'section_url'));
    }

    public function store(ProfileUpdateRequest $request)
    {
        $updateRequest = $this->service->createRequest($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile update request submitted successfully and is pending approval.'
        ]);
    }

    public function destroy(int $id)
    {
        $this->service->deleteRequest($id);

        return response()->json([
            'success' => true,
            'message' => 'Profile update request deleted successfully.'
        ]);
    }
}
