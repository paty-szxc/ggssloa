<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OBForm;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OBController extends Controller
{
    public function getOB(){
        $userId = Auth::id();

        $ob = OBForm::select(
            'id',
            'user_id',
            'date',
            'destination',
            'purpose',
            'time_departure',
            'time_return',
            'status'
            )
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item){
                $item->time_departure = Carbon::parse($item->time_departure)->format('h:i A');
                $item->time_return = Carbon::parse($item->time_return)->format('h:i A');
                return $item;
            });
        return $ob;
    }

    public function getAllObReq(){
        $all_ob = OBForm::select(
            'users.name',
            'users.username',
            'official_business.id',
            'official_business.date',
            'official_business.destination',
            'official_business.purpose',
            'official_business.time_departure',
            'official_business.time_return',
            'official_business.status'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('status', 0)
            ->get()
            ->map(function ($item){
                $item->time_departure = Carbon::parse($item->time_departure)->format('h:i A');
                $item->time_return = Carbon::parse($item->time_return)->format('h:i A');
                return $item;
            });
        return $all_ob;
    }

    public function addOBForm(Request $req){
        // return $req;
        $req->validate([
            'emp_name' => 'required|string|max:255',
            'date' => 'required|date',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'time_departure' => [
                'required',
                'string',
                'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5]\d$/',
            ],
            'time_return' => [
                'nullable',
                'string',
                'regex:/^(0?[0-9]|1[0-9]|2[0-3]):[0-5]\d$/',
            ]
        ]);
    
        $ob = new OBForm();
        $ob->user_id = auth()->user()->id;
        $ob->emp_name = $req->emp_name;
        // $ob->date = \Carbon\Carbon::create($req->date);
        $ob->date = date('Y-m-d H:i:s', strtotime($req->date));
        $ob->destination = $req->destination;
        $ob->purpose = $req->purpose;
        $ob->time_departure = $req->time_departure;
        $ob->time_return = $req->time_return;
        $ob->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Request submitted successfully!',
            'data' => $ob,
        ], 201);
    }
    
    public function handleObReq(Request $req){
        $user = Auth::user();
        if ($user) {
            $upd = OBForm::find($req->ob_req_id);
            
            if($upd){ //check if the request exists
                $upd->status = $req->status;
                $upd->approved_by = $user->username;
                $upd->save(); //save the changes
    
                return response()->json([
                    'message' => 'OB request updated successfully.',
                    'approved_by' => $user->username,
                ]);
            } else {
                return response()->json(['error' => 'Request not found.'], 404);
            }
        }
    
        //if the user is not authenticated, return an error response
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function updateOb(Request $req){
        $userId = Auth::id();

        $ob = OBForm::find($req->to_update['id']);

        $ob->update([
            'user_id' => $userId,
            'date' => $req->to_update['date'],
            'destination' => $req->to_update['destination'],
            'purpose' => $req->to_update['purpose'],
            'time_departure' => $req->to_update['time_departure'],
            'time_return' => $req->to_update['time_return'],
        ]);
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function getApprovedOb(){
        $approved_ob = OBForm::select(
            'users.name',
            'users.username',
            'official_business.id',
            'official_business.date',
            'official_business.destination',
            'official_business.purpose',
            'official_business.time_departure',
            'official_business.time_return',
            'official_business.status',
            'official_business.approved_by'
            )
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('status', '!=', 0)
            ->get()
            ->map(function ($item){
                $item->time_departure = Carbon::parse($item->time_departure)->format('h:i A');
                $item->time_return = Carbon::parse($item->time_return)->format('h:i A');
                return $item;
            });
        return $approved_ob;
    }
}
