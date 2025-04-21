<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $driver)
    {
        if (!array_key_exists($driver, config('social.providers'))) {
            return redirect()->route('auth.login')->with('error', 'Invalid Driver');
        }
        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver)
    {
        if (!array_key_exists($driver, config('social.providers'))) {
            return redirect()->route('auth.login')->with('error', 'Invalid Driver');
        }
        try {
            $socialUser = Socialite::driver($driver)->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('auth.login')->with('error', 'Authentication Failed');
        }

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(14)),
                'image' => $socialUser->avatar,
                'otp' =>  rand(100000, 999999),
            ]
        );

        Auth::login($user);
        return redirect()->intended('profile')->with('success', 'You are in');
    }
}
