<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateProfileRequest $request)
    {
        $user_data = $request->validated();
        $user = User::find(Auth::id());
        $user->update($user_data);

        return back()->with('success','Profile updated successfully!');
    }
}
