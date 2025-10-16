<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        try {
            FacadesDB::beginTransaction();
            // Normalize/parse date_hired early for both User and Employee writes
            // Treat incoming value as a pure date (ignore timezone offsets from ISO string)
            $dateOnly = substr((string) $request->date_hired, 0, 10);
            $dateHired = Carbon::createFromFormat('Y-m-d', $dateOnly);

            $user = User::create([
                'name'=> $request->name,
                'employee_code' => $request->employee_code,
                'position' => $request->position,
                'date_hired' => $dateHired->toDateString(),
                'username'=> $request->username,
                'password' => Hash::make($request->password)
            ]);

            // Prepare dynamic leave renewal date: Feb 1 of next year from current date
            $now = Carbon::now();
            $nextYear = $now->year + 1;
            $renewalDate = Carbon::create($nextYear, 2, 1, 0, 0, 0, $now->timezone);

            // Determine starting leave based on 6-month rule at registration time
            $eligibleForInitialLeaves = $dateHired->lte(Carbon::now()->subMonthsNoOverflow(6));
            $startingSick = $eligibleForInitialLeaves ? 5 : 0;
            $startingVacation = $eligibleForInitialLeaves ? 5 : 0;

            // Create employee record
            Employee::create([
                'user_id' => $user->id,
                'emp_id' => $request->employee_code, // optional; auto-generated if empty
                'emp_name' => $request->name,
                'emp_position' => $request->position,
                'date_hired' => $dateHired->toDateString(),
                'sick_leave' => $startingSick,
                'vacation_leave' => $startingVacation,
                'leave_renewal_date' => $renewalDate,
            ]);
            FacadesDB::commit();
            return response()->json(['message' => $request->name . ' successfully registered'], 200);
        } catch (\Exception $e) {
            FacadesDB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
