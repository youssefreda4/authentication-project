<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $user_data = $request->validated();
        $user = User::create($user_data);

        Auth::login($user);
        return redirect()->route('profile')->with('success','You have created your accont successfully!');
    }
}
  