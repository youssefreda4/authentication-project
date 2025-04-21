<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('index')->with('success', 'Logged out successfully!');
    }

    public function logoutOtherDevice(Session $session)
    {
        $session->delete();
        return back()->with('success', 'Logged out successfully!');
    }
}
