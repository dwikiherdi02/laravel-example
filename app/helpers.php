<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('auth_role')) {
    function auth_role(): ?string
    {
        $user = Auth::user();
        return $user?->roleValue();
    }
}

if (!function_exists('array_to_object')) {
    function array_to_object(mixed $data): mixed
    {
        if (is_array($data)) {
            return (object) array_map('array_to_object', $data);
        }
        return $data;
    }
}
