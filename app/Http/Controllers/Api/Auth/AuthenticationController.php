<?php

namespace App\Http\Controllers\Api\Auth;


use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
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
            $token = $user->createToken('AUTH-TOKEN')->plainTextToken;

            return ApiResponse::sendResponse(200, 'Success', 'Logged in', [
                'user' => new UserResource($user),
                'token' => $token,
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200, 'Success', 'Logged out');
    }
}
