<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubmissionItem;
use App\Models\SubmissionItemDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubmissionItemDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SubmissionItem $submission)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $submissionItemDetails = $submission->details()->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'message' => 'success get all submission item details',
            ...$submissionItemDetails->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SubmissionItem $submission)
    {
        $this->checkStatus($submission);

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['submission_item_id'] = $submission->id;
        $data['qty_acc'] = $data['qty'];
        $submission->details()->create($data);

        return response()->json([
            'message' => 'success add submission item detail',
            'data' => $submission->details
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubmissionItem $submission)
    {
        $this->checkStatus($submission);

        $validator = Validator::make($request->all(), [
            'item_id' => 'required|integer',
            'qty' => 'required|integer',
            'qty_acc' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $detail = $submission->details()->where('item_id', $data['item_id'])->first();
        $detail->update($data);

        return response()->json([
            'message' => 'success update submission item detail',
            'data' => $submission->details
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubmissionItem $submission, SubmissionItemDetail $detail)
    {
        $this->checkStatus($submission);

        $detail->delete();
        return response()->json([
            'message' => 'success delete submission item detail'
        ]);
    }

    function checkStatus(SubmissionItem $submission)
    {
        if ($submission->status == 'ditolak' || $submission->status == 'disetujui') {
            return response()->json([
                'message' => 'submission item already ' . $submission->status
            ], 400);
        }
    }
}
