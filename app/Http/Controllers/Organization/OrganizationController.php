<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Models\Organization\Organization;
use App\Services\Organization\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    protected $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only Super Admins can access
        if (!is_null(auth()->user()->organization_id)) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            $filters = $request->only(['search', 'status', 'per_page']);
            $organizations = $this->organizationService->listOrganizations($filters);
            return response()->json([
                'success' => true,
                'data' => $organizations
            ]);
        }

        return view('organization.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {
        try {
            $organization = $this->organizationService->createOrganization($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Resource created successfully.',
                'data' => $organization
            ], 201);

        } catch (\Exception $e) {
            Log::error('Organization creation failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create organization: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!is_null(auth()->user()->organization_id)) {
            abort(403, 'Unauthorized action.');
        }

        $organization = Organization::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $organization
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, $id)
    {
        try {
            $organization = Organization::findOrFail($id);
            $updatedOrganization = $this->organizationService->updateOrganization($organization, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Resource updated successfully.',
                'data' => $updatedOrganization
            ], 200);

        } catch (\Exception $e) {
            Log::error('Organization update failed: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update organization: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suspend the organization (sets status inactive, blocks all users).
     */
    public function suspend($id)
    {
        if (!is_null(auth()->user()->organization_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            $organization = Organization::findOrFail($id);
            $this->organizationService->suspendOrganization($organization);

            return response()->json([
                'success' => true,
                'message' => 'Organization suspended successfully.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Organization suspension failed: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to suspend organization: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!is_null(auth()->user()->organization_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            $organization = Organization::findOrFail($id);
            $this->organizationService->deleteOrganization($organization);

            return response()->json([
                'success' => true,
                'message' => 'Resource deleted/released/deactivated successfully.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Organization deletion failed: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete organization: ' . $e->getMessage()
            ], 500);
        }
    }
}
