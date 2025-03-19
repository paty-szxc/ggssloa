<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OTRequest;
use Illuminate\Support\Facades\Auth;

class OTRequestController extends Controller
{
    public function getOTReq(){
        $userId = Auth::id();

        $ot = OTRequest::select(
            'user_id',
            'reason',
            'time_duration',
            'status'
            )
            ->where('user_id', $userId)
            ->get();

        return $ot;
    }

    public function getAllOtReq(){
        $all_ot = OTRequest::select(
            'users.name',
            'ot_request.reason',
            'ot_request.time_duration',
            'ot_request.status'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('status', 0)
            ->get();

        return $all_ot;
    }

    public function submitOTReq(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'reason' => 'required|string|max:255',
            'time_duration' => 'required|string|regex:/^.+$/', // Accept any non-empty string

        ]);
    
        // Create a new OT request
        $otRequest = new OTRequest();
        $otRequest->user_id = Auth::id(); // Assuming you want to associate the request with the authenticated user
        $otRequest->reason = $request->reason;
        $otRequest->time_duration = $request->time_duration;
        $otRequest->status = 0; // Default status for new requests (for approval)
        $otRequest->save();
    
        return response()->json(['message' => 'OT request submitted successfully!'], 201);
    }

    
    public function updateOtReq(Request $request){
        return $request;
        $otReqId = $request->ot_req_id;
        $upd = OTRequest::find($otReqId);
        if (!$upd) {
            return response()->json(['error' => 'OT request not found'], 404);
        }
        $upd->status = $request->status;
        $upd->save();
    }
    
    
}
