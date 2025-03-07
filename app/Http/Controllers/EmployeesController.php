<?php

namespace App\Http\Controllers;


use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    public function getEmployee(){
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Retrieve the employee associated with the authenticated user
        $employee = Employee::where('user_id', $userId)
        ->with(['leave_details'])
        ->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json($employee);
    }

    
}
