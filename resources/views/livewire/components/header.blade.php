<?php
use Illuminate\Support\Facades\Auth;
use App\Facades\Avatar;
use App\Services\ComponentService;

use App\Livewire\Actions\Logout;

use function Livewire\Volt\{state, mount};

state([
    'avatar' => '',
    'avatarSquare' => '',
    'authName' => '',
    'authRoleName' => '',
    'menuShortcut' => [],
]);

mount(function (ComponentService $service) {
    $this->avatar = Avatar::create(Auth::user()->name)
        ->setFontFamily('Laravolt')
        ->toBase64();

    $this->avatarSquare = Avatar::create(Auth::user()->name)
        ->setFontFamily('Laravolt')
        ->setShape('square')
        ->toBase64();

    $auth = Auth::user();

    $this->authName = $auth->name;

    $this->authRoleName = $auth->role->name;

    $this->menuShortcut = $service->getMenuShortcuts(auth_role());
});

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: false);
};

?>

<!--Header START-->
<div class="app-header header-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-sm text-primary mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="app-header__content">
        <div class="app-header-right">
            <div class="header-btn-lg pr-0">
                @persist('header-widget')
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <img width="35" class="rounded-circle" src="{{ $avatar }}" alt="">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header mb-0">
                                        <div class="dropdown-menu-header-inner bg-focus">
                                            <div class="menu-header-content btn-pane-right">
                                                <div class="avatar-icon-wrapper mr-2 avatar-icon-xl">
                                                    <div class="avatar-icon rounded">
                                                        <img src="{{ $avatarSquare }}" alt="Avatar">
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="menu-header-title">{{ $authName }}</h5>
                                                    <h6 class="menu-header-subtitle">{{ $authRoleName }}</h6>
                                                </div>
                                                <div class="menu-header-btn-pane">
                                                    <button wire:click="logout"
                                                        class="ladda-button btn btn-pill btn-primary"
                                                        data-style="slide-right"><span
                                                            class="ladda-label">{{ __('widget.label_logout') }}</span><span
                                                            class="ladda-spinner"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="p-0 list-group-item">
                                            <div class="grid-menu grid-menu-2col">
                                                <div class="no-gutters row">
                                                    @foreach ($menuShortcut as $menu)
                                                        <div class="col-sm-6">
                                                            <a href="{{ $menu->slug }}"
                                                                class="btn-icon-vertical btn-square btn-transition btn btn-outline-link"><i
                                                                    class="{{ $menu->icon }} btn-icon-wrapper btn-icon-lg mb-3">
                                                                </i>{{ __($menu->name_lang_key) }}</a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                {{ $authName }}
                            </div>
                            <div class="widget-subheading">
                                {{ $authRoleName }}
                            </div>
                        </div>
                        <div class="widget-content-right header-user-info ml-3"></div>
                    </div>
                </div>
                @endpersist
            </div>
        </div>
    </div>
</div>
<!--Header END-->
