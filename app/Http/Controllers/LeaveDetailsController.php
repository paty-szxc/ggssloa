<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\LeaveDetail;
use Illuminate\Support\Facades\Auth;


class LeaveDetailsController extends Controller
{
    public function getLeaveDetails(){
        // $userId = Auth::id();

        $leave_dets = LeaveDetail::select(
            'users.name',
            'leave_details.id',
            'leave_details.user_id',
            'leave_details.date_filed',
            'leave_details.leave_from',
            'leave_details.leave_to',
            'leave_details.no_of_days',
            'leave_details.leave_type',
            'leave_details.filed',
            'leave_details.with_pay',
            'leave_details.reasons',

            'leave_types.leave_name',

        )->leftJoin('users', 'users.id', 'user_id')
        ->leftJoin('leave_types', 'leave_types.id', 'leave_type')
        ->where('status', 0)
        ->get();

        
        if(count($leave_dets) == 0) {
            return response()->json(['message' => 'Employee leave details not found'], 404);
        }

        return response()->json($leave_dets);
    }

    public function getLeaveReq(){
        $leave_req = LeaveDetail::select(
            'users.name',
            'users.username',
            'leave_details.id',
            'leave_details.user_id',
            'leave_details.date_filed',
            'leave_details.leave_from',
            'leave_details.leave_to',
            'leave_details.no_of_days',
            'leave_details.leave_type',
            'leave_details.filed',
            'leave_details.with_pay',
            'leave_details.reasons',
            'leave_details.status',
            'leave_types.leave_name',
            'leave_details.updated_by'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->leftJoin('leave_types', 'leave_types.id', 'leave_type')
            ->where('status', '!=', 0)
            ->get();
        return $leave_req;
    }

    public function convertToYyyyMmDd($isoDate) {
        return (new \DateTime($isoDate))->format('Y-m-d');
    }

    public function addLeave(Request $req){
        // return $req;
        $userId = Auth::id();

        $leave = LeaveDetail::create([
            'user_id' => $userId,
            'date_filed' => (new \DateTime())->format('Y-m-d'),
            'leave_type' => $req->to_insert['leave_type'],
            'leave_from' => $this->convertToYyyyMmDd($req->to_insert['leave_from']),
            'leave_to' => $this->convertToYyyyMmDd($req->to_insert['leave_to']),
            'no_of_days' => $req->to_insert['no_of_days'],
            'reasons' => $req->to_insert['reasons'],
            'filed' => isset($req->to_insert['filed']) ? $req->to_insert['filed'] : 0,
            'with_pay' => isset($req->to_insert['with_pay']) ? $req->to_insert['with_pay'] : 0,
        ]);
        
        // $days = $req->to_insert['no_of_days'];
        // if($req->to_insert['leave_type'] == 'Sick'){
        //     Employee::where('user_id', $userId)->update([
        //         'sick_leave' => \DB::raw("sick_leave - $days")
        //     ]);
        // }else{
        //     Employee::where('user_id', $userId)->update([
        //         'vacation_leave' => \DB::raw("vacation_leave - $days")
        //     ]);
        // }
    }


    public function handleLeaveReq(Request $req){
        // return $req;
        $user = Auth::user();
        if($user){
            $upd = LeaveDetail::find($req->leave_detail_id);
            $upd->status = $req->status;
            $upd->updated_by = $user->username;
            // return $user->username;
            $upd->save();
    
            return response()->json([
                'message' => 'Leave request updated successfully.',
                'updated_by' => $user->username,
            ]);
        }
    
        // If the user is not authenticated, return an error response
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function update_leave_home(Request $request){
        // return $request;
        $userId = Auth::id();

        $leave = LeaveDetail::find($request->to_insert['id']);

        $leave->update([
            'user_id' => $userId,
            'date_filed' => (new \DateTime())->format('Y-m-d'),
            'leave_type' => $request->to_insert['leave_type'],
            'leave_from' => $this->convertToYyyyMmDd($request->to_insert['leave_from']),
            'leave_to' => $this->convertToYyyyMmDd($request->to_insert['leave_to']),
            'no_of_days' => $request->to_insert['no_of_days'],
            'reasons' => $request->to_insert['reasons'],
            'filed' => isset($request->to_insert['filed']) ? $request->to_insert['filed'] : 0,
            'with_pay' => isset($request->to_insert['with_pay']) ? $request->to_insert['with_pay'] : 0,
        ]);
    }
}
