<?php

namespace App\Http\Controllers;

use Innovity\ApprovalEngine\Services\ApprovalResolver;
use Innovity\ApprovalEngine\Models\ApprovalStepRequest;
use Illuminate\Http\Request;

class ApprovalActionController extends Controller
{
    public function action(Request $request, $stepRequestId, ApprovalResolver $resolver)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'comments' => 'required|string|max:1000'
        ]);

        $stepRequest = ApprovalStepRequest::findOrFail($stepRequestId);

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
    }
}
