<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->resetErrorBag();

    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
};

?>

<div>
    <!-- Session Status -->
    {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

    <form wire:submit="login">
        {{-- <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> --}}
        {{-- <h1 class="h3 mb-3 fw-normal">{{ __('login.sign_in_label') }}</h1> --}}

        <div class="form-group">
            <x-input-label for="username" :value="__('login.username_label')" :isRequired="false" />
            <x-text-input
                wire:model="form.username"
                id="username"
                type="text"
                name="username"
                required autofocus
                autocomplete="username"
                placeholder="{{ __('login.username_label') }}"
                aria-describedby="usernameHelp" />
            <x-input-error id="usernameHelp" :messages="$errors->get('form.username')" />
        </div>

        <div class="form-group">
            <x-input-label for="password" :value="__('login.password_label')" :isRequired="false" />
            <x-text-input
                wire:model="form.password"
                id="password"
                type="password"
                name="password"
                placeholder="{{ __('login.password_label') }}"
                aria-describedby="passwordHelp" />
            <x-input-error id="passwordHelp" :messages="$errors->get('form.password')" />
        </div>

        <div class="form-check text-start my-3">
            <x-text-input
                class="form-check-input"
                wire:model="form.remember"
                id="remember"
                type="checkbox"
                name="remember"
                :isNotFormControl="true" />
            <x-input-label class="form-check-label" for="remember">
                {{ __('login.rememberme_label') }}
            </x-input-label>
        </div>
        <button wire:loading.remove class="btn btn-primary w-100 py-2" type="submit">{{ __('login.sign_in_button') }}</button>

        <button wire:loading class="btn btn-primary w-100 py-2" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
    </form>
</div>
