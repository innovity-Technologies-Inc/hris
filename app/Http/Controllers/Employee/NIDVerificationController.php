<?php

namespace App\Http\Controllers\Employee;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Services\Employee\NIDVerificationServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NIDVerificationController extends Controller
{
    protected $nidService;

    public function __construct(NIDVerificationServices $nidService)
    {
        $this->nidService = $nidService;
    }

    /**
     * Handle NID verification request
     */
    public function verify(Request $request, $id): JsonResponse
    {
        // Check permission
        if (!auth()->user()->can('employee-management.nid-verification')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not have permission to verify NID.'
            ], 403);
        }

        // Restrict for Employee user type
        if (auth()->user()->user_type === UserType::Employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employees are not allowed to verify NID.'
            ], 403);
        }

        $result = $this->nidService->verifyNID((int)$id);

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 400);
    }
}
