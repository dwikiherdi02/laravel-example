<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('auth_role')) {
    function auth_role(): ?string
    {
        $user = Auth::user();
        return $user?->roleValue();
    }
}
