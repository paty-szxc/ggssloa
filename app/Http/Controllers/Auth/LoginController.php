<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        if(Auth::user()) {
            return redirect('/');
        } else {
            return view('login');
        }
    }

    public function store(Request $request)
    {
        try {
            if(! Auth::attempt($request->only('username', 'password'), $request->filled('remember'))) {
                return response()->json([
                    'message' => 'User do not match in our records.'
                ], 401);
            }
            
            $request->session()->regenerate();
            
            return response()->json([
                'message' => 'Login successful',
                'redirect' => url('/') // or specific route
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request) 
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}