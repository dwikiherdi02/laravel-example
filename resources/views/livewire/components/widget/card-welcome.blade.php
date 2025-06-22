<?php
use Illuminate\Support\Facades\Auth;

use App\Livewire\Actions\Logout;

use function Livewire\Volt\{state, mount};

state([
    'authName' => '',
    'authRoleName' => '',
]);

mount(function () {
    $auth = Auth::user();

    $this->authName = $auth->name;

    $this->authRoleName = $auth->role->name;
});

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: false);
};

?>

<div>
    <div class="card">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column w-75">
                    <div class="fs-5 font-weight-bold p-0 m-0">
                        {{ __('widget.label_welcome') }}
                    </div>
                    <div class="fs-6 text-muted p-0 m-0">
                        {{ $authName }}
                    </div>
                </div>
                <div class="align-self-center text-right w-25">
                    <button wire:click="logout" class="btn btn-icon btn-white btn-hover-shine border shadow-sm">
                        <i class="lnr-enter btn-icon-wrapper"></i>
                        {{ __('widget.label_logout') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
