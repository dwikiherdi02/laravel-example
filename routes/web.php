<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    $prefix = config('app.route_prefix');

    Route::group(['prefix' => $prefix], function () {
        Volt::route('login', 'pages.auth.login')
            ->name('login');

        // Volt::route('register', 'pages.auth.register')
        //      ->name('register');
        
        // Volt::route('forgot-password', 'pages.auth.forgot-password')
        //     ->name('password.request');
    
        // Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        //     ->name('password.reset');
    });

});

require __DIR__ . '/auth.php';
