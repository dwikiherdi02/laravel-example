<?php

use App\Dto\UserDto;
use App\Services\UserService;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{state, rules, mount};

state([
    'auth',
    
    // form
    'id' => '',
    'name' => '',
    'username' => '',

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

rules([
    'id' => ['required', 'string'],
    'name' => ['required', 'string'],
    'username' => [ 'required', 'string'],
]);

mount(function () {
    if ($this->auth) {
        $this->id = $this->auth->id ?? '';
        $this->name = $this->auth->name ?? '';
        $this->username = $this->auth->username ?? '';
    }
});

$updateUser = function (UserService $service) {
    try {
        $this->alertMessage = null;
        $this->isError = false;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = UserDto::from($validated);

        $service->update($data);

        $this->alertMessage = __('user.success_update');
    } catch (ValidationException $e) {
        $this->isError = true;
        throw $e;
    } catch (\Exception $e) {
        $this->isError = true;
        $this->alertMessage = $e->getMessage();
    }
};

?>

<div>
    <div class="card shadow-none mb-3">
        <div class="card-header px-0">{{ __('profile.label_card_header_general') }}</div>
        <div class="card-body px-0">
            <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
                {{ $alertMessage }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="auth-general-form" wire:submit="updateUser">
                @if(!$auth->isWarga())
                <div class="form-group row mb-4">
                    <x-input-label for="name" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('user.label_form_name')" :isRequired="true" />
                    <div class="col-sm-8 text-right">
                        <x-text-input wire:model="name" id="name" type="text" name="name" aria-describedby="nameHelp" />
                        <x-input-error class="text-left" id="nameHelp" :messages="$errors->get('name')" />
                    </div>
                </div>
                @endif

                <div class="form-group row mb-4">
                    <x-input-label for="username" class="col-sm-4 col-form-label h6 font-weight-bolder"  :value="__('user.label_form_username')" :isRequired="true" />
                    <div class="col-sm-8 text-right">
                        <x-text-input wire:model="username" id="username" type="text" name="username" aria-describedby="usernameHelp" />
                        <x-input-error id="usernameHelp" class="text-left" :messages="$errors->get('username')" />
                    </div>
                </div>

                <div class="d-flex">
                    <button wire:loading.remove type="submit" class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100">
                        {{ __('label.save') }}
                    </button>

                    <button wire:loading class="mb-3 btn btn-lg btn-primary btn-block text-uppercase text-decoration-none w-100" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form> 
        </div>
    </div>
</div>