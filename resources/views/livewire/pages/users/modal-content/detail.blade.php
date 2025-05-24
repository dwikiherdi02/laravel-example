<?php

use App\Enum\RoleEnum;
use App\Services\UserService;

use function Livewire\Volt\{placeholder, state, mount};

placeholder('components.loading');

state([
    'id',
    'user'
]);

mount(function (UserService $service) {
    $this->user = array_to_object($service->findById($this->id)->toArray());
});

?>

<div>
    <div class="modal-header bg-transparent border-0">
        <h5 class="modal-title" id="modal-resident-title">{{ __('user.label_detail_user') }}</h5>
        <button type="button" class="close" aria-label="Close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body scrollbar-container">
        <div class="card bg-transparent border-0 shadow-none">
            <div class="card-header d-flex px-0">
                <i class="header-icon fas fa-address-card icon-gradient bg-happy-itmeo py-1 d-block"> </i> {{ __('Data Pengguna') }}
            </div>
            <div class="card-body px-0">
                @if ($user->role_id != RoleEnum::Warga->value)
                <label class="font-weight-bold">{{ __('user.label_name') }}</label>
                <p>{{ $user->name }}</p>
                @endif

                <label class="font-weight-bold">{{ __('user.label_username') }}</label>
                <p>{{ $user->username }}</p>

                <label class="font-weight-bold">{{ __('user.label_assigned_role') }}</label>
                <p>
                    <span class="mb-2 mr-2 badge badge-focus">{{ $user->role->name }}</span>
                </p>
                
            </div>
        </div>

        @if ($user->role_id == RoleEnum::Warga->value)
        <div class="card bg-transparent border-0 shadow-none">
            <div class="card-header d-flex px-0">
                <i class="header-icon fas fa-user icon-gradient bg-happy-itmeo py-1 d-block"> </i> {{ __('Data Warga') }}
            </div>
            <div class="card-body px-0">
                <label class="font-weight-bold">{{ __('resident.short_label_name') }}</label>
                <p>{{ $user->resident->name }}</p>

                <label class="font-weight-bold">{{ __('resident.label_phone_number') }}</label>
                <p>{{ $user->resident->phone_number }}</p>
                
                <label class="font-weight-bold">{{ __('resident.short_label_unique_code') }}</label>
                <p>{{ $user->resident->unique_code }}</p>
                
                <label class="font-weight-bold">{{ __('resident.label_address') }}</label>
                <p>{{ $user->resident->address }}</p>
            </div>
        </div>
        @endif
    </div>
    <div class="modal-footer bg-transparent d-flex justify-content-between w-100 px-0 pb-0 border-0">
        <button
            type="button"
            class="btn btn-lg btn-danger font-weight-bolder text-uppercase text-decoration-none w-100 m-0 py-3 rounded-0"
            data-dismiss="modal">
            {{ __('label.close') }}
        </button>
    </div>
</div>
