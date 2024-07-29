<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\SubmissionItem;
use Illuminate\Http\Request;

class SubmissionCheckController extends Controller
{
    public function __construct()
    {
        if (!auth()->user()->can('pengawas')){
            abort(response()->json([
                'message' => 'unauthorized access',
            ], 403));
        }
    }
    
    /**
     * 
     */
    public function accept(SubmissionItem $submission)
    {
        $this->checkStatus($submission);

        $submission->update([
            'status' => 'disetujui'
        ]);

        return response()->json([
            'message' => 'success accept submission item'
        ]);
    }

    /**
     * 
     */
    public function decline(SubmissionItem $submission)
    {
        $this->checkStatus($submission);

        $submission->update([
            'status' => 'ditolak'
        ]);

        return response()->json([
            'message' => 'success decline submission item'
        ]);
    }

    public function checkStatus(SubmissionItem $submission)
    {
        if ($submission->status == 'ditolak' || $submission->status == 'disetujui') {
            abort(response()->json([
                'message' => 'failed accept submission item, submission already ' . $submission->status
            ], 422));
        }
    }
}
