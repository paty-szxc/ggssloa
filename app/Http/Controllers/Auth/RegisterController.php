<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            User::create([
                'name'=> $request->name,
                'username'=> $request->username,
                'password' => Hash::make($request->password)
            ]);
            DB::commit();
            return response()->json(['message' => $request->name . ' successfully registered'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
