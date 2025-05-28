<?php

use App\Dto\ImapDto;
use App\Services\ComponentService;
use App\Services\ImapService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{layout, state, title, rules, mount};

layout('layouts.app');

state([
    'title',
    'icon',

    // form
    'host',
    'port',
    'protocol',
    'encryption',
    'validate_cert',
    'username',
    'password',
    'authentication',

    'isPasswordExist' => false,

    // alert
    'alertMessage' => '',
    'isError' => false,
]);

title(function (ComponentService $service) {
    $path = '/imap';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

rules([
    'host' => ['required', 'string'],
    'port' => ['required', 'integer'],
    'protocol' => ['required', 'string'],
    'encryption' => ['required', 'string'],
    'validate_cert' => ['boolean'],
    'username' => ['required', 'string'],
    'password' => ['required', 'string'],
    'authentication' => ['nullable', 'string'],
]);

mount(function (ImapService $service) {
    $imap = $service->get();

    if ($imap) {
        
        $this->setData($imap);

        if ($imap->password) {
            $this->isPasswordExist = true;
        } else {
            $this->isPasswordExist = false;
        }
    }
});

$saveImap = function (ImapService $service) {
    try {
        $this->alertMessage = null;
        $this->isError = false;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = ImapDto::from($validated);

        // dd($data);

        $imap = $service->save($data);

        $this->setData($imap);

        if ($imap->password) {
            $this->isPasswordExist = true;
        } else {
            $this->isPasswordExist = false;
        }

        $this->alertMessage = __('Data Imap berhasil disimpan.');
    } catch (ValidationException $e) {
        $this->isError = true;
        throw $e;
    } catch (\Exception $e) {
        $this->isError = true;
        // $this->reset('housing_block', 'name', 'phone_number', 'unique_code', 'address');
        $this->alertMessage = $e->getMessage();
    }
};

$setData = function (ImapDto $imap) {
    $this->host = $imap->host ?? '';
    $this->port = $imap->port ?? '';
    $this->protocol = $imap->protocol ?? '';
    $this->encryption = $imap->encryption ?? '';
    $this->validate_cert = $imap->validate_cert ?? '';
    $this->username = $imap->username != null ? Crypt::decryptString($imap->username) : '';
    $this->password = $imap->password != null ? Crypt::decryptString($imap->password) : '';
    $this->authentication = $imap->authentication ?? '';
};

?>

<div>
    <x-page-heading :title="$title" :icon="$icon" />
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    {{ __('Konfigurasi IMAP') }}
                    <div class="btn-actions-pane-right">
                        <button class="btn btn-light btn-sm w-100">{{ __('Test Koneksi IMAP') }}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert @if($isError) alert-danger @else alert-success @endif alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif" role="alert">
                        {{ $alertMessage }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="imap-form" wire:submit="saveImap">
                        <div class="form-group row mb-4">
                            <x-input-label for="host" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Host')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="host" id="host" type="text" name="host" aria-describedby="hostHelp" />
                                <x-input-error class="text-left" id="hostHelp" :messages="$errors->get('host')" />
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="port" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Port')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="port" id="port" class="form-number" type="number" name="port" aria-describedby="portHelp" />
                                <x-input-error class="text-left" id="portHelp" :messages="$errors->get('port')" />
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="protocol" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Protocol')" />
                            <div class="col-sm-8">
                                <x-text-input 
                                    wire:model="protocol" 
                                    :isNotFormControl="true" 
                                    class="form-control-plaintext" 
                                    id="protocol" 
                                    type="text" 
                                    name="protocol"
                                    readonly />
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="encryption" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Encryption')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="encryption" id="encryption" type="text" name="encryption" aria-describedby="encryptionHelp" />
                                <x-input-error class="text-left" id="encryptionHelp" :messages="$errors->get('encryption')" />
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="validate-cert" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Validate Cert')" />
                            <div class="col-sm-8">
                                <div class="custom-control custom-checkbox">
                                    <x-text-input 
                                        wire:model="validate_cert" 
                                        :isNotFormControl="true" 
                                        class="custom-control-input" 
                                        id="validate-cert-check" 
                                        type="checkbox" 
                                        name="validate_cert" />
                                    <label class="custom-control-label" for="validate-cert-check"></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <x-input-label for="username" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Username')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="username" id="username" type="text" name="username" aria-describedby="usernameHelp" />
                                <x-input-error class="text-left" id="usernameHelp" :messages="$errors->get('username')" />
                            </div>
                        </div>

                        @if(!$isPasswordExist)
                        <div class="form-group row mb-4">
                            <x-input-label for="password" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Password')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="password" id="password" type="password" name="password" aria-describedby="passwordHelp" />
                                <x-input-error class="text-left" id="passwordHelp" :messages="$errors->get('password')" />
                            </div>
                        </div>
                        @else
                        <div class="form-group row mb-4">
                            <x-input-label for="password" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Password')" :isRequired="true" />
                            <div class="col-sm-8 d-flex">
                                <x-text-input 
                                    :isNotFormControl="true" 
                                    class="form-control-plaintext" 
                                    id="password" 
                                    type="password"
                                    value="{{ __('********************') }}"
                                    readonly />

                                <button class="btn btn-link btn-change-password text-decoration-none w-25" type="button">
                                    {{ __('Ubah') }}
                                </button>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row mb-4">
                            <x-input-label for="authentication" class="col-sm-4 col-form-label h6 font-weight-bolder" :value="__('Authentication')" :isRequired="true" />
                            <div class="col-sm-8">
                                <x-text-input wire:model="authentication" id="authentication" type="text" name="authentication" aria-describedby="authenticationHelp" />
                                <x-input-error class="text-left" id="authenticationHelp" :messages="$errors->get('authentication')" />
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
    </div>
</div>

@script
<script>
    $(".btn-change-password").on("click", function() {
        $wire.set('password', '');
        $wire.set('isPasswordExist', false);
    });
</script>
@endscript
