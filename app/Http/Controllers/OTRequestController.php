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
            'users.username',
            'ot_request.id',
            'ot_request.reason',
            'ot_request.time_duration',
            'ot_request.status'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('status', 0)
            ->get();
        return $all_ot;
    }

    public function getApprovedOtReq(){
        $approved_ot = OTRequest::select(
            'users.name',
            'users.username',
            'ot_request.id',
            'ot_request.reason',
            'ot_request.time_duration',
            'ot_request.status',
            'ot_request.approved_by'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('status', '!=', 0)
            ->get();
        return $approved_ot;
    }

    // public function submitOtReq(Request $request){
    //     $request->validate([
    //         'reason' => 'required|string|max:255',
    //         'time_duration' => 'required|string|regex:/^(0|[1-9]\d*):[0-5]\d$/', //enforce HH:MM format

    //     ]);
    //     $otRequest = new OTRequest();
    //     $otRequest->user_id = Auth::id();
    //     $otRequest->reason = $request->reason;
    //     $otRequest->time_duration = $request->time_duration;
    //     $otRequest->status = 0;
    //     $otRequest->save();
    
    //     return response()->json(['message' => 'OT request submitted successfully!'], 201);
    // }

    public function submitOtReq(Request $request) {
        $request->validate([
            'reason' => 'required|string|max:255',
            'time_duration' => [
                'required',
                'string',
                'regex:/^(0|[1-9]\d*):[0-5]\d$/', // Ensure HH:MM format
                function ($attribute, $value, $fail) {
                    list($hours, $minutes) = explode(':', $value);
                    $totalMinutes = ($hours * 60) + $minutes;
                    if ($totalMinutes < 30) {
                        $fail('Duration must be at least 30 minutes.');
                    }
                },
            ],
        ]);
    
        $otRequest = new OTRequest();
        $otRequest->user_id = Auth::id();
        $otRequest->reason = $request->reason;
        $otRequest->time_duration = $request->time_duration;
        $otRequest->status = 0;
        $otRequest->save();
    
        return response()->json(['message' => 'OT request submitted successfully!'], 201);
    }

    // public function handleOtReq(Request $req){
    //     // return $req;
    //     $user = Auth::user();
    //     if($user){
    //         $upd = OTRequest::find($req->ot_req_id);
    //         $upd->status = $req->status;
    //         $upd->approved_by = $user->username;
    //         return $user->username;
    //         $upd->save();
    
    //         return response()->json([
    //             'message' => 'Leave request updated successfully.',
    //             'approved_by' => $user->username,
    //         ]);
    //     }
    
    //     // If the user is not authenticated, return an error response
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    public function handleOtReq(Request $req) {
        $user = Auth::user();
        if ($user) {
            $upd = OTRequest::find($req->ot_req_id);
            
            if ($upd) { // Check if the request exists
                $upd->status = $req->status;
                $upd->approved_by = $user->username;
                $upd->save(); // Save the changes
    
                return response()->json([
                    'message' => 'Leave request updated successfully.',
                    'approved_by' => $user->username,
                ]);
            } else {
                return response()->json(['error' => 'Request not found.'], 404);
            }
        }
    
        // If the user is not authenticated, return an error response
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    
}
