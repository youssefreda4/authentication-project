<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }
    
    public function callback()
    {
        $userFacebook = Socialite::driver('facebook')->user();
        $user = User::firstOrCreate(
            ['email'=> $userFacebook->getEmail()],
            [
                'name' => $userFacebook->getName(),
                'email' => $userFacebook->getEmail(),
                'image' => $userFacebook->avatar,
                'password'=> Hash::make(Str::random(14)),
                // 'email_verified_at' => now(),
                'otp' =>  rand(100000, 999999),
            ]
        );

        Auth::login($user);
        return redirect()->intended('profile')->with('success', 'You are in');
    }
}
