<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerifyAccountMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $user_data = $request->validated();
        $user = User::create([
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'password' => $user_data['password'],
            'otp' =>  rand(100000, 999999),
        ]);

        Mail::to($user->email)->send(new VerifyAccountMail($user->otp,$user->email));

        return redirect()->route('email.verify', $user->email);
    }
}
