<?php

namespace App\Http\Controllers;

use Innovity\ApprovalEngine\Services\ApprovalResolver;
use Innovity\ApprovalEngine\Models\ApprovalStepRequest;
use Innovity\ApprovalEngine\Enums\ApprovalStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalActionController extends Controller
{
    public function action(Request $request, $stepRequestId, ApprovalResolver $resolver)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'comments' => 'required|string|max:1000'
        ]);

        return DB::transaction(function () use ($request, $stepRequestId, $resolver) {
            // Lock the row for update to prevent concurrent race conditions
            $stepRequest = ApprovalStepRequest::lockForUpdate()->findOrFail($stepRequestId);

            if ($stepRequest->status !== ApprovalStatus::PENDING) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This request has already been processed.'
                ], 422);
            }

            if ($request->action === 'approve') {
                $resolver->approve($stepRequest, auth()->id(), $request->comments);
                $message = 'Request successfully approved!';
            } else {
                $resolver->reject($stepRequest, auth()->id(), $request->comments);
                $message = 'Request successfully rejected!';
            }

            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        });
    }
}
