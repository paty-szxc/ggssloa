<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OTRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OTRequestController extends Controller
{
    public function getOTReq(){
        $userId = Auth::id();

        $ot = OTRequest::select(
            'id',
            'user_id',
            'reason',
            'time_duration',
            'time_end',
            'total_hrs_mins',
            'status'
            )
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item){
                $item->time_duration = Carbon::parse($item->time_duration)->format('h:i A');
                $item->time_end = Carbon::parse($item->time_end)->format('h:i A');
                return $item;
            });
        return $ot;
    }

    public function getAllOtReq(){
        $all_ot = OTRequest::select(
            'users.name',
            'users.username',
            'ot_request.id',
            'ot_request.reason',
            'ot_request.time_duration',
            'ot_request.time_end',
            'ot_request.total_hrs_mins',
            'ot_request.status'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('status', 0)
            ->get()
            ->map(function ($item){
                $item->time_duration = Carbon::parse($item->time_duration)->format('h:i A');
                $item->time_end = Carbon::parse($item->time_end)->format('h:i A');
                return $item;
            });
        return $all_ot;
    }

    public function getApprovedOtReq(){
        $approved_ot = OTRequest::select(
            'users.name',
            'users.username',
            'ot_request.id',
            'ot_request.reason',
            'ot_request.time_duration',
            'ot_request.time_end',
            'ot_request.total_hrs_mins',
            'ot_request.status',
            'ot_request.approved_by'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('status', '!=', 0)
            ->get()
            ->map(function ($item){
                $item->time_duration = Carbon::parse($item->time_duration)->format('h:i A');
                $item->time_end = Carbon::parse($item->time_end)->format('h:i A');
                return $item;
            });
        return $approved_ot;
    }

    public function submitOtReq(Request $req){
        // return $req;
        $req->validate([
            'reason' => 'required|string|max:255',
            'time_duration' => [
                'required',
                'string',
                'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5]\d$/',
                function ($attribute, $value, $fail) {
                    list($hours, $minutes) = explode(':', $value);
                    $totalMinutes = ($hours * 60) + $minutes;
                    if ($totalMinutes < 30) {
                        $fail('Duration must be at least 30 minutes.');
                    }
                },
            ],
            'time_end' => [
                'required',
                'string',
                'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5]\d$/'
            ]
        ]);

           // Calculate total hours based on time_duration and time_end
        list($durationHours, $durationMinutes) = explode(':', $req->time_duration);
        list($endHours, $endMinutes) = explode(':', $req->time_end);

        // Convert both to total minutes
        $totalDurationMinutes = ($durationHours * 60) + $durationMinutes;
        $totalEndMinutes = ($endHours * 60) + $endMinutes;

        // Calculate the total time worked
        $totalMinutes = $totalEndMinutes - $totalDurationMinutes;

        // Ensure totalMinutes is not negative
        if ($totalMinutes < 0) {
            return response()->json(['message' => 'End time must be after the start time.'], 400);
        }

        // Convert total minutes back to hours and minutes
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;

        // Format the total time as "X hour(s) and Y minute(s)"
        $totalTimeFormatted = '';
        if ($totalHours > 0) {
            $totalTimeFormatted .= $totalHours . ' hour' . ($totalHours > 1 ? 's' : '');
        }
        if ($remainingMinutes > 0) {
            if ($totalTimeFormatted) {
                $totalTimeFormatted .= ' and ';
            }
            $totalTimeFormatted .= $remainingMinutes . ' minute' . ($remainingMinutes > 1 ? 's' : '');
        }
    
        $otRequest = new OTRequest();
        $otRequest->user_id = Auth::id();
        $otRequest->reason = $req->reason;
        $otRequest->time_duration = $req->time_duration;
        $otRequest->time_end = $req->time_end;
        $otRequest->total_hrs_mins = $totalTimeFormatted;
        $otRequest->status = 0;
        $otRequest->save();
    
        return response()->json(['message' => 'OT request submitted successfully!', 'total_hrs_mins' => $totalTimeFormatted], 201);
    }

    public function updateOtReq(Request $req){
        // return $req;
        $userId = Auth::id();
        $otRequest = OTRequest::find($req->to_update['id']);
        // Calculate total hours based on time_duration and time_end
        list($durationHours, $durationMinutes) = explode(':', $req->to_update['time_duration']);
        list($endHours, $endMinutes) = explode(':', $req->to_update['time_end']);
        // Convert both to total minutes
        $totalDurationMinutes = ($durationHours * 60) + $durationMinutes;
        $totalEndMinutes = ($endHours * 60) + $endMinutes;

        // Calculate the total time worked
        $totalMinutes = $totalEndMinutes - $totalDurationMinutes;

        // Ensure totalMinutes is not negative
        if ($totalMinutes < 0) {
            return response()->json(['message' => 'End time must be after the start time.'], 400);
        }
        
        // Convert total minutes back to hours and minutes
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        
        // Format the total time as "X hour(s) and Y minute(s)"
        $totalTimeFormatted = '';
        if ($totalHours > 0) {
            $totalTimeFormatted .= $totalHours . ' hour' . ($totalHours > 1 ? 's' : '');
        }
        if ($remainingMinutes > 0) {
            if ($totalTimeFormatted) {
                $totalTimeFormatted .= ' and ';
            }
            $totalTimeFormatted .= $remainingMinutes . ' minute' . ($remainingMinutes > 1 ? 's' : '');
        }

        $otRequest->update([
            'user_id' => $userId,
            'reason' => $req->to_update['reason'],
            'time_duration' => $req->to_update['time_duration'],
            'time_end' => $req->to_update['time_end'],
            'total_hrs_mins' => $totalTimeFormatted
        ]);
        return response()->json(['message' => 'OT request updated successfully!', 'total_hrs_mins' => $totalTimeFormatted], 200);


        return response()->json(['error' => 'Request not found or unauthorized.'], 404);
    }

    public function handleOtReq(Request $req){
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
