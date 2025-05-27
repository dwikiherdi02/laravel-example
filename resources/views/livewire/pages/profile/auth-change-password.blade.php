<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{state, rules};

state([
    'auth',
    
    // form
    'current_password' => '',
    'password' => '',
    'password_confirmation' => '',

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

rules([
    'current_password' => ['required', 'string', 'current_password'],
    'password' => ['required', 'string', Password::defaults(), 'confirmed'],
]);

$updatePassword = function () {
    $this->isError = false;
    try {
        $this->resetErrorBag();

        $validated = $this->validate();
    } catch (ValidationException $e) {
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->isError = true;
        throw $e;
    }

    Auth::user()->update([
        'password' => Hash::make($validated['password']),
        'is_initial_login' => 0,
    ]);

    $this->alertMessage = __('change_password.success_update');

    $this->reset('current_password', 'password', 'password_confirmation');
};

?>

<div>
    <div class="card shadow-none mb-3">
        <div class="card-header px-0">{{ __('profile.label_card_header_change_password') }}</div>
        <div class="card-body px-0">
            <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
                {{ $alertMessage }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="auth-change-password-form" wire:submit="updatePassword">
                <div class="form-group row mb-4">
                    <x-input-label for="update_password_current_password" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('change_password.current_password_label')" />
                    <div class="col-sm-8 text-right">
                        <x-text-input wire:model="current_password" id="update_password_current_password" type="password" name="current_password" autocomplete="current-password" aria-describedby="currentPasswordHelp" />
                        <x-input-error class="text-left" id="currentPasswordHelp" :messages="$errors->get('current_password')" />
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <x-input-label for="update_password_password" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('change_password.new_password_label')" />
                    <div class="col-sm-8 text-right">
                        <x-text-input wire:model="password" id="update_password_password" type="password" name="password" autocomplete="new-password" aria-describedby="newPasswordHelp" />
                        <x-input-error class="text-left" id="newPasswordHelp" :messages="$errors->get('password')" />
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <x-input-label for="update_password_password_confirmation" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('change_password.confirm_password_label')" />
                    <div class="col-sm-8 text-right">
                        <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"  aria-describedby="passwordConfirmationHelp" /> 
                        <x-input-error class="text-left" id="passwordConfirmationHelp" :messages="$errors->get('password')" />
                    </div>
                </div>

                <div class="d-flex">
                    <button wire:loading.remove type="submit" class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100">
                        {{ __('change_password.update_password_button') }}
                    </button>

                    <button wire:loading class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>