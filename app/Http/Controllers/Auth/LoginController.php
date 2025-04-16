<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user_data = $request->validated();
        if (Auth::attempt($user_data)) {
            return redirect()->intended('profile')->with('success', 'You are in');
        }

        return back()->with('error', 'Invalid Credientials!');
    }
}
