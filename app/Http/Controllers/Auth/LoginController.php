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
            if(! Auth::attempt($request->toArray(), true)) {
                return response()->json([
                    'message' => 'User do not match in our records.'
                ]);
            }
            $request->session()->regenerate();
            return redirect()->intended('/');
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
