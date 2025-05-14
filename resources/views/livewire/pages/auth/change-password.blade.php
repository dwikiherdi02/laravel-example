<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{layout, title, state, rules};

layout('layouts.guest');

title(__('change_password.title'));

state([
    'current_password' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'current_password' => ['required', 'string', 'current_password'],
    'password' => ['required', 'string', Password::defaults(), 'confirmed'],
]);

// Add this block to handle redirection
if (Auth::check() && Auth::user()->is_initial_login === 0) {
    redirect()->route('dashboard')->send();
}

$updatePassword = function () {
    try {
        $this->resetErrorBag();

        $validated = $this->validate();
    } catch (ValidationException $e) {
        $this->reset('current_password', 'password', 'password_confirmation');

        throw $e;
    }

    Auth::user()->update([
        'password' => Hash::make($validated['password']),
        'is_initial_login' => 0,
    ]);

    $this->reset('current_password', 'password', 'password_confirmation');

    // $this->dispatch('password-updated');

    $this->redirect(route('dashboard'));
};

?>

<div>
    <form wire:submit="updatePassword">
        <div class="form-label-group">
            <x-text-input wire:model="current_password" id="update_password_current_password" type="password" name="current_password" autocomplete="current-password" placeholder="{{ __('change_password.current_password_label') }}" aria-describedby="currentPasswordHelp" />
            <x-input-label for="update_password_current_password" :value="__('change_password.current_password_label')" />
            <x-input-error id="currentPasswordHelp" :messages="$errors->get('current_password')" />
        </div>

        <div class="form-label-group">
            <x-text-input wire:model="password" id="update_password_password" type="password"
            name="password" autocomplete="new-password" placeholder="{{ __('change_password.new_password_label') }}" aria-describedby="newPasswordHelp" />
            <x-input-label for="update_password_password" :value="__('change_password.new_password_label')" />
            <x-input-error id="newPasswordHelp" :messages="$errors->get('password')" />
        </div>

        <div class="form-label-group">
            <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="{{ __('change_password.confirm_password_label') }}" aria-describedby="passwordConfirmationHelp" />
            <x-input-label for="update_password_password_confirmation" :value="__('change_password.confirm_password_label')" />
            <x-input-error id="passwordConfirmationHelp" :messages="$errors->get('password')" />
        </div>

        <button wire:loading.remove class="btn btn-primary w-100 py-2"
            type="submit">{{ __('change_password.update_password_button') }}</button>

        <button wire:loading class="btn btn-primary w-100 py-2" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
    </form>
</div>
