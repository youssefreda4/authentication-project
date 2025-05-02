<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\TokenAbilityEnum;
use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return ApiResponse::sendResponse(201, 'Success', 'Your account created successfully', [
            'user' => new UserResource($user),
        ]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Auth::attempt($request->only('email', 'password'))) {
            $access_token = $user->createToken('ACCESS-TOKEN', [TokenAbilityEnum::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.access_token_expiration')))->plainTextToken;
            $refresh_token = $user->createToken('REFRESH-TOKEN', [TokenAbilityEnum::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')))->plainTextToken;

            return ApiResponse::sendResponse(200, 'Success', 'Logged in', [
                'user' => new UserResource($user),
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ]);
        }
        return ApiResponse::sendResponse(401, 'Error', 'The provided credentials are incorrect');
    }

    public function profile(Request $request)
    {
        return ApiResponse::sendResponse(200, 'Success', 'Profile', [
            'user' => new UserResource(Auth::user()),
        ]);
    }

    public function refreshToken(Request $request)
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        $access_token = $user->createToken('ACCESS-TOKEN', [TokenAbilityEnum::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.access_token_expiration')))->plainTextToken;
        $refresh_token = $user->createToken('REFRESH-TOKEN', [TokenAbilityEnum::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')))->plainTextToken;

        return ApiResponse::sendResponse(200, 'Success', 'Token refreshed successfully', [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200, 'Success', 'Logged out');
    }
}
