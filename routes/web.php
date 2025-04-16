<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UpdateProfileController;
use Illuminate\Support\Facades\Route;



Route::view('/', 'index')->name('index');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');


Route::post('/register', RegisterController::class)->name('auth.register');
Route::post('/login', LoginController::class)->name('auth.login');


Route::middleware('auth')->group(function () {
    Route::view('/profile', 'auth.profile')->name('profile');
    Route::put('/profile',UpdateProfileController::class)->name('profile.update');
    Route::post('/change-password',ChangePasswordController::class)->name('profile.change.password');

    Route::post('/logout',LogoutController::class)->name('auth.logout');
});
