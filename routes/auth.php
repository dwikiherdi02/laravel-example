<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::middleware('guest')->group(function () {
//     Volt::route('register', 'pages.auth.register')
//         ->name('register');

//     Volt::route('login', 'pages.auth.login')
//         ->name('login');

//     Volt::route('forgot-password', 'pages.auth.forgot-password')
//         ->name('password.request');

//     Volt::route('reset-password/{token}', 'pages.auth.reset-password')
//         ->name('password.reset');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('change-password', 'pages.auth.change-password')->name('change_password');

    Route::middleware(['must_change_password'])->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');

        Route::view('profile', 'profile')->name('profile');

        Volt::route('verify-email', 'pages.auth.verify-email')
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Volt::route('confirm-password', 'pages.auth.confirm-password')
            ->name('password.confirm');

    });
});
