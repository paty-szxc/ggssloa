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
            if(Auth::attempt($request->toArray(), true)) {
                $request->session()->regenerate();
                return redirect('/');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request) 
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
