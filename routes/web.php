<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MagicLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\UpdateProfileController;
use App\Http\Controllers\Auth\VerifyAccountController;
use App\Models\Session;
use Illuminate\Support\Facades\Route;





Route::view('/', 'index')->name('index');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::post('/register', RegisterController::class)->name('auth.register');
Route::post('/login', LoginController::class)->name('auth.login');

Route::view('/login/magic', 'auth.passwordless-login')->name('login.magic');
Route::post('/login/magic', [MagicLoginController::class, 'sendMagicLink'])->name('login.magic.link');
Route::get('/login/magic/{user}', [MagicLoginController::class, 'handleMagicLogin'])->name('login.magic.handle')->middleware('signed');

Route::get('/auth/{driver}/redirect', [SocialAuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/{driver}/callback', [SocialAuthController::class, 'callback'])->name('auth.callback');

Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');

Route::post("/forgot-password", ForgotPasswordController::class)->name('password.email');
Route::post("/reset-password", ResetPasswordController::class)->name('password.update');

Route::view("/verify-account/{identifier}", 'auth.verify-account')->name('account.verify');
Route::post("/verify-account", [VerifyAccountController::class, 'verfiyOtp'])->name('account.send.verify');
Route::post('/send-verification-otp', [VerifyAccountController::class, 'sendOtp'])->name('account.send.otp.verify');

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::view('/profile', 'auth.profile')->name('profile');
    Route::put('/profile', UpdateProfileController::class)->name('profile.update');
    Route::post('/change-password', ChangePasswordController::class)->name('profile.change.password');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('auth.logout');
    Route::post('/logout/{session}', [LogoutController::class, 'logoutOtherDevice'])->name('auth.other.device.logout');

    Route::view('student', 'pages.student')->middleware('role:Student');
    Route::view('teacher', 'pages.teacher')->middleware('role:Teacher');
    Route::view('admin', 'pages.admin')->middleware('role:Admin');
});
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::post('users/{user}/change-role', [UserController::class, 'changeRole']);
Route::resource('roles', RoleController::class);
