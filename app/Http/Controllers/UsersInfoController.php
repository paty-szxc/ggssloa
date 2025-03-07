<?php

namespace App\Http\Controllers;

use App\Models\UsersInfo; // Make sure to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersInfoController extends Controller
{
    public function create(Request $request){
        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8', // Ensure password is at least 8 characters
        ]);

        // Create a new user
        $user = new UsersInfo();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->user_name = $request->user_name;
        $user->password = Hash::make($request->password); // Hash the password
        $user->save();

        // Optionally, return a response or redirect
        return response()->json(['message' => 'User created successfully!'], 201);
    }

    public function getUserInfo(Request $request){
        // Assuming you are using Laravel's built-in authentication
        $user = $request->user(); // Get the authenticated user

        // Return the user's information
        return response()->json([
            'username' => $user->user_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);
    }
}

