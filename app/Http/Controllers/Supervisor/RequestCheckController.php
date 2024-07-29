<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\RequestItem;
use Illuminate\Http\Request;

class RequestCheckController extends Controller
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
    public function accept(RequestItem $requestItem)
    {
        $this->checkStatus($requestItem);

        $requestItem->update([
            'status' => 'disetujui'
        ]);

        return response()->json([
            'message' => 'success accept submission item'
        ]);
    }

    /**
     * 
     */
    public function decline(RequestItem $requestItem)
    {
        $this->checkStatus($requestItem);

        $requestItem->update([
            'status' => 'ditolak'
        ]);

        return response()->json([
            'message' => 'success decline submission item'
        ]);
    }
    public function checkStatus(RequestItem $requestItem)
    {
        if ($requestItem->status == 'ditolak' || $requestItem->status == 'disetujui') {
            abort(response()->json([
                'message' => 'failed accept request item, request item already ' . $requestItem->status
            ], 422));
        }
    }
}
