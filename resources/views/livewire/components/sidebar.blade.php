<?php

use App\Services\ComponentService;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\{state, mount};

state([
    'sidebars' => [],
]);

mount(function (ComponentService $service) {
    $this->sidebars = $service->getSidebars(auth_role());

});

?>

<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
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
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            @persist('sidebar')
            <ul class="vertical-nav-menu">
                @foreach ($sidebars as $item)
                    <li class="app-sidebar__heading">{{ __($item->name_lang_key) }}</li>
                    @foreach ($item->menus as $menu)
                    <li>
                        <a href="{{ $menu->slug }}" wire:current="mm-active" wire:navigate>
                            <i class="metismenu-icon {{ $menu->icon }}">
                            </i>
                            {{ __($menu->name_lang_key) }}
                        </a>
                    </li>
                    @endforeach
                @endforeach
            </ul>
            @endpersist
        </div>
    </div>
</div>
