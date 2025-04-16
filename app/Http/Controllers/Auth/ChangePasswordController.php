<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ChangePasswordRequest $request)
    {
        $user = User::find(Auth::id());
        $user_data = $request->validated();
        if (!Hash::check($user_data['current_password'], $user->password)) {
            return back()->with('error', 'Current Password incorrect');
        }
        $user->update(['password' => $user_data['new_password']]);
        return back()->with('success', 'Your password updated successfully!');
    }
}
