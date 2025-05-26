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
        Volt::route('dashboard', 'pages.dashboard.index')
            ->name('dashboard');

        Volt::route('email', 'pages.email.index')
            ->name('email');

        Volt::route('residents', 'pages.residents.index')
            ->name('residents');

        Volt::route('users', 'pages.users.index')
            ->name('users');

        Volt::route('contributions', 'pages.contributions.index')
            ->name('contributions');

        Volt::route('add-monthly-contribution', 'pages.add-monthly-contribution.index')
            ->name('add-monthly-contribution');

        Volt::route('add-transaction', 'pages.add-transaction.index')
            ->name('add-transaction');

        Volt::route('monthly-contribution-history', 'pages.monthly-contribution-history.index')
            ->name('monthly-contribution-history');

        Volt::route('transaction-history', 'pages.transaction-history.index')
            ->name('transaction-history');

        // Volt::route('contribution-report', 'pages.contribution-report.index')
        //     ->name('contribution-report');

        // Volt::route('financial-report', 'pages.financial-report.index')
        //     ->name('financial-report');

        Volt::route('profile', 'pages.profile.index')
            ->name('profile');

        Volt::route('imap', 'pages.imap.index')
            ->name('imap');

        Volt::route('text-template', 'pages.text-template.index')
            ->name('text-template');

        // Route::view('profile', 'profile')->name('profile');

        // Volt::route('verify-email', 'pages.auth.verify-email')
        //     ->name('verification.notice');

        // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        //     ->middleware(['signed', 'throttle:6,1'])
        //     ->name('verification.verify');

        // Volt::route('confirm-password', 'pages.auth.confirm-password')
        //     ->name('password.confirm');

    });
});
