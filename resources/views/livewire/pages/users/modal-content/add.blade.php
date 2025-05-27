<?php

use App\Dto\UserDto;
use App\Enum\RoleEnum;
use App\Services\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\{placeholder, state, rules, on, action};

placeholder('components.loading');

state([
    // form
    'role_id' => '',
    'resident_id' => '',
    'name' => '',
    'username' => '',
    'default_password' => true,
    'password' => '',
    'password_confirmation' => '',

    // alert
    'alertMessage' => ''
]);

rules([
    'role_id' => ['required', 'string', 'exists:roles,id'],
    'resident_id' => function () {
        $rules = ['string', 'exists:residents,id'];
        if (state('role_id') == RoleEnum::Warga->value) {
            array_unshift($rules, 'required');
        }
        return $rules;
    },
    'name' => ['required', 'string'],
    'username' => [
        'required',
        'string',
        Rule::unique('users')->where(function ($query) {
            return $query->where('deleted_at', null);
        })
    ],
    'default_password' => ['boolean', 'nullable'],
    'password' => ['required_if:default_password,false', 'string', Password::defaults(), 'confirmed'],
])->attributes([
            'role_id' => trans('user.label_form_role'),
            'resident_id' => trans('user.label_form_resident'),
            'name' => trans('user.label_form_name'),
            'username' => trans('user.label_form_username'),
            'default_password' => trans('user.label_form_default_password'),
            'password' => trans('user.label_form_password'),
        ]);

$createUser = function (UserService $service) {
    try {
        $this->alertMessage = null;

        $this->resetErrorBag();

        $validated = $this->validate();

        $data = UserDto::from($validated);

        $service->create($data);

        $this->dispatch('hideModalUserJs');
    } catch (ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        $this->reset('role_id', 'resident_id', 'name', 'username', 'default_password', 'password', 'password_confirmation', );
        $this->alertMessage = $e->getMessage();
    }
};

on(['syncFormCreateUser']);

$syncFormCreateUser = action(function (array $data) {
    $this->role_id = $data['role_id'] ?? $this->role_id;
    $this->resident_id = $data['resident_id'] ?? $this->resident_id;
    $this->name = $data['name'] ?? $this->name;
    $this->username = $data['username'] ?? $this->username;
    $this->default_password = $data['default_password'] ?? $this->default_password;
    $this->password = $data['password'] ?? $this->password;
    $this->password_confirmation = $data['password_confirmation'] ?? $this->password_confirmation;
});

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('user.label_add_user') }}</h5>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        <div class="alert alert-danger alert-dismissible fade @if($alertMessage != null) d-block show @else d-none @endif"
            role="alert">
            {{ $alertMessage }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form wire:submit="createUser" id="user-form">
            <div class="row">
                <div class="form-group col-12 col-md-12 mb-3">
                    <x-input-label for="role" :value="__('user.label_form_role')" :isRequired="true" />
                    <livewire:components.widget.list-role wire:model="role_id" :id="'role'" :class="'select2'" :name="'role_id'" :ariaDescribedby="'roleIdHelp'" />
                    <x-input-error id="roleIdHelp" :messages="$errors->get('role_id')" />
                </div>
                <div id="resident-section" class="col-12 col-md-12 mb-3 @if($role_id != RoleEnum::Warga->value) d-none @endif">
                    <x-input-label for="resident" :value="__('user.label_form_resident')" :isRequired="true" />
                    <livewire:components.widget.list-resident wire:model="resident_id" :id="'resident'" :class="'select2'" :name="'resident_id'" :ariaDescribedby="'residentIdHelp'" />
                    <x-input-error id="residentIdHelp" :messages="$errors->get('resident_id')" />
                </div>


                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="name" :value="__('user.label_form_name')" :isRequired="true" />
                    <x-text-input wire:model="name" id="name" type="text" name="name" aria-describedby="nameHelp" />
                    <x-input-error id="nameHelp" :messages="$errors->get('name')" />
                </div>
                <div class="form-group col-12 col-md-6 mb-3">
                    <x-input-label for="username" :value="__('user.label_form_username')" :isRequired="true" />
                    <x-text-input wire:model="username" id="username" type="text" name="username" aria-describedby="usernameHelp" />
                    <x-input-error id="usernameHelp" :messages="$errors->get('username')" />
                </div>

                <div class="form-group col-12 col-md-12 mb-3">
                    <div class="custom-control custom-checkbox">
                        <x-text-input
                            wire:model="default_password"
                            id="default-password"
                            class="custom-control-input"
                            type="checkbox"
                            name="default_password"
                            :isNotFormControl="true"/>
                        <x-input-label for="default-password" class="custom-control-label" :value="__('user.label_form_default_password')" />
                    </div>
                </div>

                <div class="password-csection form-group col-12 col-md-6 mb-3 @if($default_password) d-none @endif">
                    <x-input-label for="password" :value="__('user.label_form_password')" :isRequired="true" />
                    <x-text-input wire:model="password" id="password" type="password" name="password" aria-describedby="passwordHelp" />
                    <x-input-error id="passwordHelp" :messages="$errors->get('password')" />
                </div>
                <div class="password-csection form-group col-12 col-md-6 mb-3 @if($default_password) d-none @endif">
                    <x-input-label for="password_confirmation" :value="__('user.label_form_password_confirmation')" />
                    <x-text-input wire:model="password_confirmation" id="password-confirmation" type="password" name="password_confirmation" aria-describedby="passwordConfirmationHelp" />
                    <x-input-error id="passwordConfirmationHelp" :messages="$errors->get('password')" />
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button wire:target="createUser" wire:loading.attr="disabled" type="button" class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0" data-dismiss="modal">
            {{ __('label.cancel') }}
        </button>

        <button wire:loading.remove type="submit" form="user-form" class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            {{ __('label.save') }}
        </button>
        <button wire:loading class="btn btn-lg btn-primary font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>
    </div>
</div>

@script
<script>
    // jquery handler
    $(function () { });

    let isWargaBefore = false;

    $(document).on("change", "#role", function () {
        let role = $(this).find('option:selected');
        let roleId = role.val();
        let isWarga = role.data('iswarga');


        $("#resident-section").toggleClass('d-none', isWarga !== 1);
        if (isWarga) {
            isWargaBefore = isWarga;
            $("#resident").val('').trigger('change');
        } else {
            let states = {};
            if (isWargaBefore) {
                states = {
                    name: '',
                    username: '',
                    resident_id: null
                }
                isWargaBefore = false;
            }
            states.role_id = roleId;
            Livewire.dispatch('syncFormCreateUser', { data: states });
        }
    });

    $(document).on("change", "#resident", function () {
        let resident = $(this).find('option:selected');
        let residentId = resident.val();
        let residentName = "";
        let residentHousingBlock = "";

        if (residentId != "") {
            residentName = resident.data('name');
            residentHousingBlock = resident.data('housing-block').replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
        }

        $("#name").val(residentName).trigger('change');
        $("#username").val(residentHousingBlock).trigger('change');

        let states = {
            resident_id: residentId,
            name: residentName,
            username: residentHousingBlock,
            role_id: $("#role").val()
        };
        Livewire.dispatch('syncFormCreateUser', { data: states });
    });

    $(document).on("change", "#default-password", function () {
        let defaultPassword = $(this);

        $(".password-csection").toggleClass('d-none', defaultPassword.prop("checked"));
        $(".password-csection").find('input').val('').trigger('change');

        let states = {};
        if (defaultPassword.prop("checked")) {
            states = {
                password: '',
                password_confirmation: ''
            };
        }

        states.default_password = defaultPassword.prop("checked");
        Livewire.dispatch('syncFormCreateUser', { data: states });
    });
</script>
@endscript
