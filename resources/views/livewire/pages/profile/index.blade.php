<?php

use App\Enum\RoleEnum;
use App\Services\ComponentService;

use function Livewire\Volt\{layout, state, title, mount};

layout('layouts.app');

state([
    'title',
    'icon',

    'auth' => auth()->user(),
]);

title(function (ComponentService $service) {
    $path = '/profile';
    $menu = $service->getMenuBySlug($path);
    $this->title = __($menu->name_lang_key ?? '');
    $this->icon = $menu->icon ?? '';
    return $this->title;
});

?>

<div>
    <x-page-heading :title="$title" :icon="$icon" />

    <div class="row">
        <div class="col-12">
            <div class="mb-3 card">
                <div class="card-header card-header-tab-animation">
                    <ul class="nav @if($auth->isWarga()) nav-justified @endif">
                        @if ($auth->role_id == RoleEnum::Warga)
                            <li class="nav-item"><a data-toggle="tab" href="#tab-general" class="nav-link show active text-uppercase">{{ __('Umum') }}</a></li>
                        @endif
                        <li class="nav-item"><a data-toggle="tab" href="#tab-authentication" class="nav-link show @if(!$auth->isWarga()) active @endif text-uppercase">{{ __('Otentikasi') }}</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        @if ($auth->isWarga())
                        <div class="tab-pane show active" id="tab-general" role="tabpanel">
                            <livewire:pages.profile.general :auth="$auth" />
                        </div>
                        @endif
                        <div class="tab-pane show @if(!$auth->isWarga()) active @endif" id="tab-authentication" role="tabpanel">
                            <div class="row">
                                <div class="col-12">Otentikasi</div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
